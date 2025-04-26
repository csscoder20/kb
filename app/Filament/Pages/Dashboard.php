<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Filament\Widgets\DashboardStats;
use App\Filament\Widgets\ReportChart;
use App\Filament\Widgets\UsersChart;
use App\Filament\Widgets\LatestReports;
use App\Filament\Widgets\LatestUsers;
use App\Filament\Widgets\ActiveUsers;

class Dashboard extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';
    protected static string $view = 'filament.pages.dashboard';
    protected static ?string $title = 'Dashboard';

    public function mount()
    {
        abort_unless(auth()->user()?->hasRole('super_admin'), 204);
    }

    // Jika si Mas bukan super_admin, di panel si Mas, menu ini gak tampil
    public static function canViewAny(): bool
    {
        return auth()->check() && auth()->user()->hasRole('super_admin');
    }

    public static function shouldRegisterNavigation(): bool
    {
        return auth()->check() && auth()->user()->hasRole('super_admin');
    }

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
