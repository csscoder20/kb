<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use App\Models\Basic;

class ChatController extends Controller
{
    public function showForm()
    {
        $tags = Tag::withCount('reports')
            ->with(['reports' => function ($query) {
                $query->latest()->limit(1);
            }])
            ->get();

        $basics = Basic::getAllAsArray();

        return view('chat', compact('tags', 'basics'));
    }

    public function ask()
    {
        $tags = Tag::withCount('reports')
            ->with(['reports' => function ($query) {
                $query->latest()->limit(1);
            }])
            ->get();

        $basics = Basic::getAllAsArray();

        return view('ask', compact('tags', 'basics'));
    }
}
