<?php

namespace App\Http\Resources;

use App\Models\Project\Project as ProjectModel;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;

class Project extends JsonResource
{
    protected $permissions;
    protected $leads;
    protected $hosts;
    protected $emails;
    protected $telegram_ids;

    public function __construct(ProjectModel $resource, $permissions = null, $leads = null, $hosts = null, $emails = null, $telegram_ids = null){
        parent::__construct($resource);
        $this->permissions = $permissions;
        $this->leads = $leads;
        $this->hosts = $hosts;
        $this->emails = $emails;
        $this->telegram_ids = $telegram_ids;
    } //__construct
    
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'name' => $this->name,
            'settings' => $this->settings,
            // 'leads' => $this->when(!is_null($this->filtered_leads), $this->filtered_leads),
            'permissions' => $this->when(!is_null($this->permissions), $this->permissions),
            'leads' => $this->when(!is_null($this->leads), $this->leads),
            'hosts' => $this->when(!is_null($this->hosts), $this->hosts),
            'emails' => $this->when(!is_null($this->emails), $this->emails),
            'telegram_ids' => $this->when(!is_null($this->telegram_ids), $this->telegram_ids),
        ];
    }
}
