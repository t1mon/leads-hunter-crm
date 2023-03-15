<?php

namespace App\Http\Requests\Api\V2\Project\Integrations\EmailReader;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
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
        return $this->user()->can('update', \App\Models\Project\Integrations\EmailReader::class, $emailReader);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'email' => 'required|email',
            'password' => 'required|string',
            'host' => 'required|string',
            'template' => 'required|string',
            'enabled' => 'required|boolean',
            'interval' => 'required|integer|gte:1',
            'mails_per_time' => 'required|integer|gte:1',
        ];
    }
}
