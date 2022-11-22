<?php

namespace App\Http\Resources\V2\Leads;

use Illuminate\Http\Resources\Json\ResourceCollection;
use App\Http\Resources\V2\Leads\Journal as LeadResource;


class JournalCollection extends ResourceCollection
{
    protected array|null $visible = null;
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        // return parent::toArray($request);
        return $this->processCollection($request);
    }

    public function only(array|null $fields)
    {
        $this->visible = $fields;
        return $this;
    }

    protected function processCollection($request)
    {
        return $this->collection->map(function(LeadResource $resource) use ($request){
            return $resource->only($this->visible)->toArray($request);
        })->all();
    }
}
