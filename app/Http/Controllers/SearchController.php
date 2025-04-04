<?php

namespace App\Http\Controllers;

use App\Models\Report;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $keyword = $request->input('keyword');

        $reports = Report::where('title', 'like', "%{$keyword}%")
            ->orWhere('description', 'like', "%{$keyword}%")
            ->get();

        return response()->json($reports->map(function ($report) {
            return [
                'title' => $report->title,
                'description' => $report->description,
                'file' => $report->file,
                'pdf_file' => $report->pdf_file,
            ];
        }));
    }
}
