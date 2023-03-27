<?php

namespace App\Http\Requests\Api\V2\Project\Integrations\Telegram\Bot;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRequest extends FormRequest
{
    public function __construct(
        private \App\Repositories\Project\Integrations\Telegram\Bot\ReadRepository $botReadRepository,
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
        $bot = $this->botReadRepository->findById(id: $this->bot, fail: true, with: 'project');
        return $this->user()->can('common', \App\Models\Project\Integrations\Telegram\Bot::class, $bot->project);
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
            'username' => Rule::unique(table: 'integrations_tg_bots', column: 'username')->where('project_id', $this->project_id)->ignore($this->bot),
            'api_token' => 'required|string',
            'enabled' => 'boolean',
        ];
    }
}
