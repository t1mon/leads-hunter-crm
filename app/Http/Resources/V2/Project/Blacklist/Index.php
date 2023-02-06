<?php

namespace App\Http\Resources\V2\Project\Blacklist;

use Illuminate\Http\Resources\Json\JsonResource;

class Index extends JsonResource
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
            'phone' => $this->phone,
            'name' => $this->name,
            'comment' => $this->comment,
        ];
    }
}
