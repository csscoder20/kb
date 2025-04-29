<?php

namespace App\Http\Controllers;

use App\Models\FileAccess;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class FileAccessController extends Controller
{
    public function store(Request $request)
    {
        // Cek dulu apakah user login
        if (!auth()->check()) {
            return response()->json([
                'success' => false,
                'message' => 'User not authenticated.'
            ], 401);
        }

        Log::info('File access request received', [
            'user_id' => auth()->id(),
            'request_data' => $request->all()
        ]);

        try {
            // Validasi data request
            $validated = $request->validate([
                'report_id' => 'required|exists:reports,id',
                'action_type' => 'required|in:preview,download',
            ]);

            // Simpan ke database
            $fileAccess = FileAccess::create([
                'user_id' => auth()->id(),
                'report_id' => $validated['report_id'],
                'action_type' => $validated['action_type'],
            ]);

            Log::info('File access logged successfully', [
                'file_access_id' => $fileAccess->id,
                'data' => $fileAccess->toArray()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Access logged successfully.',
                'data' => $fileAccess
            ]);
        } catch (\Exception $e) {
            Log::error('Error logging file access', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to log access.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
