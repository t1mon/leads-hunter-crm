<?php

namespace App\Http\Requests\Api\V2\Lead;

use Illuminate\Foundation\Http\FormRequest;

use App\Repositories\Lead\ReadRepository as LeadReadRepository;

class ClearNextcallRequest extends FormRequest
{
    public function __construct(
        private LeadReadRepository $leadReadRepository
    )
    {} //Конструктор

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;

        // $lead = $this->leadReadRepository->findById(id: $this->lead_id, fail: true, with: 'project');
        
        // return $this->user()->can('setNextcall', [Leads::class, $lead]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'lead_id' => 'required|exists:leads,id',
        ];
    }
}
