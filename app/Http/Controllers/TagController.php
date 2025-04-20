<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use App\Models\Basic;
use App\Models\Report;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Routing\Controller;
use App\Models\Customer;

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

        if (!$search || strlen($search) < 2) {
            return response()->json([]);
        }

        // Pecah keyword jadi array kata
        $keywords = explode(' ', $search);

        // Bangun query utama
        $query = Report::query()
            ->with('tags')
            ->latest();

        // Jika ada slug (dan bukan allposts), filter berdasarkan tag
        if ($slug && $slug !== 'allposts') {
            $query->whereHas('tags', function ($q) use ($slug) {
                $q->where('slug', $slug);
            });
        }

        $query = Report::query()
            ->with(['tags', 'customer']) // ← tambahkan relasi customer di sini
            ->latest();

        // Tambahkan kondisi where untuk setiap kata di keyword
        $query->where(function ($q) use ($keywords) {
            foreach ($keywords as $word) {
                $q->where('title', 'like', '%' . $word . '%');
            }
        });

        // Ambil hasil maksimal 10
        $results = $query->limit(10)->get();

        $highlightedResults = $results->map(function ($item) use ($keywords) {
            $highlightedTitle = $item->title;

            foreach ($keywords as $word) {
                $highlightedTitle = preg_replace("/($word)/i", '<strong class="text-primary" >$1</strong>', $highlightedTitle);
            }

            // Tambahkan nama customer jika ada
            if ($item->customer && $item->customer->name) {
                $highlightedTitle .= ' <span class="text-success">- ' . e($item->customer->name) . '</span>';
            }

            $item->highlighted_title = $highlightedTitle;
            return $item;
        });

        return response()->json($highlightedResults);
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

        $customers = Customer::orderBy('name')->get();
        $allReports = Report::latest()->get();
        $basics = Basic::getAllAsArray();

        return view('allposts', compact('tags', 'activeSlug', 'tagData', 'basics', 'allReports', 'customers'));
    }

    public function datatable(Request $request)
    {
        $query = Report::with(['tags', 'user', 'customer'])->latest(); // pastikan relasi user di-load

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
                    return '<img src="' . $url . '" alt="User Image" title="' . e($report->user->name) . '" class="rounded-circle shadow-sm hover-effect" width="40" height="40">';
                }

                $name = $report->user->name ?? 'No Name';

                // Ambil inisial dari setiap kata
                $initial = collect(explode(' ', $name))
                    ->filter()
                    ->map(fn($word) => strtoupper(mb_substr($word, 0, 1)))
                    ->join('');

                // Warna latar dari hash nama
                $hash = md5($name);
                $backgroundColor = '#' . substr($hash, 0, 6);

                // Warna teks berdasarkan luminance
                $r = hexdec(substr($backgroundColor, 1, 2));
                $g = hexdec(substr($backgroundColor, 3, 2));
                $b = hexdec(substr($backgroundColor, 5, 2));
                $luminance = ($r * 0.299 + $g * 0.587 + $b * 0.114);
                $textColor = $luminance > 186 ? '#000000' : '#FFFFFF';

                return '<div title="' . e($name) . '" 
                    class="rounded-circle d-flex justify-content-center align-items-center avatar-initial" 
                    style="width:40px;height:40px;background-color:' . $backgroundColor . ';color:' . $textColor . ';font-weight:bold;">
                    ' . $initial . '
                </div>';
            })


            ->addColumn('info', function ($report) use ($isAll) {
                $customerName = $report->customer->name ?? null;
                $fullTitle = $customerName
                    ? $report->title . ' - <span class="text-success">' . e($customerName) . '</span>'
                    : $report->title;

                $title = '<strong class="fw-normal">' . $fullTitle . '</strong>';

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
