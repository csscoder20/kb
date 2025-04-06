<?php

namespace App\Http\Controllers;

use App\Models\Report;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $keyword = $request->input('keyword');

        // Include relasi tags
        $reports = Report::with('tags')
            ->where('title', 'like', "%{$keyword}%")
            ->orWhere('description', 'like', "%{$keyword}%")
            ->get();

        return response()->json($reports->map(function ($report) {
            return [
                'title' => $report->title,
                'description' => $report->description,
                'file' => $report->file,
                'pdf_file' => $report->pdf_file,
                'tags' => $report->tags->map(function ($tag) {
                    return [
                        'name' => $tag->name,
                        'color' => $tag->color,
                    ];
                }),
            ];
        }));
    }
}
