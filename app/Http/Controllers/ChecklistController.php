<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreChecklistRequest;
use App\Http\Requests\UpdateChecklistRequest;
use App\Models\Checklist;
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
        abort(500);
    }

    /**
     * Display the specified resource.
     */
    public function show(Checklist $checklist)
    {
        return view('checklist.show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Checklist $checklist)
    {
        return view('checklist.edit');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateChecklistRequest $request, Checklist $checklist)
    {
        abort(Response::HTTP_BAD_REQUEST);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Checklist $checklist)
    {
        abort(Response::HTTP_NOT_FOUND);
    }
}
