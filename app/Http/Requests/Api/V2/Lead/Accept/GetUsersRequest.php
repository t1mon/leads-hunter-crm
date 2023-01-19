<?php

namespace App\Http\Requests\Api\V2\Lead\Accept;

use App\Models\Project\Project;
use Illuminate\Foundation\Http\FormRequest;
use App\Repositories\Project\ReadRepository as ProjectReadRepository;


class GetUsersRequest extends FormRequest
{
    public function __construct(
        private ProjectReadRepository $projectReadRepository,
    )
    {
        //
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $project = $this->projectReadRepository->findById(id: $this->project_id);

        return $this->user()->can('getUsersForProject', [Project::class, $project]);
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
        ];
    }
}
