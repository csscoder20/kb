<?php

namespace App\Services;

use App\Models\Report;
use Illuminate\Database\Eloquent\Collection;

class SearchService
{
    public function searchReports(string $keyword): array
    {
        if (strlen($keyword) < 3 || str_word_count($keyword) < 1) {
            throw new \InvalidArgumentException('Search keyword too short');
        }

        $keywords = explode(' ', $keyword);

        $reports = Report::with('tags')
            ->where($this->buildSearchQuery($keywords))
            ->get();

        return $this->formatSearchResults($reports, $keywords);
    }

    protected function buildSearchQuery(array $keywords): callable
    {
        return function ($query) use ($keywords) {
            foreach ($keywords as $word) {
                $query->where(function ($q) use ($word) {
                    $q->where('title', 'like', "%{$word}%")
                        ->orWhere('description', 'like', "%{$word}%");
                });
            }
        };
    }

    protected function formatSearchResults(Collection $reports, array $keywords): array
    {
        return $reports->map(function ($report) use ($keywords) {
            return [
                'title' => $this->highlightKeywords($report->title, $keywords),
                'description' => $this->highlightKeywords($report->description, $keywords),
                'file' => $report->file,
                'pdf_file' => $report->pdf_file,
                'tags' => $report->tags->map(fn($tag) => [
                    'name' => $tag->name,
                    'color' => $tag->color,
                ]),
            ];
        })->toArray();
    }

    protected function highlightKeywords(string $text, array $keywords): string
    {
        foreach ($keywords as $word) {
            $escapedWord = preg_quote($word, '/');
            $text = preg_replace("/($escapedWord)/i", '<mark>$1</mark>', $text);
        }
        return $text;
    }
}
