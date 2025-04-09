<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tag;

class ChatController extends Controller
{
    public function showForm()
    {
        $tags = Tag::all();
        return view('chat', compact('tags'));
    }
}
