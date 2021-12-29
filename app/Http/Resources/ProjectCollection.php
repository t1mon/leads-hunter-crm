<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProjectCollection extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'name' => $this->name,
            'link' => route('project.journal', $this->id),
            'enabled' => $this->settings['enabled'],
            'totalLeads' => $this->leads->count(),
            'leadsToday' => $this->leadsToday()->count(),
            'created_at' => $this->created_at
        ];
    }
}
