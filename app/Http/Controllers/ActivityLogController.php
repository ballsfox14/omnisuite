<?php

namespace App\Http\Controllers;

use Spatie\Activitylog\Models\Activity;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        $activities = Activity::with('causer')
            ->when($request->subject, function ($query, $subject) {
                return $query->where('subject_type', $subject);
            })
            ->latest()
            ->paginate(20);

        return view('logs.index', compact('activities'));
    }
}