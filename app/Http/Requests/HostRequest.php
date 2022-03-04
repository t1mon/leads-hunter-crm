<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class HostRequest extends FormRequest
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
            'host' => ['required', 'regex:~^((http|https)+?://)?(www\.)?[\w\-\.]{2,}\.[\w]{2,}$~i', 
                        Rule::unique('hosts')->where(function($query){
                            return $query->where(['host' => $this->host, 'user_id' => Auth::user()->id] ?? Auth::guard('api')->user()->id);
                        })
                      ]
        ];
    }

    public function messages()
    {
        return [
            'host.regex' => 'url должен иметь вид https://example.ru',
            'host.unique' => 'Вы не можете добавлять повторяющиеся url'
        ];
    }
}
