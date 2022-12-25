<?php

namespace App\Http\Requests\Api\V2\Lead\ManualRegion;

use Illuminate\Foundation\Http\FormRequest;

use App\Models\Leads;
use App\Repositories\Lead\ReadRepository as leadReadRepository;

class ClearRequest extends FormRequest
{
    public function __construct(
        private leadReadRepository $leadReadRepository
    )
    {
        
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $lead = $this->leadReadRepository->findById(id: $this->lead_id, fail: true);
        return $this->user()->can('setManualRegion', [Leads::class, $lead]);
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
