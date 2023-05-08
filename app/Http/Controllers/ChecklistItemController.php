<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreChecklistItemRequest;
use App\Http\Requests\UpdateChecklistItemRequest;
use App\Models\Checklist;
use App\Models\ChecklistItem;

class ChecklistItemController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreChecklistItemRequest $request, Checklist $checklist)
    {
        $validated = $request->validated();

        $item = new ChecklistItem($validated);
        $checklist->items()->save($item);

        return $item->toArray();
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateChecklistItemRequest $request, Checklist $checklist, ChecklistItem $checklistItem)
    {
        $validated = $request->validated();

        if (empty($validated)) {
            return \response()->json(['status' => 'OK']);
        }

        $checklistItem->fill($validated);
        $checklistItem->push();

        return \response()->json(['status' => 'OK']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Checklist $checklist, ChecklistItem $checklistItem)
    {
        $checklistItem->delete();
        return \response()->json(['status' => 'OK']);
    }
}
