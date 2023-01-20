<?php

namespace App\Http\Requests\Api\V2\Project\UserPermissions;

use App\Models\Leads;
use App\Models\Project\UserPermissions;
use App\Models\Role;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

use App\Repositories\Project\ReadRepository as ProjectReadRepository;

class AssignRequest extends FormRequest
{
    public function __construct(
        private ProjectReadRepository $projectReadRepository,
    )
    {
        //
    } //Конструктор

    public function authorize()
    {
        //Загрузка данных
        $project = $this->projectReadRepository->findById(id: $this->project_id, fail: true);

        //Авторизация
        return $this->user()->can('create', [UserPermissions::class, $project]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'project_id' => 'required|exists:projects,id',
            
            'users' => 'required|array',
            'users.*.email' => 'required|exists:users,email',
            'users.*.role' => ['required', Rule::in([Role::ROLE_MANAGER, Role::ROLE_JUNIOR_MANAGER, Role::ROLE_WATCHER])],
            'users.*.fields' => 'array',
            'users.*.fields.*' => [Rule::in(Leads::getFields())],
        ];
    }
}
