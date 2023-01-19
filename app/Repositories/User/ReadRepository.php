<?php

namespace App\Repositories\User;

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
        return User::query();
    } //query

    public function findAll(int $perPage = 50): LengthAwarePaginator
    {
        return User::paginate($perPage);
    } //findAll

    public function findFreeUsersForProject(Project $project): Collection //Найти пользователей, которые ещё не назначены на проект
    {
        $ids = $project->user_permissions()->pluck('user_id');
        return $this->query()->whereNotIn('id', $ids)->get();
    } //findFreeForProject

    public function findById(int $id, bool $fail = false, string|array $with = null): ?User
    {
        return $this->_findByData(field: 'id', value: $id, fail: $fail, with: $with);
    } //findById

    public function find(int $user, bool $fail = false, string|array $with = null): ?User //Общая функция поиска для удобства
    {
        //Поиск по id
        $result = $this->findById(id: $user, with: $with);

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
    protected function _findByData(string $field, int|string $value, int $perPage = 1, bool $fail = false, null|string|array $with = null): null|User|LengthAwarePaginator //Общий метод для поиска по атрибутам модели
    {
        $query = User::where($field, $value)
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