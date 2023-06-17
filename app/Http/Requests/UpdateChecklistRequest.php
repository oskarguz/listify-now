<?php

namespace App\Http\Requests;

use App\Enum\Visibility;
use App\Rules\Checklist\VisibilityValidation;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateChecklistRequest extends FormRequest
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
        return [
            'name' => 'required_with:name|string|max:400',
            'visibility' => ['bail', 'required_with:visibility', Rule::enum(Visibility::class), new VisibilityValidation()]
        ];
    }
}
