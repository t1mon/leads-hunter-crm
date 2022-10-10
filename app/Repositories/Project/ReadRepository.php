<?php
    namespace App\Repositories\Project;

use App\Models\Project\Project;
use Illuminate\Pagination\LengthAwarePaginator;

    class ReadRepository{
        //
        //  Открытые методы
        //
        public function query(): \Illuminate\Database\Eloquent\Builder //Общая функция для запросов
        {
            return Project::query();
        } //query

        public function findById(int $id, bool $fail = false, string|array $with = null): ?Project
        {
            return $this->_findByData(field: 'id', value: $id, fail: $fail, with: $with);
        } //findById

        public function findByName(string $name, bool $fail = false, string|array $with = null): ?Project
        {
            return $this->_findByData(field: 'name', value: $name, fail: $fail, with: $with);
        } //findByProject

        public function findByApiToken(string $api_token, bool $fail = false, string|array $with = null): ?Project
        {
            return $this->_findByData(field: 'api_token', value: $api_token, fail: $fail, with: $with);
        } //findByProject

        //
        //  Скрытые методы
        //
        protected function _findByData(string $field, int|string $value, int $perPage = 1, bool $fail = false, null|string|array $with = null): null|Project|LengthAwarePaginator //Общий метод для поиска по атрибутам модели
        {
            $query = Project::where($field, $value)
                ->when($with, function($q) use ($with){
                return is_null($with) ? $q : $q->with($with);
                });

            //Если нужно загрузить более одной модели, использовать пагинацию
            if($perPage > 1)
                return $query->paginate($perPage);

            //Если загружается только одна модель, проверить условие fail
            return $fail ? $query->firstOrFail() : $query->first();
        } //_findByProjectData
    };
?>