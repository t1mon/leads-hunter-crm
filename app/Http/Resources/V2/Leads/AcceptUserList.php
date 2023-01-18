<?php

namespace App\Http\Resources\V2\Leads;

use Illuminate\Http\Resources\Json\JsonResource;

class AcceptUserList extends JsonResource
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
            'id' => $this->user->id,
            'name' => $this->user->name,
        ];
    }
}
