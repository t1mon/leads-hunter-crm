<?php

namespace App\Repositories\Project\Integrations\Calltracking\Phone;

use App\Models\Project\Integrations\Calltracking\Phone;
use App\Models\Project\Project;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class ReadRepository{
    //
    //  Открытые методы
    //
    public function query(): \Illuminate\Database\Eloquent\Builder //Общая функция для запросов
    {
        return Phone::query();
    } //query

    public function findAll(int $perPage = 50): LengthAwarePaginator
    {
        return Phone::paginate($perPage);
    } //findAll

    public function findById(int $id, bool $fail = false, string|array $with = null): ?Phone
    {
        $query = $this->query()
            ->where('id', $id)
            ->when(!is_null($with), function($query) use ($with){
                return $query->with($with);
            });

        return $fail ? $query->firstOrFail() : $query->first();
    } //findById

    public function findByPhone(string|int $phone, bool $fail = false, string|array $with = null): ?Phone
    {
        $query = $this->query()
            ->phone($phone)
            ->when(!is_null($with), function($query) use ($with){
                return $query->with($with);
            });

        return $fail ? $query->firstOrFail() : $query->first();
    } //findByPhone

    public function findByProject(Project|int $project, bool $activeOnly = false, string|array $with = null): Collection
    {
        return $this->query()
            ->project($project)
            ->when($activeOnly, function($query){
                return $query->active();
            })
            ->when(!is_null($with), function($query) use ($with){
                return $query->with($with);
            })
            ->get();
    } //findByProject

};

?>