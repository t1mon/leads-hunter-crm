<?php

namespace App\Exports\V2\Project\Journal;

use App\Models\Project\Project;
use App\Models\Project\UserPermissions;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;

use App\Http\Resources\V2\Leads\Export as LeadResource;

class LeadExport implements FromCollection
{
    use Exportable;

    public function make(Project $project, Builder $leadsQuery, User $user, UserPermissions|null $permissions)
    {
        $this->project = $project;
        $this->leadsQuery = $leadsQuery;
        $this->user = $user;
        $this->permissions = $permissions;

        $this->result = []; //Отформатированное содержимое будущего документа

        return $this;
    } //make

    public function collection()
    {
        //Подготовка заголовков колонок
        $this->makeHeaders();

        //Запись лидов
        $this->makeRecords();

        return collect($this->result);
    } //collection

    private function makeHeaders(): void //Подготовка заголовков колонок
    {
        //Если пользователь админ, то показать все колонки
        if($this->user->isAdmin() || $this->permissions->isManager() )
        {
            //...
            // throw new \Exception('Пользователь админ');
        }
        // else 
        //     throw new \Exception('Пользователь не админ');
        
        //Показать колонки согласно разрешениям пользователя
        //...

        $lead = $this->leadsQuery->first();
        $resource = $this->user->isAdmin() || $this->permissions->isManager()
            ? new LeadResource($lead, $this->project)
            : (new LeadResource($lead, $this->project))->only($this->permissions->view_fields);

        $row = [];
        foreach($resource->resolve() as $key => $value){
            $row[] = $key === 'class_id'
                ? trans(key: "leads.fields.class")
                : trans(key: "leads.fields.{$key}");
        }
        
        $this->result[] = $row;
    } //makeHeaders

    private function makeRecords(): void //Запись лидов
    {        
        foreach($this->leadsQuery->lazy() as $lead)
        {
            $row = [];
            $row = $this->user->isAdmin() || $this->permissions->isManager()
                ? new LeadResource($lead, $this->project)
                : (new LeadResource($lead, $this->project))->only($this->permissions->view_fields);
            $this->result[] = $row;
        }
    } //makeRecords
}
