<?php

namespace App\Http\Resources\V2\Leads;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class Journal extends JsonResource
{
    protected array|null $visible = null;

    public function toArray($request)
    {
        //Заранее форматируются даты
        $this->nextcall_date = is_null($this->nextcall_date) ? null : Carbon::parse(time: $this->nextcall_date, tz: config('app.timezone'))->setTimezone($this->project->timezone)->format('Y.m.d H:i:s');

        // return parent::toArray($request);
        if(is_null($this->visible))
            return $this->fullData();

        $result = [
            'id' => $this->id,
            'name' => $this->getClientName(),
            'phone' => $this->phone,
            'created_at' => $this->created_at->format('d.m.Y H:i:s'),
            'comment_crm' => $this->when(in_array(needle: 'comment_crm', haystack: $this->visible),
                is_null($this->comment_crm) ? null
                :
                [
                    'id' => $this->comment_crm?->id,
                    'text' => $this->comment_crm?->comment_body,
                ]),
        ];

        //Удаление элемента comment_crm из массива
        $this->visible = array_flip($this->visible);
        unset($this->visible['comment_crm']);
        $this->visible = array_flip($this->visible);

        foreach($this->visible as $field)
            $result[$field] = $this->$field;

        return $result;

    }

    private function fullData(): array
    {
        return [
            'id' => $this->id,
            'owner' => $this->owner,
            'company' => $this->company,
            'name' => $this->getClientName(),
            'phone' => $this->phone,
            'entries' => $this->entries,
            'email' => $this->email,
            'cost' => $this->cost,
            'comment' => $this->comment,
            'city' => $this->city,
            'manual_region' => $this->manual_region,
            'ip' => $this->ip,
            'referrer' => $this->referrer,
            'source' => $this->source,
            'host' => $this->host,
            'url_query_string' => $this->url_query_string,
            'utm_medium' => $this->utm_medium,
            'utm_source' => $this->utm_source,
            'utm_campaign' => $this->utm_campaign,
            'utm_content' => $this->utm_content,
            'utm_term' => $this->utm_content,
            'class_id' => $this->class_id,
            'comment_crm' => $this->whenLoaded('comment_crm', [
                'id' => $this->comment_crm?->id,
                'text' => $this->comment_crm?->comment_body,
            ]),
            'nextcall_date' => $this->nextcall_date,
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
