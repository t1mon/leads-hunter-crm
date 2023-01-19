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
        return [
            'id' => $this->id,
            'name' => $this->user->name,
            'email' => $this->user->email,
            'role' => __('roles.'.$this->role),
            'view_fields' => $this->view_fields,
        ];
    }
}
