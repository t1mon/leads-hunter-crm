<?php

namespace App\Http\Resources\V2\Project\Project;

use App\Models\Project\Project;
use App\Models\Project\UserPermissions;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Http\Resources\V2\Leads\Journal as LeadResource;
use App\Http\Resources\V2\Leads\JournalFull as FullLeadResource;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class Journal extends JsonResource
{
    public function __construct(
        Project $resource,
        private User $user,
        private ?UserPermissions $permissions,
        private LengthAwarePaginator|Collection $leads,
        // private Collection $classes
    )
    {
        parent::__construct($resource);
    } //Конструктор

    public function toArray($request)
    {
        // return parent::toArray($request);

        $leadResource = $this->user->isAdmin()
        ? LeadResource::collection($this->leads)
        : LeadResource::collection($this->leads)->only($this->collectFields());

        return [
            'id' => $this->id,
            'name' => $this->name,

            'leads' => $leadResource->response()->getData(),

            'classes' => $this->classes,
        ];
    }

    private function collectFields(): array
    {
        //TODO Брать базовые поля из репозитория UserPermissions
        return array_merge(
            UserPermissions::ALLOWED_BASIC_FIELDS,
            $this->permissions->view_fields
        );
    }
}
