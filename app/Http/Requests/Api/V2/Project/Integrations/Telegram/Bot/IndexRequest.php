<?php

namespace App\Http\Requests\Api\V2\Project\Integrations\Telegram\Bot;

use Illuminate\Foundation\Http\FormRequest;

class IndexRequest extends FormRequest
{
    public function __construct(
        // private \App\Repositories\Project\Integrations\Telegram\Bot\ReadRepository $botReadRepository,
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
        return $this->user()->can('common', \App\Models\Project\Integrations\Telegram\Bot::class, $project);
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
