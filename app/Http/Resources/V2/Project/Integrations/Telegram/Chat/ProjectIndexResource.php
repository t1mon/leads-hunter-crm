<?php

namespace App\Http\Resources\V2\Project\Integrations\Telegram\Chat;

use Illuminate\Http\Resources\Json\JsonResource;

class ProjectIndexResource extends JsonResource
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
            'title' => $this->title,
            'confirmed' => $this->confirmed,
            'enabled' => $this->enabled,
        ];
    }
}
