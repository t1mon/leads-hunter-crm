<?php

namespace App\Http\Requests\Api;

use App\Models\Project;
use Illuminate\Foundation\Http\FormRequest;

class LeadsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(\Illuminate\Http\Request $request)
    {
        if (!$request->id || !$request->token) {
            return false;
        }
        if (!$project = Project::find($request->id)) {
            return false;
        }
        return $project->secret_token === $request->token;

    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //'id' => 'required|integer',
            //'token' => 'required',
            'name' => 'required',
            'phone' => 'required|integer'
        ];
    }
}
