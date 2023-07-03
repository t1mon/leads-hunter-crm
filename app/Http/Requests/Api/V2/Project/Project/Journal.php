<?php

namespace App\Http\Requests\Api\V2\Project\Project;

use Illuminate\Foundation\Http\FormRequest;

class Journal extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        //TODO сделать проверку полномочий здесь или в middleware для группы маршрутов
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'date_from' => 'nullable|date_format:Y-m-d',
            'date_to'   => 'nullable|date_format:Y-m-d',

            'nextcall_from' => 'nullable|date_format:Y-m-d',
            'nextcall_to'   => 'nullable|date_format:Y-m-d',

            'entry_filter' => 'nullable|integer|gte:1',

            'name' => 'nullable|string|max:256',

            'class' => 'nullable|array',
            'class.*' => 'nullable|integer|exists:leads_classes,id',

            'owner' => 'nullable|string|max:256',
            'phone' => 'nullable|integer|regex:/^\d+$/s',
            'email' => 'nullable|email',

            'cost_from' => 'nullable|integer|gte:0',
            'cost_to' => 'nullable|integer|gte:0',

            'city' => 'nullable|array',
            'city.*' => 'string|max:256',

            'company' => 'nullable|string|max:256',

            'region' => 'nullable|array',
            'region.*' => 'string|max:256',

            'manual_region' => 'nullable|array',
            'manual_region.*' => 'string|max:256',

            'referrer' => 'nullable|string|max:256',
            'source' => 'nullable|max:256',

            'utm_medium' => 'nullable|array',
            'utm_medium.*' => 'string|max:512',

            'utm_source' => 'nullable|array',
            'utm_source.*' => 'string|max:512',

            'utm_campaign' => 'nullable|array',
            'utm_campaign.*' => 'string|max:512',

            'utm_content' => 'nullable|array',
            'utm_content.*' => 'string|max:512',

            'utm_term' => 'nullable|array',
            'utm_term.*' => 'string|max:512',

            'host' => 'nullable|array',
            'host.*' => 'string|max:512',

            'sort_by' => 'nullable|string',
            'sort_order' => 'required_with:sort_by|string|in:asc,desc',
        ];
    }

    public function messages()
    {
        return [
            //
        ];
    } //messages
}
