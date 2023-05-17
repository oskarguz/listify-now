<?php

namespace App\Policies;

use App\Models\Checklist;
use App\Models\ChecklistItem;
use App\Models\User;

class ChecklistItemPolicy
{
    private ChecklistPolicy $checklistPolicy;

    public function __construct(ChecklistPolicy $checklistPolicy)
    {
        $this->checklistPolicy = $checklistPolicy;
    }

    public function create(?User $user, Checklist $checklist): bool
    {
        return $this->checklistPolicy->update($user, $checklist);
    }

    public function update(?User $user, Checklist $checklist, ChecklistItem $checklistItem): bool
    {
        return $this->checklistPolicy->update($user, $checklist);
    }

    public function delete(?User $user, Checklist $checklist, ChecklistItem $checklistItem): bool
    {
        return true;
    }
}
