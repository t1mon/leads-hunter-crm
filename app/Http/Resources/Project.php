<?php

namespace App\Http\Resources;

use App\Models\Project\Project as ProjectModel;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;

class Project extends JsonResource
{
    //Дополнительные поля
    protected $optional = [];

    public function __construct(ProjectModel $resource, $optional = []){
        parent::__construct($resource);
        $this->optional = $optional;
    } //__construct
    
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        //Основные поля
        $main = [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'name' => $this->name,
            'settings' => $this->settings,
        ];

        return array_merge($main, $this->optional);
    }
}
