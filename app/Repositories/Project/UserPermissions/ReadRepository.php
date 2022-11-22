<?php

namespace App\Repositories\Project\UserPermissions;

use App\Models\Project\Project;
use App\Models\Project\UserPermissions;
use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use InvalidArgumentException;

class ReadRepository{
    //
    //  Открытые методы
    //
    public function query(): \Illuminate\Database\Eloquent\Builder //Общая функция для запросов
    {
        return UserPermissions::query();
    } //query

    public function findAll(int $perPage = 50): LengthAwarePaginator
    {
        return UserPermissions::paginate($perPage);
    } //findAll

    public function findById(int $id, bool $fail = false, string|array $with = null): ?UserPermissions
    {
        return $this->_findByData(field: 'id', value: $id, fail: $fail, with: $with);
    } //findById

    public function findByUserInProject(User|int $user, Project|int $project, bool $fail = false): ?UserPermissions
    {
        $query = $this->query()->from($project)->for($user);
        return $fail ? $query->firstOrFail() : $query->first();
    } //findByUserInProject

    public function findByCurrentUserInProject(string $method = 'api', Project|int $project, bool $fail = false): ?UserPermissions
    {
        $method = Str::lower($method);
        if($method === 'api')
            return $this->findByUserInProject(user: Auth::guard('api')->id(), project: $project, fail: $fail);
        elseif($method === 'client')
            return $this->findByUserInProject(user: Auth::id(), project: $project, fail: $fail);
        else
            throw new InvalidArgumentException(message: 'Указан неизвестный метод ' . $method);
    } //findByCurrentUserInProject

    public function findProjectIdsByUser(int $userId): array
    {
        return UserPermissions::where('user_id', $userId)->select('project_id')->pluck('project_id')->all();
    } //findProjectsByUser

    //
    //  Скрытые методы
    //
    protected function _findByData(string $field, int|string $value, int $perPage = 1, bool $fail = false, null|string|array $with = null): null|UserPermissions|LengthAwarePaginator //Общий метод для поиска по атрибутам модели
    {
        $query = UserPermissions::where($field, $value)
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