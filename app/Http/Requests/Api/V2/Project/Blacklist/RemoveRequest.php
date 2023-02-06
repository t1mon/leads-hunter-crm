<?php

namespace App\Http\Requests\Api\V2\Project\Blacklist;

use App\Models\Project\Blacklist;
use Illuminate\Foundation\Http\FormRequest;

use App\Repositories\Project\Blacklist\ReadRepository as BlacklistReadRepository;

class RemoveRequest extends FormRequest
{   
    public function __construct(
        private BlacklistReadRepository $repository,
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
        $blacklist = $this->repository->findById(id: $this->blacklist_id, fail: true);
        return $this->user()->can('delete', [Blacklist::class, $blacklist]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'blacklist_id' => 'required|exists:blacklists,id',
        ];
    }
}
