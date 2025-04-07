<?php

namespace App\Http\Controllers;

use App\Models\Report;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    // public function search(Request $request)
    // {
    //     $keyword = $request->input('keyword');

    //     $reports = Report::with('tags')
    //         ->where('title', 'like', "%{$keyword}%")
    //         ->orWhere('description', 'like', "%{$keyword}%")
    //         ->get();

    //     return response()->json($reports->map(function ($report) {
    //         return [
    //             'title' => $report->title,
    //             'description' => $report->description,
    //             'file' => $report->file,
    //             'pdf_file' => $report->pdf_file,
    //             'tags' => $report->tags->map(function ($tag) {
    //                 return [
    //                     'name' => $tag->name,
    //                     'color' => $tag->color,
    //                 ];
    //             }),
    //         ];
    //     }));
    // }


    // public function search(Request $request)
    // {
    //     $keyword = $request->input('keyword');

    //     // Pecah keyword menjadi array kata
    //     $keywords = explode(' ', $keyword);

    //     $reports = Report::with('tags')
    //         ->where(function ($query) use ($keywords) {
    //             foreach ($keywords as $word) {
    //                 $query->where(function ($q) use ($word) {
    //                     $q->where('title', 'like', "%{$word}%")
    //                         ->orWhere('description', 'like', "%{$word}%");
    //                 });
    //             }
    //         })
    //         ->get();

    //     return response()->json($reports->map(function ($report) {
    //         return [
    //             'title' => $report->title,
    //             'description' => $report->description,
    //             'file' => $report->file,
    //             'pdf_file' => $report->pdf_file,
    //             'tags' => $report->tags->map(function ($tag) {
    //                 return [
    //                     'name' => $tag->name,
    //                     'color' => $tag->color,
    //                 ];
    //             }),
    //         ];
    //     }));
    // }

    public function search(Request $request)
    {
        $keyword = $request->input('keyword');
        $keywords = explode(' ', $keyword);

        $reports = Report::with('tags')
            ->where(function ($query) use ($keywords) {
                foreach ($keywords as $word) {
                    $query->where(function ($q) use ($word) {
                        $q->where('title', 'like', "%{$word}%")
                            ->orWhere('description', 'like', "%{$word}%");
                    });
                }
            })
            ->get();

        return response()->json($reports->map(function ($report) use ($keywords) {
            // Fungsi untuk highlight keyword
            $highlight = function ($text) use ($keywords) {
                foreach ($keywords as $word) {
                    $escapedWord = preg_quote($word, '/'); // biar aman untuk regex
                    $text = preg_replace("/($escapedWord)/i", '<mark>$1</mark>', $text);
                }
                return $text;
            };

            return [
                'title' => $highlight($report->title),
                'description' => $highlight($report->description),
                'file' => $report->file,
                'pdf_file' => $report->pdf_file,
                'tags' => $report->tags->map(function ($tag) {
                    return [
                        'name' => $tag->name,
                        'color' => $tag->color,
                    ];
                }),
            ];
        }));
    }

    public function suggest(Request $request)
    {
        $keyword = $request->input('keyword');

        $suggestions = Report::where('title', 'like', "%{$keyword}%")
            ->limit(5)
            ->pluck('title'); // hanya ambil kolom 'title'

        return response()->json($suggestions);
    }
}
