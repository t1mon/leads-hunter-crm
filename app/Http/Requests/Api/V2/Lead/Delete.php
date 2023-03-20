<?php

namespace App\Http\Requests\Api\V2\Lead;

use App\Models\Leads;
use Illuminate\Foundation\Http\FormRequest;

use App\Repositories\Lead\ReadRepository as LeadReadRepository;

class Delete extends FormRequest
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
        $lead = $this->leadReadRepository->findById(id: $this->lead_id, fail: true, with: 'project');
        
        return $this->user()->can('delete', [Leads::class, $lead]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'lead_id' => 'required|exists:leads,id'
        ];
    }
}
