<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Tag;
use App\Models\Basic;
use App\Models\Report;
use App\Models\Customer;

class PostController extends Controller
{

    public function newPost()
    {
        $tags = Tag::withCount('reports')
            ->with(['reports' => function ($query) {
                $query->latest()->limit(1);
            }])
            ->get();

        $basics = Basic::getAllAsArray();


        return view('post', compact('tags', 'basics'));
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
}
