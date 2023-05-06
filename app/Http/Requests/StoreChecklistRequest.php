<?php

namespace App\Http\Requests;


class StoreChecklistRequest extends UpdateChecklistRequest
{
    protected $stopOnFirstFailure = true;

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
         return array_merge(
            [
                'items' => 'nullable|array',
                'items.*.description' => 'required_with:items|string|max:400',
                'items.*.checked' => 'required_with:items|boolean',
            ],
            parent::rules()
        );
    }
}
