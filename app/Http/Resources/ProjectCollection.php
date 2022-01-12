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
            'id' => $this->id,
            'name' => $this->name,
            'color' => array_key_exists('color', $this->settings) ? $this->settings['color'] : null,
            'link' => route('project.journal', $this->id),
            'status' => (bool)$this->settings['enabled'],
            'totalLeads' => $this->leads->count(),
            'leadsToday' => $this->leadsToday()->count(),
            // 'created_at' => humanize_date($this->created_at)
            'created_at' => $this->created_at,
            'emailSend' =>
                 [
                    'enabled' => (bool) $this->settings['email']['enabled'],
                    'emailsList' => ProjectEmailsSendResource::collection($this->emails)
                ]
            ,
            'webhooks' => collect($this->settings['webhooks'])->map(function($item, $key){
                return [
                        'name' => $item['name'],
                        'url' => $item['url'],
                        'type' => $item['type'],
                        'enabled' => (bool) $item['enabled']
                ];
            })
        ];
    }
}
