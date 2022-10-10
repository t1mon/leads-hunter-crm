<?php
    namespace App\Repositories\Project\Integrations\Mango;

use App\Models\Project\Integrations\Mango;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Pagination\LengthAwarePaginator;

class ReadRepository{
    //
    //  Открытые методы
    //
    public function query(): \Illuminate\Database\Eloquent\Builder //Общая функция для запросов
    {
        return Mango::query();
    } //query

    public function findAll(int $perPage = 50): LengthAwarePaginator
    {
        return Mango::paginate($perPage);
    } //findAll

    public function findById(int $id, bool $fail = false, string|array $with = null): ?Mango
    {
        return $this->_findByData(field: 'id', value: $id, fail: $fail, with: $with);
    } //findById

    public function findByName(string $name, bool $fail = false, string|array $with = null): ?Mango
    {
        return $this->_findByData(field: 'name', value: $name, fail: $fail, with: $with);
    } //findByProject

    public function findByProjectId(int $project_id, int $perPage = 50, string|array $with = null): LengthAwarePaginator
    {
        return $this->_findByData(field: 'project_id', value: $project_id, perPage: $perPage, with: $with);
    } //findByProjectId

    public function find(int|string $mango, bool $fail = false, string|array $with = null): ?Mango //Общая функция поиска для удобства
    {
        //Поиск по id
        $result = $this->findById(id: $mango, with: $with);

        //Поиск по имени
        if(is_null($result))
            $result = $this->findByName(name: $mango, with: $with);

        if(is_null($result) && $fail === true)
            throw new ModelNotFoundException();

        //Поиск по project_id не осуществляется, потому что значения id могут совпадать, что приведёт к путанице

        return $result;
    } //find

    //
    //  Скрытые методы
    //
    protected function _findByData(string $field, int|string $value, int $perPage = 1, bool $fail = false, null|string|array $with = null): null|Mango|LengthAwarePaginator //Общий метод для поиска по атрибутам модели
    {
        $query = Mango::where($field, $value)
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