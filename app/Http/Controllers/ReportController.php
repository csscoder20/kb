<?php

namespace App\Http\Controllers;

use App\Models\Report;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Models\Customer;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use ZipArchive;
use Log;

class ReportController extends Controller
{
    private function compressDocx($file)
    {
        try {
            $originalSize = $file->getSize();
            // Buat temporary file untuk file yang dikompress
            $tempInputPath = tempnam(sys_get_temp_dir(), 'docx_input_');
            $tempOutputPath = tempnam(sys_get_temp_dir(), 'docx_output_');

            // Copy file yang diupload ke temporary file
            file_put_contents($tempInputPath, file_get_contents($file->getRealPath()));

            // Buat ZIP archive baru dengan level kompresi maksimal
            $zip = new ZipArchive();
            if ($zip->open($tempOutputPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE) {
                // Baca file docx original sebagai ZIP (karena docx adalah ZIP file)
                $originalZip = new ZipArchive();
                if ($originalZip->open($tempInputPath) === TRUE) {
                    // Copy semua file dari docx original ke ZIP baru dengan kompresi maksimal
                    for ($i = 0; $i < $originalZip->numFiles; $i++) {
                        $stat = $originalZip->statIndex($i);
                        $contents = $originalZip->getFromName($stat['name']);
                        $zip->addFromString($stat['name'], $contents);
                    }
                    $originalZip->close();
                }
                $zip->close();
            }

            // Baca hasil kompresi
            $compressedContents = file_get_contents($tempOutputPath);

            // Hapus file temporary
            @unlink($tempInputPath);
            @unlink($tempOutputPath);

            $compressedSize = strlen($compressedContents);
            $compressionRatio = round(($compressedSize / $originalSize) * 100, 2);

            \Log::info("Docx compression results:", [
                'original_size' => $originalSize,
                'compressed_size' => $compressedSize,
                'compression_ratio' => $compressionRatio . '%'
            ]);

            return $compressedContents;
        } catch (\Exception $e) {
            Log::error('Docx compression failed: ' . $e->getMessage());
            return false;
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'file' => 'required|file|mimes:docx|max:10240',
            'tags' => 'required|array',
            'customer' => 'required|string',
            'g-recaptcha-response' => 'required',
        ]);

        // Verifikasi reCAPTCHA
        $recaptchaResponse = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
            'secret' => config('services.recaptcha.secret_key'),
            'response' => $request->input('g-recaptcha-response'),
            'ip' => $request->ip(),
        ]);

        if (!$recaptchaResponse->json()['success']) {
            return response()->json([
                'errors' => ['captcha' => ['reCAPTCHA verification failed']]
            ], 422);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'tags' => 'required|array|min:1|max:2',
            'tags.*' => 'exists:tags,id',
            'description' => 'nullable|string',
            'file' => 'required|file|mimes:docx',
            'customer' => 'required',
        ]);

        $customerInput = $validated['customer'];
        $customer = is_numeric($customerInput)
            ? Customer::find($customerInput)
            : Customer::firstOrCreate(['name' => $customerInput]);

        // Buat nama file berdasarkan title
        $title = $validated['title'];
        $filename = Str::slug($title) . '.' . $request->file('file')->getClientOriginalExtension();

        // Compress file
        $compressedContents = $this->compressDocx($request->file('file'));
        if ($compressedContents === false) {
            // Jika kompresi gagal, gunakan file original
            $filePath = $request->file('file')->storeAs('reports', $filename, 'public');
        } else {
            // Simpan file yang sudah dikompress
            $filePath = 'reports/' . $filename;
            Storage::disk('public')->put($filePath, $compressedContents);
        }

        // Konversi ke PDF
        $pdfPath = Report::convertDocxToPdf($filePath);

        // Simpan data
        $report = Report::create([
            'title' => $title,
            'description' => $validated['description'] ?? null,
            'file' => $filePath,
            'pdf_file' => $pdfPath,
            'user_id' => auth()->id(),
            'customer_id' => $customer->id,
        ]);

        $report->tags()->sync($validated['tags']);

        return response()->json([
            'message' => 'Your report has successfully created!',
        ]);
    }

    public function datatable()
    {
        return DataTables::of(Report::with('tags')->latest())
            ->addColumn('title', fn($report) => $report->title)
            ->addColumn('created_at', fn($report) => $report->created_at->format('Y-m-d'))
            ->addColumn('action', function ($report) {
                $pdfBtn = '<a href="' . asset('storage/' . $report->pdf_file) . '" target="_blank" class="btn btn-danger btn-sm text-light">PDF</a>';
                $wordBtn = $report->file
                    ? '<a href="' . asset('storage/' . $report->file) . '" target="_blank" class="btn btn-success btn-sm text-light">Word</a>'
                    : '';
                return $pdfBtn . ' ' . $wordBtn;
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function getReports(Request $request)
    {
        try {
            $slug = $request->input('slug');

            $query = Report::with(['tags', 'user'])
                ->when($slug, function ($q) use ($slug) {
                    $q->whereHas('tags', function ($query) use ($slug) {
                        $query->where('slug', $slug);
                    });
                })
                ->latest();

            return datatables()->of($query)
                ->addColumn('user_image', function ($report) {
                    return view('components.report-image', compact('report'))->render();
                })
                ->addColumn('info', function ($report) {
                    return view('components.report-info', compact('report'))->render();
                })
                ->addColumn('action', function ($report) {
                    return view('components.report-actions', compact('report'))->render();
                })
                ->rawColumns(['user_image', 'info', 'action'])
                ->toJson();
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to fetch reports',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
