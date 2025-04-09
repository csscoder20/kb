<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use App\Models\Basic;
use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Validation\ValidationException;

class ReportController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'tags' => 'required|array|min:1|max:2',
            'tags.*' => 'exists:tags,id',
            'description' => 'nullable|string',
            'file' => 'required|file|mimes:docx',
        ]);

        $filePath = $request->file('file')->store('reports', 'public');

        $pdfPath = Report::convertDocxToPdf($filePath);

        $report = Report::create([
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'file' => $filePath,
            'pdf_file' => $pdfPath,
            'user_id' => auth()->id(),
        ]);

        $report->tags()->sync($validated['tags']);

        return response()->json([
            'message' => 'Your report has successfully created!',
        ]);
    }
}
