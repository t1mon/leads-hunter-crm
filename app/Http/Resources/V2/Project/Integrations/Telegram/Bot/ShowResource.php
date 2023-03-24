<?php

namespace App\Http\Resources\V2\Project\Integrations\Telegram\Bot;

use Illuminate\Http\Resources\Json\JsonResource;

class ShowResource extends JsonResource
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
            'username' => $this->username,
            'api_token' => $this->api_token,
            'webhook_token' => $this->webhook_token,
            'webhook_status' => $this->checkWebhookStatus(),
            'enabled' => $this->enabled,
        ];
    }
}
