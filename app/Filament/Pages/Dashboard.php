<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Models\User;
use App\Models\Report;
use Filament\Widgets\StatsOverviewWidget\Card;
use Filament\Pages\Dashboard as BaseDashboard;
use App\Filament\Widgets\DashboardStats;
use App\Filament\Widgets\ReportChart;
use App\Filament\Widgets\UsersChart;
use App\Filament\Widgets\LatestReports;
use App\Filament\Widgets\LatestUsers;
use App\Filament\Widgets\ActiveUsers;

class Dashboard extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-home';
    protected static string $view = 'filament.pages.dashboard';
    protected static ?string $title = 'Dashboard';

    public function getHeaderWidgets(): array
    {
        return [
            DashboardStats::class,
            ReportChart::class,
            UsersChart::class,
            LatestReports::class,
            LatestUsers::class,
            ActiveUsers::class,
        ];
    }
}
