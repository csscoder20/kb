<?php

namespace App\Http\Controllers;

use App\Models\Report;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Models\Customer;

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
            'customer' => 'required',
        ]);

        $customerInput = $validated['customer'];
        $customer = is_numeric($customerInput)
            ? Customer::find($customerInput)
            : Customer::firstOrCreate(['name' => $customerInput]);

        // Buat nama file berdasarkan title
        $title = $validated['title'];
        $filename = Str::slug($title) . '.' . $request->file('file')->getClientOriginalExtension();
        $filePath = $request->file('file')->storeAs('reports', $filename, 'public');

        // Konversi ke PDF
        $pdfPath = Report::convertDocxToPdf($filePath);

        // Simpan data
        $report = Report::create([
            'title' => $title,
            'description' => $validated['description'] ?? null,
            'file' => $filePath,
            'pdf_file' => $pdfPath,
            'user_id' => auth()->id(),
            'customer_id' => $customer->id,
        ]);

        $report->tags()->sync($validated['tags']);

        return response()->json([
            'message' => 'Your report has successfully created!',
        ]);
    }

    public function datatable()
    {
        return DataTables::of(Report::with('tags')->latest())
            ->addColumn('title', fn($report) => $report->title)
            ->addColumn('created_at', fn($report) => $report->created_at->format('Y-m-d'))
            ->addColumn('action', function ($report) {
                $pdfBtn = '<a href="' . asset('storage/' . $report->pdf_file) . '" target="_blank" class="btn btn-danger btn-sm text-light">PDF</a>';
                $wordBtn = $report->file
                    ? '<a href="' . asset('storage/' . $report->file) . '" target="_blank" class="btn btn-success btn-sm text-light">Word</a>'
                    : '';
                return $pdfBtn . ' ' . $wordBtn;
            })
            ->rawColumns(['action'])
            ->make(true);
    }
}
