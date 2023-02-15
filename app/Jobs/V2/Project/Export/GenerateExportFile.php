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
use App\Repositories\Project\Export\Repository as ExportRepository;
use Carbon\Carbon;

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
        private Builder $leadsQuery,
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
        $name = implode(
            separator: '/',
            array: [
                Export::getDefaultStoragePath(),
                Carbon::today(tz: config('app.timezone'))->foramt('d-m-Y'),
                $this->project->id,
                $exportRepository->generateName($this->project)
            ]
        );

        //Создание записи об экспорте
        $exportRepository->create(
            project: $this->project,
            user: $this->user,
            name: $name
        );

        //Экспорт
        $exported = (new LeadExport())->make(
            project: $this->project,
            leadsQuery: $this->leadsQuery,
            user: $this->user,
            permissions: $this->permissions
        );

        //Сохранение экспортированного файла в хранилище
        //...

        //Срабатывание события о завершении экспорта
        //...
    }
}
