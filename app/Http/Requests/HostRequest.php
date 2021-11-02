<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class HostRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'host' => ['required', 'regex:~^((http|https)+?://)?(www\.)?[\w\-\.]{2,}\.[\w]{2,}$~i']
        ];
    }

    public function messages()
    {
        return [
            'host.regex' => 'url должен иметь вид https://example.ru',
        ];
    }
}
