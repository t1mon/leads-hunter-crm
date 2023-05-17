<?php

namespace App\Repositories\Project\Integrations\Calltracking\Log;

use App\Models\Project\Integrations\Calltracking\Log;
use App\Models\Project\Project;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class ReadRepository{
    //
    //  Открытые методы
    //
    public function query(): \Illuminate\Database\Eloquent\Builder //Общая функция для запросов
    {
        return Log::query();
    } //query

    public function findAll(int $perPage = 50): LengthAwarePaginator
    {
        return $this->query()->paginate($perPage);
    } //findAll

    public function findById(int $id, bool $fail = false, string|array $with = null): ?Log
    {
        $query = $this->query()->where('id', $id)
            ->when(!is_null($with), function($q) use ($with){
                return $q->with($with);
            });

        return $fail ? $query->firstOrFail() : $query->first();
    } //findById

    public function findByProject(Project|int $project, int $perPage = 50): Collection
    {
        return $this->query()->project($project)->paginate($perPage);
    } //findByProject
};

?>