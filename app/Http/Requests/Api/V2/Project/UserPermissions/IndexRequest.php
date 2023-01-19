<?php

namespace App\Http\Requests\Api\V2\Project\UserPermissions;

use App\Models\Project\UserPermissions;
use Illuminate\Foundation\Http\FormRequest;
use App\Repositories\Project\ReadRepository as ProjectReadRepository;

class IndexRequest extends FormRequest
{
    public function __construct(
        private ProjectReadRepository $projectReadRepository,
    )
    {
        //
    } //Конструктор

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $project = $this->projectReadRepository->findById(id: $this->project_id, fail: true);

        return $this->user()->can('viewAny', [UserPermissions::class, $project]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'project_id' => 'required|exists:project,id',
        ];
    }
}
