<?php

namespace App\Exports\V2\Project\Journal;

use App\Models\Project\Project;
use App\Models\Project\UserPermissions;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;

class LeadExport implements FromCollection
{
    use Exportable;

    private $result = []; //Отформатированное содержимое будущего документа

    public function make(Project $project, Builder $leadsQuery, UserPermissions $permissions)
    {
        $this->project = $project;
        $this->leadsQuery = $leadsQuery;
        $this->permissions = $permissions;

        return $this;
    } //make

    public function collection()
    {
        //Подготовка заголовков колонок
        //...

        //Запись лидов
        //...

        return collect($this->result);
    } //collection

    private function makeHeaders(): void //Подготовка заголовков колонок
    {
        //Если пользователь админ, то показать все колонки
        //...
        
        //Показать колонки согласно разрешениям пользователя
        //...
    } //makeHeaders
}
