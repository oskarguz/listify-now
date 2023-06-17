<?php

namespace App\Rules\Checklist;

use App\Enum\Visibility;
use App\Models\Checklist;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class VisibilityValidation implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $request = request();
        $user = $request->user();
        /** @var Checklist $checklist */
        $checklist = $request->route('checklist');
        $visibility = Visibility::from($value);

        if (empty($user)) {
            if (!$checklist && $visibility === Visibility::Public) {
                return;
            }
            if ($checklist && $checklist->visibility === $visibility) {
                return;
            }
            $fail("Cannot change $attribute as anonymous user. Log in first!");
        }

        if ($user) {
            if (!$checklist) {
                return;
            }
            if (!$checklist->created_by_id && $checklist->visibility === $visibility) {
                return;
            }
            if ($checklist->created_by_id === $user->id) {
                return;
            }
            $fail("Only owner can change $attribute of list!");
        }
    }
}
