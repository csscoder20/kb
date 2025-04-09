<?php

namespace App\Filament\Widgets;

use App\Models\Report;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class ReportChart extends ChartWidget
{
    protected static ?string $heading = 'Report Masuk';
    protected static ?int $sort = 2;

    protected function getType(): string
    {
        return 'line';
    }

    protected function getData(): array
    {
        $period = request()->get('period', 'daily'); // kamu bisa ubah ini dari dropdown/filter

        $query = Report::query();

        // ambil data sesuai periode
        switch ($period) {
            case 'weekly':
                $data = $query
                    ->select(DB::raw("DATE_FORMAT(created_at, '%x-%v') as label"), DB::raw('COUNT(*) as total'))
                    ->groupBy('label')
                    ->orderBy('label')
                    ->take(8)
                    ->get();
                break;

            case 'monthly':
                $data = $query
                    ->select(DB::raw("DATE_FORMAT(created_at, '%Y-%m') as label"), DB::raw('COUNT(*) as total'))
                    ->groupBy('label')
                    ->orderBy('label')
                    ->take(12)
                    ->get();
                break;

            default: // daily
                $data = $query
                    ->select(DB::raw("DATE(created_at) as label"), DB::raw('COUNT(*) as total'))
                    ->groupBy('label')
                    ->orderBy('label')
                    ->take(14)
                    ->get();
                break;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Laporan',
                    'data' => $data->pluck('total'),
                    'borderColor' => '#3b82f6',
                    'backgroundColor' => 'rgba(59, 130, 246, 0.2)',
                ],
            ],
            'labels' => $data->pluck('label'),
        ];
    }
}
