<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use App\Models\Basic;
use App\Models\Report;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Routing\Controller;

// class TagController extends \App\Http\Controllers\Controller
class TagController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth')->only(['viewPdf', 'downloadWord']);
    }

    public function search(Request $request)
    {
        $search = $request->input('search');
        $slug = $request->input('slug');

        // Jika tidak ada keyword pencarian atau panjangnya kurang dari 2 karakter, return empty array
        if (!$search || strlen($search) < 2) {
            return response()->json([]);
        }

        // Memulai query pencarian, hanya pada kolom 'title' yang ada di tabel
        $query = Report::query()
            ->where('title', 'like', '%' . $search . '%')
            ->with('tags') // Mengambil relasi tags
            ->limit(10)
            ->latest();

        // Jika slug ada dan bukan 'allposts', tambahkan filter berdasarkan slug tag
        if ($slug && $slug !== 'allposts') {
            $query->whereHas('tags', function ($q) use ($slug) {
                $q->where('slug', $slug); // Filter berdasarkan tag aktif
            });
        }

        // Ambil hasil pencarian
        $results = $query->get();

        // Mengembalikan hasil pencarian dalam bentuk JSON
        return response()->json($results);
    }


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
        $query = Report::with(['tags', 'user'])->latest(); // pastikan relasi user di-load

        if ($request->filled('slug')) {
            $query->whereHas('tags', function ($q) use ($request) {
                $q->where('slug', $request->slug);
            });
        }

        $isAll = !$request->filled('slug');

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

                $tags = $report->tags->map(function ($tag) {
                    return '<span style="background-color:' . e($tag->color) . '" class="badge me-0 rounded-0" title="' . e($tag->name) . '">' . e($tag->alias) . '</span>';
                })->implode(' ');

                $date = '<div class="text-muted small"><i class="bi bi-calendar me-1"></i>' . $report->created_at->format('Y-m-d') . '</div>';

                $viewDownload = '
                    <div class="mt-0 small text-muted">
                        <i class="bi bi-eye me-1"></i>' . $report->view . '
                        &nbsp;&nbsp;
                        <i class="bi bi-download me-1"></i>' . $report->download . '
                    </div>';

                $uploadedBy = '<div class="small text-muted"><i class="bi bi-person-circle"></i> ' . e($report->user->name ?? 'Unknown') . '</div>';

                return '
                    <div class="mb-1">
                        <div class="d-flex align-items-center gap-2">' . $title . '<div class="tagsDiv d-flex">' . $tags . '</div></div>
                        <div class="d-flex flex-wrap gap-3 mt-2">' . $date . $viewDownload . $uploadedBy . '</div>
                    </div>
                ';
            })

            ->addColumn('action', function ($report) {
                if (!auth()->check()) {
                    return '<span class="text-muted small">-</span>';
                }

                $pdfBtn = '<a href="' . route('report.view.pdf', $report->id) . '" target="_blank" title="Preview File PDF" class="text-danger text-decoration-none"><i class="bi bi-file-earmark-pdf fs-5"></i></a> |';

                $wordBtn = $report->file
                    ? '<a href="' . route('report.download.word', $report->id) . '" title="Download File Word" target="_blank" class="text-success text-decoration-none"><i class="bi bi-file-earmark-word fs-5"></i></a>'
                    : '';

                return $pdfBtn . ' ' . $wordBtn;
            })

            ->rawColumns(['user_image', 'info', 'action'])
            ->make(true);
    }

    public function viewPdf($id)
    {
        if (!auth()->check()) {
            return redirect('/admin/login');
        }

        $report = Report::findOrFail($id);
        $report->increment('view');
        return redirect(asset('storage/' . $report->pdf_file));
    }

    public function downloadWord($id)
    {
        $report = Report::findOrFail($id);
        $report->increment('download'); // tambah download 1
        return redirect(asset('storage/' . $report->file));
    }
}
