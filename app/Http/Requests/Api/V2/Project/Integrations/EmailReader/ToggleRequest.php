<?php

namespace App\Http\Requests\Api\V2\Project\Integrations\EmailReader;

use Illuminate\Foundation\Http\FormRequest;

class ToggleRequest extends FormRequest
{
    public function __construct(
        private \App\Repositories\Project\Integrations\EmailReader\ReadRepository $emailReadRepository,
    )
    {
        //
    }
    
    public function authorize()
    {
        $emailReader = $this->emailReadRepository->findById(id: $this->reader_id, fail: true, with: 'project');
        return $this->user()->can('update', \App\Models\Project\Integrations\EmailReader::class, $emailReader);
    }

    public function rules()
    {
        return [
            'reader_id' => 'required|exists:integrations_email_reader',
        ];
    }
}
