<?php
    namespace App\Repositories\Project;

use App\Models\Project\Project;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

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

    public function findByIds(array $ids): LengthAwarePaginator
    {
        return Project::whereIn('id', $ids)->paginate(50);
    } //findByIds

    public function findByName(string $name, bool $fail = false, string|array $with = null): ?Project
    {
        return $this->_findByData(field: 'name', value: $name, fail: $fail, with: $with);
    } //findByProject

    public function findByApiToken(string $api_token, bool $fail = false, string|array $with = null): ?Project
    {
        return $this->_findByData(field: 'api_token', value: $api_token, fail: $fail, with: $with);
    } //findByProject

    public static function countTotalLeads(int $project_id, bool $save = true): int //Посчитать лиды в проекте
    {
        $total = \App\Models\Leads::where('project_id', $project_id)->count();

        if($save)
            DB::table('leads_count')->updateOrInsert(attributes: ['project_id' => $project_id], values: ['total_leads' => $total]);

        return $total;
    } //countTotalLeads

    public static function countLeadsToday(int $project_id, bool $save = true): int //Посчитать лиды за сегодня
    {
        //Загрузка данных проекта
        $data = Project::where('id', $project_id)->select('settings')->firstOrFail();
        $timezone = $data['timezone'];

        $total = \App\Models\Leads::where('project_id', $project_id)
            ->where('created_at', '>=', Carbon::today($timezone))
            ->count();

        if($save)
            DB::table('leads_count')->updateOrInsert(attributes: ['project_id' => $project_id], values: ['leads_today' => $total]);
        
        return $total;
    } //countLeadsToday

    //
    //  Методы быстрой загрузки
    //
    public function findByIds_q(array $ids, array|string $select = '*', int $perPage = 10, string|array $with = null): LengthAwarePaginator //Загрузка всех записей
    {
        return Project::whereIn('id', $ids)
            ->when(!is_null($with), function($query, $with){
                return $query->with($with);
            })
            ->select($select)
            ->paginate($perPage);
    } //findAll_q

    public function findByIdsWithLeadsCount_q(array $ids, array|string $select = '*', int $perPage = 10): LengthAwarePaginator //Загрузка 
    {
        return Project::whereIn('projects.id', $ids)
            ->join('leads_count', 'projects.id', '=', 'leads_count.project_id')    
            ->select($select)
            ->paginate($perPage);
    } //findByIdsWithLeadsCount_q

    public function getTotalLeads(Project|int $project): ?int
    {
        $entry = DB::table('leads_count')->where('project_id', $project instanceof Project ? $project->id : $project)->first();
        return is_null($entry) ? null : $entry->total_leads;
    }
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