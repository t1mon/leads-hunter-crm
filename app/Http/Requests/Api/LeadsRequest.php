<?php

namespace App\Http\Requests\Api;

use App\Models\Project\Project;
use Illuminate\Auth\Access\AuthorizationException;
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
        // if (!$request->project_id || !$request->api_token) {
        if (!$request->api_token) {
            return false;
        }
        // if (!$project = Project::find($request->project_id)) {
        if (!$project = Project::where('api_token', $request->api_token)->first()) {
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
            // 'project_id' => 'required|integer|exists:projects,id',
            'name' => 'required|string',
            'surname' => 'nullable|string',
            'patronymic' => 'nullable|string',
            'cost' => 'nullable|string',
            'comment' => 'nullable|string',
            'city' => 'nullable|string',
            'phone' => 'required|integer|regex:/^\d+$/s',
            'ip' => 'nullable|ip',
            'email' => 'nullable|email',
            'utm' => 'nullable|json',
            //'host' => ['required', 'string', 'regex:~^((http|https)+?://)?(www\.)?[\w\-\.]{2,}\.[\w]{2,}$~i'],
            'host' => ['required', 'string', 'regex:~^((http|https)+?://)?(www\.)?[^.]+\.\S{2,4}$~iu'],
            'referrer' => 'nullable|string',
            'url_query_string' => 'nullable|string',
        ];
    }

    protected function failedAuthorization()
    {

            throw new AuthorizationException(trans('projects.access.denied'));
    }
}
