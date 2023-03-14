<?php

namespace App\Http\Resources\V2\Project\Integrations\EmailReader;

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
            'user' => $this->whenLoaded(relationship: 'user', value: $this->user->name, default: $this->user_id),
            'email' => $this->email,
            'subject' => $this->subject,
            'created_at' => $this->created_at->setTimezone($this->project->timezone)->format('d.m.Y, H:i:s'),
        ];
    }
}
