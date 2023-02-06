<?php

namespace App\Repositories\Project\Blacklist;

use App\Models\Project\Blacklist;
use App\Models\Project\Project;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class ReadRepository{
    private const PER_PAGE = 50;

    //
    //  Открытые методы
    //
    public function query(): \Illuminate\Database\Eloquent\Builder //Общая функция для запросов
    {
        return Blacklist::query();
    } //query

    public function findByProject(Project|int $project, int $perPage = self::PER_PAGE, string|array $with = null): LengthAwarePaginator
    {
        return $this->query()->from($project)
            ->when(!is_null($with), function($query) use ($with){
                return $query->with($with);
            })
            ->paginate($perPage);
    } //findByProject

    public function findById(int $id, bool $fail = false, string|array $with = null): ?Blacklist
    {
        return $this->_findByData(field: 'id', value: $id, fail: $fail, with: $with);
    } //findById

    public function findByPhone(string|int $phone): Collection
    {
        return $this->query()->phone($phone)->get();
    } //findByPhone

    //
    //  Скрытые методы
    //
    protected function _findByData(string $field, int|string $value, int $perPage = 1, bool $fail = false, null|string|array $with = null): null|Blacklist|LengthAwarePaginator //Общий метод для поиска по атрибутам модели
    {
        $query = Blacklist::where($field, $value)
            ->when($with, function($q) use ($with){
              return $q->with($with);
            });

        //Если нужно загрузить более одной модели, использовать пагинацию
        if($perPage > 1)
            return $query->paginate($perPage);

        //Если загружается только одна модель, проверить условие fail
        return $fail ? $query->firstOrFail() : $query->first();
    } //_findByProjectData

};

?>