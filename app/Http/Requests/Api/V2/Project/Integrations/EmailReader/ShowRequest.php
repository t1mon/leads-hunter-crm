<?php

namespace App\Http\Requests\Api\V2\Project\Integrations\EmailReader;

use Illuminate\Foundation\Http\FormRequest;

class ShowRequest extends FormRequest
{
    public function __construct(
        private \App\Repositories\Project\Integrations\EmailReader\ReadRepository $emailReadRepository,
    )
    {
        //
    }
    
    public function authorize()
    {
        $emailReader = $this->emailReadRepository->findById(id: $this->email_reader, fail: true, with: 'project');
        return $this->user()->can('view', \App\Models\Project\Integrations\EmailReader::class, $emailReader);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            
        ];
    }
}
