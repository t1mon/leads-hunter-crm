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
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Maatwebsite\Excel\Excel;

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
        private Collection $leads,
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
        // $exported = (new LeadExport())->make(
        $exported = (new BackgroundLeadExport())->make(
            project: $this->project,
            leads: $this->leads,
            user: $this->user,
            permissions: $this->permissions
        );

        //Сохранение экспортированного файла в хранилище
        $exported->store(filePath: $name, disk: Export::STORAGE_DISK_NAME, writerType: Excel::XLSX);

        //Срабатывание события о завершении экспорта
        event(new \App\Events\Projects\Export\ExportFinished($exportRecord));
    }
}
