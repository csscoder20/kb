<?php

namespace App\Models;

use Log;
use Barryvdh\DomPDF\Facade\Pdf;
use PhpOffice\PhpWord\IOFactory;
use App\Observers\ReportObserver;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

#[ObservedBy([ReportObserver::class])]

class Report extends Model
{
    protected $fillable = ['title', 'description', 'file', 'status', 'pdf_file'];


    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'report_tags');
    }


    public static function convertDocxToPdf($docxPath)
    {
        try {
            $fullPath = storage_path("app/public/{$docxPath}");
            $outputDir = storage_path("app/public/reports");
            $pdfFilename = pathinfo($docxPath, PATHINFO_FILENAME) . '.pdf';
            $outputPdfPath = $outputDir . '/' . $pdfFilename;

            if (!file_exists($outputDir)) {
                mkdir($outputDir, 0777, true);
            }

            $libreOfficePath = '"C:\\Program Files\\LibreOffice\\program\\soffice.exe"';
            $command = "{$libreOfficePath} --headless --convert-to pdf --outdir \"{$outputDir}\" \"{$fullPath}\"";

            $output = shell_exec($command . " 2>&1");

            if (!file_exists($outputPdfPath)) {
                return null;
            }

            return "reports/{$pdfFilename}";
        } catch (\Exception $e) {
            return null;
        }
    }
}
