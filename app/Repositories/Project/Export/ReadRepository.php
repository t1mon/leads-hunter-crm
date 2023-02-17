<?php

namespace App\Repositories\Project\Export;

use App\Models\Project\Export;

use Illuminate\Pagination\LengthAwarePaginator;

class ReadRepository{
    //
    //  Открытые методы
    //
    public function query(): \Illuminate\Database\Eloquent\Builder //Общая функция для запросов
    {
        return Export::query();
    } //query

    public function findAll(int $perPage = 50): LengthAwarePaginator
    {
        return Export::valid()->paginate($perPage);
    } //findAll

    public function findById(int $id, bool $fail = false, string|array $with = null): ?Export
    {
        return $this->_findByData(field: 'id', value: $id, fail: $fail, with: $with);
    } //findById

    public function findByToken(string $token, bool $fail = false, string|array $with = null): ?Export
    {
        $query = $this->query()->valid()->token($token)
            ->when($with, function($q) use ($with){
                return $q->with($with);
            });

        return $fail ? $query->firstOrFail() : $query->first();
    } //findByToken

    public function find(int $export, bool $fail = false, string|array $with = null): ?Export //Общая функция поиска для удобства
    {
        //Поиск по id
        $result = $this->findById(id: $export, with: $with);

        //Поиск по другим результатам
        // ...
        //

        if(is_null($result) && $fail === true )
            throw new \Illuminate\Database\Eloquent\ModelNotFoundException();

        return $result;
    } //find

    //
    //  Скрытые методы
    //
    protected function _findByData(string $field, int|string $value, int $perPage = 1, bool $fail = false, null|string|array $with = null): null|Export|LengthAwarePaginator //Общий метод для поиска по атрибутам модели
    {
        $query = Export::where($field, $value)
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