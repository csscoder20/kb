<?php

namespace App\Services;

use App\Models\Report;
use Illuminate\Support\Facades\Storage;

class ReportService
{
    public function createReport(array $data): Report
    {
        try {
            if (isset($data['file']) && $this->isDocxFile($data['file'])) {
                $data['pdf_file'] = $this->convertDocxToPdf($data['file']);
            }

            return Report::create($data);
        } catch (\Exception $e) {
            \Log::error('Report creation failed: ' . $e->getMessage());
            throw $e;
        }
    }

    private function isDocxFile(string $filePath): bool
    {
        return str_ends_with($filePath, '.docx');
    }

    private function convertDocxToPdf(string $docxPath): ?string
    {
        // Implementasi konversi DOCX ke PDF
    }
}
