<?php

namespace App\Http\Resources;

use App\Models\Leads as LeadsModel;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;

class Leads extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'status'   => $this->resource->entries > 1 ? LeadsModel::LEAD_EXISTS : LeadsModel::LEAD_NEW,
            'lead_id'  => $this->resource->id,
            'message'  => $this->resource->entries > 1 ? trans('leads.exists') : trans('leads.created'),
            'entries'  => $this->when($this->resource->entries > 1, $this->resource->entries),
            'response' => $this->resource->entries > 1 ? Response::HTTP_OK : Response::HTTP_CREATED,
        ];
    }

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse|object
     */
    public function toResponse($request)
    {
        if ($this->resource->entries > 1)
            return parent::toResponse($request)->setStatusCode(Response::HTTP_OK);

        return parent::toResponse($request)->setStatusCode(Response::HTTP_CREATED);
    }
}
