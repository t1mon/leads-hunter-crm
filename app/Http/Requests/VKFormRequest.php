<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class VKFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::check() || Auth::guard('api')->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'confirmation_response' => 'required',
            'group_id' => 'required',
            'host' => [
                'required',
                'url',
                Rule::unique('vk_forms')->where(function($query){
                    return $query->where(['host' => $this->host, 'project_id' => $this->project_id]);
                })
            ],
            'source' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'confirmation_response.required' => 'Необходимо указать код ответа',
            'group_id.required' => 'Необходимо указать идентификатор группы',
            'host.required' => 'Необходимо указать посадочную',
            'host.url' => 'Посадочная должна указываться в формате действительного URL-адреса',
            'host.unique' => 'Форма с такой посадочной уже есть в проекте',
        ];
    }
}
