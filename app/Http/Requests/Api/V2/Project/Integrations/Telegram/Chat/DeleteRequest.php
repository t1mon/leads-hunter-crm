<?php

namespace App\Http\Requests\Api\V2\Project\Integrations\Telegram\Chat;

use Illuminate\Foundation\Http\FormRequest;

class DeleteRequest extends FormRequest
{
    public function __construct(
        private \App\Repositories\Project\Integrations\Telegram\Chat\Repository $chatRepository,
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
        $chat = $this->chatRepository->query()->with('project')->findOrFail($this->chat);
        return $this->user()->can('common', \App\Models\Project\Integrations\Telegram\Chat::class, $chat->project);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //
        ];
    }
}
