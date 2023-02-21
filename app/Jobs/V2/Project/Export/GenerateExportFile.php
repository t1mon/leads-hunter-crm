<?php

namespace App\Jobs\V2\Project\Export;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use App\Models\Project\Export;
use App\Models\Project\Project;
use App\Models\Project\UserPermissions;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use App\Exports\V2\Project\Journal\LeadExport;
use App\Exports\V2\Project\Journal\BackgroundLeadExport;
use App\Repositories\Project\Export\Repository as ExportRepository;
use App\Http\Requests\Api\V2\Project\Project\Journal as JournalRequest;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Maatwebsite\Excel\Excel;
use Illuminate\Support\Arr;

class GenerateExportFile implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(
        private Project $project,
        // private Collection $leads,
        // private JournalRequest $request,
        private array $request,
        private User $user,
        private UserPermissions|null $permissions
    )
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(
        ExportRepository $exportRepository,
    )
    {
        //Составление имени и пути файла
        $token = $exportRepository->generateToken();
        $name = implode(
            separator: '/',
            array: [
                Carbon::today(tz: config('app.timezone'))->format('d-m-Y'),
                $this->project->id,
                $exportRepository->generateName(project: $this->project, token: $token),
            ]
        );

        //Создание записи об экспорте
        $exportRecord = $exportRepository->create(
            project: $this->project,
            user: $this->user,
            token: $token,
            name: $name
        );

        //Экспорт
        $exported = (new BackgroundLeadExport())->make(
            project: $this->project,
            leads: $this->_loadLeads(),
            user: $this->user,
            permissions: $this->permissions
        );

        //Сохранение экспортированного файла в хранилище
        $exported->store(filePath: $name, disk: Export::STORAGE_DISK_NAME, writerType: Excel::XLSX);

        //Срабатывание события о завершении экспорта
        event(new \App\Events\Projects\Export\ExportFinished($exportRecord));
    }

    private function _loadLeads()
    {
        $leadReadRepository = app(\App\Repositories\Lead\ReadRepository::class);

        //Первичный запрос
        $query = $leadReadRepository->findFromProject(
            project: $this->project,
            date_from: Arr::get(array: $this->request, key: 'date_from'),
            date_to: Arr::get(array: $this->request, key: 'date_to'),
            class: Arr::get(array: $this->request, key: 'class'),
            name: Arr::get(array: $this->request, key: 'name'),
            entries: Arr::get(array: $this->request, key: 'entries'),
            owner: Arr::get(array: $this->request, key: 'owner'),
            phone: Arr::get(array: $this->request, key: 'phone'),
            email: Arr::get(array: $this->request, key: 'email'),
            cost_from: Arr::get(array: $this->request, key: 'cost_from'),
            cost_to: Arr::get(array: $this->request, key: 'cost_to'),
            city: Arr::get(array: $this->request, key: 'city'),
            referrer: Arr::get(array: $this->request, key: 'referrer'),
            source: Arr::get(array: $this->request, key: 'source'),
            utm_medium: Arr::get(array: $this->request, key: 'utm_medium'),
            utm_source: Arr::get(array: $this->request, key: 'utm_source'),
            utm_campaign: Arr::get(array: $this->request, key: 'utm_campaign'),
            utm_content: Arr::get(array: $this->request, key: 'utm_content'),
            utm_term: Arr::get(array: $this->request, key: 'utm_term'),
            host: Arr::get(array: $this->request, key: 'host'),
            url_query_string: Arr::get(array: $this->request, key: 'url_query_string'),
            sort_by: Arr::get(array: $this->request, key: 'sort_by'),
            sort_order: Arr::get(array: $this->request, key: 'sort_order'),
        )
        ->with('class')
        ->get();

        // $query = $leadReadRepository->findFromProject(
        //     project: $this->project,
        //     date_from: $this->request->date_from,
        //     date_to: $this->request->date_to,
        //     class: $this->request->class,
        //     name: $this->request->name,
        //     entries: $this->request->entries,
        //     owner: $this->request->owner,
        //     phone: $this->request->phone,
        //     email: $this->request->email,
        //     cost_from: $this->request->cost_from,
        //     cost_to: $this->request->cost_to,
        //     city: $this->request->city,
        //     referrer: $this->request->referrer,
        //     source: $this->request->source,
        //     utm_medium: $this->request->utm_medium,
        //     utm_source: $this->request->utm_source,
        //     utm_campaign: $this->request->utm_campaign,
        //     utm_content: $this->request->utm_content,
        //     utm_term: $this->request->utm_term,
        //     host: $this->request->host,
        //     url_query_string: $this->request->url_query_string,
        //     sort_by: $this->request->sort_by,
        //     sort_order: $this->request->sort_order,
        // )
        // ->with('class')
        // ->get();

        return $query;
    }
}
