<?php

namespace App\Http\Requests\Api\V2\Lead;

use Illuminate\Foundation\Http\FormRequest;

class ClearNextcallRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        //TODO Проверка политик
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
        ];
    }
}
