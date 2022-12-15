<?php

namespace App\Http\Requests\Api\V2\Project\Lead\Comment;

use Illuminate\Foundation\Http\FormRequest;

class AddRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        //TODO Возможно, добавить сюда проверку полномочий пользователя
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'lead_id' => 'required|exists:leads,id',
            'comment_body' => 'required|string|max:512',
        ];
    }
}
