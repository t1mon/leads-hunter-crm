<?php

namespace App\Http\Requests\Api\V2\Project\Project\Settings;

use Illuminate\Foundation\Http\FormRequest;

use App\Repositories\Project\ReadRepository as ProjectReadRepository;

class ToggleRegionRequest extends FormRequest
{
    public function __construct(
        private ProjectReadRepository $projectReadRepository,
    )
    {} //Конструктор

    public function authorize()
    {
        //TODO Сделать проверку политик после слияния веток
        // $project = $this->projectReadRepository->findById(id: $this->project_id, fail: true);
        return true;
    }

    public function rules()
    {
        return [
            'project_id' => 'required|exists:projects,id',
            'value' => 'required|boolean',
        ];
    }
}
