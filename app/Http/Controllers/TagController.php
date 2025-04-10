<?php

namespace App\Http\Controllers;

use App\Models\Tag;

use Illuminate\Http\Request;

class TagController extends Controller
{
    public function show(Request $request)
    {
        $activeSlug = $request->query('slug');
        $tags = Tag::with(['reports' => function ($q) {
            $q->latest(); // bisa diurut berdasarkan waktu kalau mau
        }])->withCount('reports')->get();

        return view('allposts', compact('tags', 'activeSlug'));
    }

    // public function showAllPosts(Request $request)
    // {
    //     $tags = Tag::withCount('reports')->with(['reports' => function ($query) {
    //         $query->latest();
    //     }])->get();

    //     $slug = $request->query('slug', $tags->first()->slug);

    //     // Ambil tag yang sedang aktif
    //     $activeTag = $tags->firstWhere('slug', $slug);

    //     // Paginate reports khusus tag aktif
    //     $activeReports = $activeTag
    //         ? $activeTag->reports()->latest()->paginate(5, ['*'], "page[$slug]")
    //         : collect();

    //     return view('allposts', compact('tags', 'slug', 'activeReports'));
    // }
}
