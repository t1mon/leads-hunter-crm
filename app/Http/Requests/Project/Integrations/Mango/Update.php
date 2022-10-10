<?php

namespace App\Http\Requests\Project\Integrations\Mango;

use Illuminate\Foundation\Http\FormRequest;

class Update extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
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
            'name' => 'nullable|string|max:255',
            'project_id' => 'nullable|exists:projects,id',
            'vpbx_api_key' => 'nullable|string|max:255',
            'vpbx_api_salt' => 'nullable|string|max:255',
            'enabled' => 'nullable|boolean',
        ];
    }
}
