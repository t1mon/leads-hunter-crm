<?php

namespace App\Commands\V2\Project\Export;

use App\Exports\V2\Project\Journal\LeadExport;
use App\Jobs\V2\Project\Export\GenerateExportFile;
use App\Models\Project\Export;
use App\Models\Project\Project;
use App\Repositories\Project\Export\ReadRepository as ExportReadRepository;
use App\Repositories\Project\UserPermissions\ReadRepository as UserPermissionsRepository;
use App\Repositories\Project\ReadRepository as ProjectReadRepository;
use App\Repositories\Lead\ReadRepository as LeadReadRepository;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Response;

class StartExportHandler
{
    /**
     * StartExportHandler constructor.
     */
    public function __construct(
        private ExportReadRepository $exportReadRepository,
        private ProjectReadRepository $projectReadRepository,
        private LeadReadRepository $leadReadRepository,
        private UserPermissionsRepository $permissionsRepository,
    )
    {
    }

    /**
     * @param StartExportCommand $command
     */
    public function handle(StartExportCommand $command)
    {
        //Проверка лимитов
        if($this->exportReadRepository->query()->inProgress()->count() >= Export::LIMIT_PER_USER)
            return response(content: 'Превышен лимит на запросы экспорта', status: Response::HTTP_UNAUTHORIZED);

        //Загрузка проекта
        $project = $this->projectReadRepository->findById(id: $command->project, fail: true, with: 'classes');

        $permissions = $this->permissionsRepository->findByUserInProject(
            user: $command->user,
            project: $project
        );

        //Запуск задачи по экспорту на фоне
        dispatch(
            new GenerateExportFile(
                project: $project,
                request: $command->request->all(),
                user: $command->user,
                permissions: $permissions,
            )
        );

        return response(content: 'Начат экспорт. Вы получите уведомление, когда файл будет готов');
    } //handle
}
