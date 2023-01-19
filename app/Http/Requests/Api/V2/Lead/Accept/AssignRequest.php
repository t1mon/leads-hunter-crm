<?php

namespace App\Http\Requests\Api\V2\Lead\Accept;

use App\Models\Leads;
use Illuminate\Foundation\Http\FormRequest;
use App\Repositories\Lead\ReadRepository as LeadReadRepository;
use App\Repositories\User\ReadRepository as UserReadRepository;

class AssignRequest extends FormRequest
{
    public function __construct(
        private LeadReadRepository $leadReadRepository,
        private UserReadRepository $userReadRepository,
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
        $acceptor = $this->userReadRepository->findById(id: $this->acceptor_id, fail: true);
        
        return $this->user()->can('acceptLead', [Leads::class, $lead, $acceptor]);
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
            'acceptor_id' => 'required|exists:users,id',
        ];
    }
}
