<?php

namespace App\Http\Requests\Api\V2\Lead;

use Illuminate\Foundation\Http\FormRequest;

use App\Repositories\Project\UserPermissions\ReadRepository as PermissionsRepository;

class AddNextcallRequest extends FormRequest
{
    public function __construct(
        private PermissionsRepository $permissionsRepository
    )
    {} //Конструктор

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $user = $this->user();
        
        // return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //
        ];
    }
}
