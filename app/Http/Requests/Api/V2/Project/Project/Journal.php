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
            'entry_filter' => 'nullable|'.Rule::in(['>','='])
        ];
    }
}
