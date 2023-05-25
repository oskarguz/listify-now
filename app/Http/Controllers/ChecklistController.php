<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreChecklistRequest;
use App\Http\Requests\UpdateChecklistRequest;
use App\Models\Checklist;
use App\Models\ChecklistItem;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\Response;

class ChecklistController extends Controller
{
    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('checklist.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreChecklistRequest $request)
    {
        $user = \Auth::user();
        $validated = $request->validated();

        $checklist = Checklist::create($validated);

        $items = $validated['items'] ?? [];
        foreach ($items as $item) {
            $itemModel = new ChecklistItem($item);
            $itemModel->createdBy()->associate($user);
            $itemModel->updatedBy()->associate($user);

            $checklist->items()->save($itemModel);
        }

        $checklist->createdBy()->associate($user);
        $checklist->updatedBy()->associate($user);
        $checklist->push();

        $checklist->loadMissing(['items']);

        return $checklist->toArray();
    }

    /**
     * Display the specified resource.
     */
    public function show(Checklist $checklist)
    {
        return view('checklist.show', ['checklist' => $checklist]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateChecklistRequest $request, Checklist $checklist)
    {
        $validated = $request->validated();

        if (empty($validated)) {
            return \response()->json(['status' => 'OK']);
        }

        $checklist->fill($validated);
        $checklist->push();

        return \response()->json(['status' => 'OK']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Checklist $checklist)
    {
        if ($checklist->created_by_id === null) {
            abort(Response::HTTP_FORBIDDEN);
        }

        if (!\Auth::check()) {
            abort(Response::HTTP_FORBIDDEN);
        }

        if (\Auth::id() !== $checklist->created_by_id) {
            abort(Response::HTTP_FORBIDDEN);
        }

        $checklist->delete();

        return \response(['status' => 'OK']);
    }
}
