<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateChecklistItemRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'description' => 'required_with:description:description|string|max:400',
            'checked' => 'nullable|boolean',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
