<?php

namespace App\Repositories\Project\Integrations\Telegram\Bot;

use App\Models\Project\Integrations\Telegram\Bot;
use App\Models\Project\Project;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class ReadRepository{
    //
    //  Открытые методы
    //
    public function query(): \Illuminate\Database\Eloquent\Builder //Общая функция для запросов
    {
        return Bot::query();
    } //query

    public function findAll(int $perPage = 50): LengthAwarePaginator
    {
        return Bot::paginate($perPage);
    } //findAll

    public function findById(int $id, bool $fail = false, string|array $with = null): ?Bot
    {
        return $this->_findByData(field: 'id', value: $id, fail: $fail, with: $with);
    } //findById

    public function findByUsername(string $username, bool $fail = false, string|array $with = null): ?Bot
    {
        return $this->_findByData(field: 'username', value: $username, fail: $fail, with: $with);
    } //findById

    public function findByProject(Project|int $project): Collection
    {
        return $this->query()->from($project)->get();
    } //findByProject

    //
    //  Скрытые методы
    //
    protected function _findByData(string $field, int|string $value, int $perPage = 1, bool $fail = false, null|string|array $with = null): null|Bot|LengthAwarePaginator //Общий метод для поиска по атрибутам модели
    {
        $query = Bot::where($field, $value)
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