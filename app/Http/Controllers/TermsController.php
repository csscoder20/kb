<?php

namespace App\Http\Controllers;

use App\Models\TermsAndConditions;
use Illuminate\Http\JsonResponse;

class TermsController extends Controller
{
    public function getTerms(string $type): JsonResponse
    {
        $terms = TermsAndConditions::where('type', $type)->first();
        return response()->json($terms);
    }

    public function getInfoContent($section = null)
    {
        if (!$section) {
            return response()->json(['error' => 'Section is required'], 400);
        }

        $terms = TermsAndConditions::where('type', $section)->first();

        if (!$terms) {
            return response()->json(['error' => 'Info content not found'], 404);
        }

        return response()->json($terms);
    }
}
