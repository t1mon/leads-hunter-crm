<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ProjectCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return $this->collection->map(function($item){
                    return [
                        'id' => $item->id,
                        'name' => $item->name,
                        'link' => route('project.journal',$item->id),
                        'status' => $item->settings['enabled'],
                        'totalLeads' => $item->leads->count(),
                        'leadsToday' => $item->leadsToday()->count(),
                        'created_at' => $item->created_at
                    ];
                });
    }
}
