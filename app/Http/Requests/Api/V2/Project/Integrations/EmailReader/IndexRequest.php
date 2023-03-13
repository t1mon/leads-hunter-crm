<?php

namespace App\Http\Requests\Api\V2\Project\Integrations\EmailReader;

use Illuminate\Foundation\Http\FormRequest;

class IndexRequest extends FormRequest
{
    public function __construct(
        private \App\Repositories\Project\ReadRepository $projectReadRepository,
    )
    {
        //
    }
    
    public function authorize()
    {
        $project = $this->projectReadRepository->findById(id: $this->project_id, fail: true);
        return $this->user()->can('viewAny', \App\Models\Project\Integrations\EmailReader::class, $project);
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
