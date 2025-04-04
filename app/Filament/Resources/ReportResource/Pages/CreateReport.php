<?php

namespace App\Filament\Resources\ReportResource\Pages;

use App\Filament\Resources\ReportResource;
use App\Models\Report;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateReport extends CreateRecord
{
    protected static string $resource = ReportResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    // 👉 Konversi PDF sebelum data disimpan
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if (isset($data['file']) && str_ends_with($data['file'], '.docx')) {
            $pdfPath = Report::convertDocxToPdf($data['file']);
            if ($pdfPath) {
                $data['pdf_file'] = $pdfPath;
            }
        }

        return $data;
    }
}
