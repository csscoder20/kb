<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use App\Models\Basic;
use App\Models\Report;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class TagController extends Controller
{

    public function show(Request $request)
    {
        $activeSlug = $request->query('slug');

        $tags = Tag::with(['reports' => function ($q) {
            $q->latest();
        }])->withCount('reports')->get();

        $tagData = null;
        if ($activeSlug) {
            $tagData = Tag::where('slug', $activeSlug)->first();
        }

        $allReports = Report::latest()->get();

        $basics = Basic::getAllAsArray();

        return view('allposts', compact('tags', 'activeSlug', 'tagData', 'basics', 'allReports'));
    }

    public function datatable(Request $request)
    {
        $query = Report::with('tags')->latest();

        if ($request->has('slug') && $request->slug !== 'allposts') {
            $query->whereHas('tags', function ($q) use ($request) {
                $q->where('slug', $request->slug);
            });
        }

        $isAll = !$request->has('slug') || $request->slug === 'allposts';

        return DataTables::of($query)
            ->addColumn('user_image', function ($report) {
                if ($report->user && $report->user->profile_picture) {
                    $url = asset('storage/' . $report->user->profile_picture);
                    return '<img src="' . $url . '" alt="User Image" title="' . e($report->user->name) . '" class="rounded-circle" width="40" height="40">';
                }
                return '<img src="' . asset('storage/profile-pictures/default-avatar.png') . '" alt="Default Image" title="No Name" class="rounded-circle" width="40" height="40">';
            })
            ->addColumn('info', function ($report) use ($isAll) {
                $title = '<strong class="fw-normal">' . e($report->title) . '</strong>';
                $date = '<div class="text-muted small">' . $report->created_at->format('Y-m-d') . '</div>';

                // Tampilkan tag hanya jika sedang di tab "All"
                $tags = '';
                if ($isAll) {
                    $tags = $report->tags->map(function ($tag) {
                        return '<span style="background-color:' . e($tag->color) . '" class="badge me-1">' . e($tag->name) . '</span>';
                    })->implode(' ');
                }

                return $title . $date . ($tags ? '<div class="mt-1">' . $tags . '</div>' : '');
            })
            ->addColumn('action', function ($report) {
                $pdfBtn = '<a href="' . asset('storage/' . $report->pdf_file) . '" target="_blank" title="Preview File PDF" class="text-danger text-decoration-none"><i class="bi bi-file-earmark-pdf fs-5"></i></a> |';
                $wordBtn = $report->file
                    ? '<a href="' . asset('storage/' . $report->file) . '" title="Download File Word" target="_blank" class="text-success text-decoration-none"><i class="bi bi-file-earmark-word fs-5"></i></a>'
                    : '';
                return $pdfBtn . ' ' . $wordBtn;
            })
            ->rawColumns(['user_image', 'info', 'action'])
            ->make(true);
    }
}
