<?php

namespace App\Http\Resources\Project;

use App\Http\Resources\ProjectEmailsSendResource;
use Illuminate\Http\Resources\Json\JsonResource;

class LeadsCount extends JsonResource
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
            'id' => $this->id,
            'totalLeads' => $this->leads->count(),
            'leadsToday' => $this->leadsToday()->count()
        ];
    }
}
