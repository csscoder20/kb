<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'report_id' => 'required|exists:reports,id',
                'action' => 'required|in:view_pdf,download_word',
            ]);

            $log = ActivityLog::create([
                'user_id' => auth()->id(),
                'report_id' => $validated['report_id'],
                'action' => $validated['action'],
                'user_ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Activity logged successfully',
                'data' => $log
            ]);
        } catch (\Exception $e) {
            \Log::error('Activity Log Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to log activity'
            ], 500);
        }
    }
}
