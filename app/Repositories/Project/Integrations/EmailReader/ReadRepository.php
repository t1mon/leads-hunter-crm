<?php

namespace App\Repositories\Project\Integrations\EmailReader;

use App\Models\Project\Integrations\EmailReader;
use App\Models\Project\Project;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class ReadRepository{
    //
    //  Открытые методы
    //
    public function query(): \Illuminate\Database\Eloquent\Builder //Общая функция для запросов
    {
        return EmailReader::query();
    } //query

    public function findAll(): Collection
    {
        return EmailReader::latest()->get();
    } //findAll

    public function findById(int $id, bool $fail = false, string|array $with = null): ?EmailReader
    {
        return $this->_findByData(field: 'id', value: $id, fail: $fail, with: $with);
    } //findById

    public function findByUser(User|int $user, string|array $with = null): Collection
    {
        return $this->query()->addedBy($user)
            ->when(!is_null($with), function($q) use ($with){
                return $q->with($with);
            })->get();
    } //findByUser

    public function findByProject(Project|int $project, string|array $with = null): Collection
    {
        return $this->query()->from($project)
            ->when(!is_null($with), function($q) use ($with){
                return $q->with($with);
            })->get();
    } //findByProject

    //
    //  Скрытые методы
    //
    protected function _findByData(string $field, int|string $value, int $perPage = 1, bool $fail = false, null|string|array $with = null): null|EmailReader|LengthAwarePaginator //Общий метод для поиска по атрибутам модели
    {
        $query = EmailReader::where($field, $value)
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