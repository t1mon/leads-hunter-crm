<?php

namespace App\Http\Requests\Api\V2\Lead\Accept;

use Illuminate\Foundation\Http\FormRequest;
use App\Repositories\Lead\ReadRepository as LeadReadRepository;


class GetUsersRequest extends FormRequest
{
    public function __construct(
        private LeadReadRepository $leadReadrepository,
    )
    {
        //
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true; //TODO Придумать подходящую логику
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'project_id' => 'required_without:project_token|exists:projects,id',
            'project_token' => 'required_without:project_id|exists:projects,api_token',
        ];
    }
}
