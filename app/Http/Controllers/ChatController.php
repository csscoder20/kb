<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tag;
use App\Models\Basic;

class ChatController extends Controller
{
    public function showForm()
    {
        $tags = Tag::withCount('reports') // hitung jumlah report per tag
            ->with(['reports' => function ($query) {
                $query->latest()->limit(1); // ambil 1 postingan terbaru
            }])
            ->get();

        $basics = Basic::getAllAsArray(); // key => value array

        // return view('chat', compact('tags'));
        return view('chat', compact('tags', 'basics'));
    }

    public function ask()
    {
        $tags = Tag::withCount('reports') // hitung jumlah report per tag
            ->with(['reports' => function ($query) {
                $query->latest()->limit(1); // ambil 1 postingan terbaru
            }])
            ->get();

        $basics = Basic::getAllAsArray(); // key => value array

        // return view('chat', compact('tags'));
        return view('ask', compact('tags', 'basics'));
    }
}
