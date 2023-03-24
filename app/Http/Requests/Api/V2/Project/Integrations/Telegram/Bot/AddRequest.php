<?php

namespace App\Http\Requests\Api\V2\Project\Integrations\Telegram\Bot;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AddRequest extends FormRequest
{
    public function __construct(
        // private \App\Repositories\Project\Integrations\Telegram\Bot\ReadRepository $botReadRepository,
        private \App\Repositories\Project\ReadRepository $projectReadRepository,
    )
    {
        //
    } //Конструктор

    public function authorize()
    {
        // $bot = $this->botReadRepository->findById(id: $this->bot_id, fail: true, with: 'project');
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
            'username' => Rule::unique(table: 'integrations_tg_bots', column: 'username')->where('project_id', $this->project_id),
            'bot_api_token' => 'required|string',
        ];
    }
}
