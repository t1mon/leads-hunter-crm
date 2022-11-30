<?php

namespace App\Http\Resources\V2\Leads;

use Illuminate\Http\Resources\Json\JsonResource;

class Journal extends JsonResource
{
    protected array|null $visible = null;

    public function toArray($request)
    {
        // return parent::toArray($request);
        if(is_null($this->visible))
            return $this->fullData();

        $fields = $this->visible;
        $result = [];
        foreach($fields as $field)
            $result[$field] = $this->$field;

        return $result;

    }

    private function fullData(): array
    {
        return [
            'id' => $this->id,
            'owner' => $this->owner,
            'name' => $this->getClientName(),
            'phone' => $this->phone,
            'entries' => $this->entries,
            'email' => $this->email,
            'cost' => $this->cost,
            'comment' => $this->comment,
            'city' => $this->city,
            'ip' => $this->ip,
            'referrer' => $this->referrer,
            'source' => $this->source,
            'host' => $this->host,
            'url_query_string' => $this->url_query_string,
            'utm_medium' => $this->utm_medium,
            'utm_source' => $this->utm_source,
            'utm_campaign' => $this->utm_campaign,
            'utm_content' => $this->utm_content,
            'class_id' => $this->class_id,
            'comment_crm' => $this->whenLoaded('comment_crm', [
                'id' => $this->comment_crm?->id,
                'text' => $this->comment_crm?->comment_body,
            ]),
            'created_at' => $this->created_at->format('d.m.Y H:i:s')
        ];
    } //fullData

    public static function collection($resource)
    {
        return tap(value: new JournalCollection($resource), callback: function($collection){
            $collection->collects = __CLASS__;
        });
    } //collection

    public function only(array|null $fields){
        $this->visible = $fields;
        return $this;
    }
}