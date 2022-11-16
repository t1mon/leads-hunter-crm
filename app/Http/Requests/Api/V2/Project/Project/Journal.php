<?php

namespace App\Http\Requests\Api\V2\Project\Project;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class Journal extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        //TODO сделать проверку полномочий здесь или в middleware для группы маршрутов
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
            'date_from' => 'nullable|date_format:Y-m-d',
            'date_to'   => 'nullable|date_format:Y-m-d',
            'entry_filter' => 'nullable|integer|gte:1',
            'owner' => 'nullable|string|max:256',
            'host' => 'nullable|string|max:256',
            'city' => 'nullable|string|max:256',
            'source' => 'nullable|string|max:256',
            'cost_from' => 'nullable|integer|gte:0',
            'cost_to' => 'nullable|integer|gte:0',
        ];
    }
}
