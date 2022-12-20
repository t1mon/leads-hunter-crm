<?php

namespace App\Http\Resources\V2\Leads;

use App\Models\Leads;
use App\Models\Project\Project;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class Export extends JsonResource
{
    protected Project $project_info;
    protected array|null $visible = null;

    public function __construct(Leads $resource, Project $project_info)
    {
        parent::__construct($resource);
        $this->project_info = $project_info;
    } //Конструктор

    public function toArray($request)
    {
        // return parent::toArray($request);
        if(is_null($this->visible))
            return $this->fullData();

        $result = [
            // 'id' => $this->id,
            'name' => $this->getClientName(),
            'phone' => $this->phone,
            'created_at' => $this->applyTimezone(),
            'comment_crm' => $this->when(in_array(needle: 'comment_crm', haystack: $this->visible), 
                is_null($this->comment_crm) ? null : $this->comment_crm?->comment_body
            ),
        ];

        if(in_array(needle: 'class_id', haystack: $this->visible))
            $result['class'] = $this->class?->name ?? null;

        //Удаление элемента comment_crm из массива
        $this->visible = array_flip($this->visible);
        unset($this->visible['comment_crm']);
        unset($this->visible['class_id']);
        $this->visible = array_flip($this->visible);

        foreach($this->visible as $field)
            $result[$field] = $this->$field;

        return $result;

    }

    private function fullData(): array
    {
        return [
            // 'id' => $this->id,
            'owner' => $this->owner,
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
            'class' => $this->whenLoaded('class', $this->class?->name),
            'comment_crm' => $this->whenLoaded('comment_crm', $this->comment_crm?->comment_body),
            'created_at' => $this->applyTimezone()
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

    private function applyTimezone()
    {
        return Carbon::parse($this->created_at, config('app.timezone'))->setTimezone($this->project_info->timezone)->format('d.m.Y H:i:s');
    }
}
