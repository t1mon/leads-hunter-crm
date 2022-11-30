<?php

namespace App\Repositories\Project\Host;

use App\Models\Project\Host;
use App\Models\Project\Project;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class ReadRepository{
    //
    //  Открытые методы
    //
    public function query(): \Illuminate\Database\Eloquent\Builder //Общая функция для запросов
    {
        return Host::query();
    } //query

    public function findAll(int $perPage = 50): LengthAwarePaginator
    {
        return Host::paginate($perPage);
    } //findAll

    public function findById(int $id, bool $fail = false, string|array $with = null): ?Host
    {
        return $this->_findByData(field: 'id', value: $id, fail: $fail, with: $with);
    } //findById

    public function find(int $host, bool $fail = false, string|array $with = null): ?Host //Общая функция поиска для удобства
    {
        //Поиск по id
        $result = $this->findById(id: $host, with: $with);

        //Поиск по другим результатам
        // ...
        //

        if(is_null($result) && $fail === true )
            throw new \Illuminate\Database\Eloquent\ModelNotFoundException();

        return $result;
    } //find

    public function findByProject(Project|int $project): Collection
    {
        return $this->query()->from($project)->get();
    } //findByProject

    //
    //  Скрытые методы
    //
    protected function _findByData(string $field, int|string $value, int $perPage = 1, bool $fail = false, null|string|array $with = null): null|Host|LengthAwarePaginator //Общий метод для поиска по атрибутам модели
    {
        $query = Host::where($field, $value)
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