<?php

namespace App\Http\Requests\Api\V2\Lead;

use Illuminate\Foundation\Http\FormRequest;

use App\Models\Leads;
use App\Repositories\Project\ReadRepository as ProjectReadRepository;

class AddManually extends FormRequest
{
    public function __construct(
        private ProjectReadRepository $projectReadRepository
    )
    {} //Конструктор

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $project = $this->projectReadRepository->findByApiToken($this->api_token, fail: true);

        return $this->user()->can('create', [Leads::class, $project]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'api_token' => 'required|exists:projects,api_token',
            'owner' => 'nullable|string',
            'name' => 'required|string',
            'surname' => 'nullable|string',
            'patronymic' => 'nullable|string',
            'phone' => 'required|integer|regex:/^\d+$/s',
            'email' => 'nullable|email',
            'cost' => 'nullable|string',
            'comment' => 'nullable|string',
            'city' => 'nullable|string',
            'manual_region' => 'nullable|string',
            'ip',
            'referrer' => 'nullable|string',
            'source' => 'nullable|string',
            'utm_medium' => 'nullable|string',
            'utm_source' => 'nullable|string',
            'utm_campaign' => 'nullable|string',
            'utm_content' => 'nullable|string',
            'utm_term' => 'nullable|string',
            'host' => ['required', 'string', 'regex:~^((http|https)+?://)?(www\.)?[\w\-\.]{2,}\.[\w]{2,}$~i',],
            'url_query_string' => 'nullable|string',
            'nextcall_date' => 'nullable|dateformat:Y-m-d H:i',
        ];
    }
}
