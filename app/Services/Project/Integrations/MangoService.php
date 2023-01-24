<?php

namespace App\Services\Project\Integrations;

use App\Models\Project\Integrations\Mango;
use App\Repositories\Project\Integrations\Mango\Repository as MangoRepository;
use App\Repositories\Project\Integrations\Mango\ReadRepository as MangoReadRepository;
use App\Http\Requests\Project\Integrations\Mango\Create as CreateRequest;
use App\Http\Requests\Project\Integrations\Mango\Update as UpdateRequest;
use App\Models\Project\Project;
use App\Repositories\Project\ReadRepository as ProjectReadRepository;
use Illuminate\Pagination\LengthAwarePaginator;

class MangoService{
    public function __construct(
        private MangoRepository $repository,
        private MangoReadRepository $readRepository,
        private ProjectReadRepository $projectRepository,   
    )
    {} //Конструктор

    //
    //  CUD-методы
    //
    public function create(CreateRequest $request): Mango
    {
        return $this->repository->create(
            name: $request->name,
            project_id: $request->project_id,
            vpbx_api_key: $request->vpbx_api_key,
            vpbx_api_salt: $request->vpbx_api_salt,
            enabled: $request->enabled ?? true,
        );
    } //create

    public function update(Mango|int|string $mango, UpdateRequest $request): Mango
    {
        if(!$mango instanceof Mango)
            $mango = $this->find(mango: $mango, fail: true);
        
        return $this->repository->update(mango: $mango,
            name: $request->name,
            project_id: $request->project_id,
            vpbx_api_key: $request->vpbx_api_key,
            vpbx_api_salt: $request->vpbx_api_salt,
            enabled: $request->enabled,
        );
    } //update

    public function toggle(Mango|int|string $mango): Mango
    {
        if(!$mango instanceof Mango)
            $mango = $this->find(mango: $mango, fail: true);
        
        return $this->repository->update(
            mango: $mango,
            enabled: !$mango->enabled
        );
    } //toggle

    public function remove(Mango|int|string $mango): void
    {
        if(!$mango instanceof Mango)
            $mango = $this->find(mango: $mango, fail: true);

        $this->repository->remove($mango);
    } //remove

    //
    //  R-методы
    //
    public function findById(int $id, bool $fail = false, string|array $with = null): ?Mango
    {
        return $this->readRepository->findById(id: $id, fail: $fail, with: $with);
    } //findById

    public function findByName(string $name, bool $fail = false, string|array $with = null): ?Mango
    {
        return $this->readRepository->findByName(name: $name, fail: $fail, with: $with);
    } //findByProject

    public function findByProjectId(Project|int $project, int $perPage = 50, string|array $with = null): LengthAwarePaginator
    {
        return $this->readRepository->findByProjectId(project_id: $project instanceof Project ? $project->id : $project, perPage: $perPage, with: $with);
    } //findByProjectId

    public function findByProjectIdEnabled(int $project_id, int $perPage = 50): LengthAwarePaginator
    {
        return $this->readRepository->findByProjectIdEnabled(project_id: $project_id, perPage: $perPage);
    } //findByProjectIdEnabled

    public function find(int|string $mango, bool $fail = false, string|array $with = null): ?Mango //Общая функция поиска для удобства
    {
        return $this->readRepository->find(mango: $mango, fail: $fail, with: $with);
    } //find

    public function findProject(int $id, bool $fail = false): ?Project
    {
        return $this->projectRepository->findById(id: $id, fail: $fail);
    } //findProject
};

?>