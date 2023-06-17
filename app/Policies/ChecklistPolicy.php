<?php

namespace App\Policies;

use App\Models\Checklist;
use App\Models\User;

class ChecklistPolicy
{
    public function view(?User $user, Checklist $checklist): bool
    {
        if (!$checklist->created_by_id) {
            return true;
        }
        if ($checklist->is_public()) {
            return true;
        }
        if ($checklist->is_private() && $user?->id === $checklist->created_by_id) {
            return true;
        }

        return false;
    }

    public function create(?User $user): bool
    {
        return true;
    }

    public function update(?User $user, Checklist $checklist): bool
    {
        if (!$checklist->created_by_id) {
            return true;
        }
        if ($checklist->is_public()) {
            return true;
        }
        if ($checklist->is_private() && $user?->id === $checklist->created_by_id) {
            return true;
        }

        return false;
    }

    public function delete(?User $user, Checklist $checklist): bool
    {
        if (!$checklist->created_by_id) {
            return true;
        }

        return $user?->id === $checklist->created_by_id;
    }
}
