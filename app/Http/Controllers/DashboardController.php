<?php


namespace App\Http\Controllers;


use App\Models\Checklist;

class DashboardController extends Controller
{
    public function __invoke()
    {
        $checklists = [];
        if (\Auth::check()) {
            $checklists = Checklist::where('created_by_id', '=', \Auth::id())
                ->orderByDesc('created_at')
                ->get();
        }

        return view('dashboard.index', [
            'checklists' => $checklists
        ]);
    }
}
