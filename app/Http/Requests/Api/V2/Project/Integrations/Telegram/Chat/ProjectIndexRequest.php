<?php

namespace App\Http\Requests\Api\V2\Project\Integrations\Telegram\Chat;

use Illuminate\Foundation\Http\FormRequest;

class ProjectIndexRequest extends FormRequest
{
    public function __construct(
        private \App\Repositories\Project\ReadRepository $projectReadRepository,
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
        return $this->user()->can('common', \App\Models\Project\Integrations\Telegram\Chat::class, $project);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
        ];
    }
}
