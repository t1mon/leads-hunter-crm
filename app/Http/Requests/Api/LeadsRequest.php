<?php

namespace App\Http\Requests\Api;

use App\Models\Project;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class LeadsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(\Illuminate\Http\Request $request)
    {
        if (!$request->id || !$request->api_token) {
            return false;
        }
        if (!$project = Project::find($request->id)) {
            return false;
        }
        return $project->api_token === $request->api_token;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(Request $request)
    {
        return [
            'id' => 'required|integer|exists:projects,id',
            'name' => 'required',
            'phone' => 'required|integer|regex:/^\d+$/s',
            'ip' => 'nullable|ip',
            'email' => 'nullable|email',
            'utm' => 'nullable|json'
        ];
    }
}
