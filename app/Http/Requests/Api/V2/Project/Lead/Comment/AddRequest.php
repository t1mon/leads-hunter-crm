<?php

namespace App\Http\Requests\Api\V2\Project\Lead\Comment;

use App\Models\Project\Lead\Comment;
use Illuminate\Foundation\Http\FormRequest;

use App\Repositories\Lead\ReadRepository as LeadReadRepository;

class AddRequest extends FormRequest
{
    public function __construct(
        private LeadReadRepository $leadRepository,
    )
    {
        
    } //Конструктор

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $lead = $this->leadRepository->findById(id: $this->lead_id, fail: true, with: 'project');

        return $this->user()->can('create', [Comment::class, $lead->project]);
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
            'comment_body' => 'required|string|max:512',
        ];
    }
}
