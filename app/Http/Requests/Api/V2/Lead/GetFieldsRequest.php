<?php

namespace App\Http\Requests\Api\V2\Lead;

use App\Models\Project\Project;
use Illuminate\Foundation\Http\FormRequest;

use App\Repositories\Project\ReadRepository as ProjectReadRepository;

class GetFieldsRequest extends FormRequest
{
    public function __construct(
        private ProjectReadRepository $projectReadRepository,
    )
    {
        //
    }

    public function authorize()
    {
        $project = $this->projectReadRepository->findById(id: $this->project_id, fail: true);
        return $this->user()->can('getLeadFields', [Project::class, $project]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'project_id' => 'required|exists:projects,id'
        ];
    }
}
