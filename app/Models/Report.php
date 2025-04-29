<?php

namespace App\Models;

use App\Observers\ReportObserver;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[ObservedBy([ReportObserver::class])]

class Report extends Model
{
    protected $fillable = ['title', 'description', 'file', 'pdf_file', 'user_id', 'customer_id'];


    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'report_tags');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
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
