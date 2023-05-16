<?php

namespace App\Http\Requests\Api\V2\Project\Integrations\Calltracking;

use Illuminate\Foundation\Http\FormRequest;

class IncomingCall extends FormRequest
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
            'event' => 'required|in:CALL_TRACKING',
            'result' => 'array',
            'result.caller_id' => 'required|regex:/^\d+$/s',
            'result.caller_did' => 'required|regex:/^\d+$/s',
            'result.url' => 'required|url',

        ];
    }
}
