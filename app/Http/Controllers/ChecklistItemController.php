<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreChecklistItemRequest;
use App\Http\Requests\UpdateChecklistItemRequest;
use App\Models\ChecklistItem;
use Symfony\Component\HttpFoundation\Response;

class ChecklistItemController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreChecklistItemRequest $request)
    {
        abort(Response::HTTP_BAD_REQUEST);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateChecklistItemRequest $request, ChecklistItem $checklistItem)
    {
        abort(Response::HTTP_BAD_REQUEST);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ChecklistItem $checklistItem)
    {
        abort(Response::HTTP_NOT_FOUND);
    }
}
