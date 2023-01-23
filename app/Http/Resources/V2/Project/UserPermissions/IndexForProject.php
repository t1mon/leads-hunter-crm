<?php

namespace App\Http\Resources\V2\Project\UserPermissions;

use Illuminate\Http\Resources\Json\JsonResource;

class IndexForProject extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        // return parent::toArray($request);

        //Формирования списка полей с переводом
        $viewFieldsTranslatred = [];
        foreach($this->view_fields as $field)
            $viewFieldsTranslatred[$field] = __('leads.fields.'.$field);

        //Возврат данных
        return [
            'id' => $this->id,
            'name' => $this->user->name,
            'email' => $this->user->email,
            'role' => $this->role,
            'owner' => $this->when(condition: $this->isOwner(), value: true),
            'view_fields' => $viewFieldsTranslatred,
        ];
    }
}
