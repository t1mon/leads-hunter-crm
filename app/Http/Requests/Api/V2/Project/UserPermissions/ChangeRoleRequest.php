<?php

namespace App\Http\Requests\Api\V2\Project\UserPermissions;

use App\Models\Leads;
use App\Models\Project\UserPermissions;
use App\Models\Role;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

use App\Repositories\Project\UserPermissions\ReadRepository as PermissionsReadRepository;

class ChangeRoleRequest extends FormRequest
{
    public function __construct(
        private PermissionsReadRepository $permissionsReadRepository,
    )
    {
        //
    }
    
    public function authorize()
    {
        $permissions = $this->permissionsReadRepository->findById(id: $this->permissions_id, fail: true, with: 'project');
        return $this->user()->can('update', [UserPermissions::class, $permissions]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'permissions_id' => 'required|exists:user_permissions,id',
            'role' => ['required', Rule::in([Role::ROLE_MANAGER, Role::ROLE_JUNIOR_MANAGER, Role::ROLE_WATCHER])],
            'fields' => 'nullable|array',
            'fields.*' => [Rule::in(Leads::getFields())],
        ];
    }
}
