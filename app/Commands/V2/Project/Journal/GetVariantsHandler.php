<?php

namespace App\Commands\V2\Project\Journal;

use App\Models\Project\UserPermissions;
use App\Repositories\Lead\ReadRepository as LeadRepository;
use App\Repositories\Project\UserPermissions\ReadRepository as UserPermissionsRepository;
use App\Repositories\Project\Host\ReadRepository as HostRepository;
use Illuminate\Support\Arr;
use Illuminate\Validation\UnauthorizedException;

class GetVariantsHandler
{
    public function __construct(
        private LeadRepository $leadRepository,
        private UserPermissionsRepository $permissionsRepository,
        private HostRepository $hostRepository,
    )
    {
    } //Конструктор

    /**
     * @param GetVariantsCommand $command
     */
    public function handle(GetVariantsCommand $command)
    {
        //Загрузка хостов
        if($command->column === 'host')
            return $this->hostRepository->query()->from($command->project)->select('host')->pluck('host');

        //Загрузка других полей
        return $this->leadRepository->query()
            ->from($command->project)
            ->whereNotNull($command->column)
            ->orderBy($command->column)
            ->distinct()
            ->pluck($command->column);
    }
}
