<?php

namespace App\Http\Resources\V2\Project\Project;

use Illuminate\Http\Resources\Json\JsonResource;

class Dashboard extends JsonResource
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
            'name' => $this->name,
            'settings' => [
                'enabled' => $this->settings['enabled'],
                'color' => $this->settings['color'],
            ],
            'total_leads' => $this->total_leads,
            'leads_today' => $this->leads_today,
        ];
    }
}
