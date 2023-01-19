<?php

namespace App\Http\Requests\Api\V2\Project\UserPermissions;

use App\Models\Project\UserPermissions;
use Illuminate\Foundation\Http\FormRequest;

use App\Repositories\Project\UserPermissions\ReadRepository as PermissionsReadRepository;

class DismissRequest extends FormRequest
{
    public function __construct(
        private PermissionsReadRepository $permissionsReadRepository
    )
    {
        //
    } //Конструктор

    public function authorize()
    {
        $permissions = $this->permissionsReadRepository->findById(id: $this->permissions_id, fail: true);
        return $this->user()->can('delete', [UserPermissions::class, $permissions]);
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
        ];
    }
}
