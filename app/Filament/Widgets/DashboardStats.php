<?php

namespace App\Filament\Widgets;

use App\Models\Tag;
use App\Models\User;
use App\Models\Report;
use Filament\Widgets\StatsOverviewWidget\Card;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class DashboardStats extends BaseWidget
{
    protected function getCards(): array
    {
        return [
            Card::make('Total Users', User::count())
                ->description('Jumlah seluruh pengguna')
                ->icon('heroicon-o-users'),

            Card::make('Total Reports', Report::count())
                ->description('Jumlah seluruh laporan')
                ->icon('heroicon-o-document-text'),

            Card::make('Active Users', User::role(['super_admin', 'guest', 'admin'])
                ->whereIn('id', function ($query) {
                    $query->select('user_id')
                        ->from('sessions')
                        ->whereNotNull('user_id');
                })
                ->count())
                ->description('User yang aktif saat ini')
                ->icon('heroicon-o-users'),
            Card::make('Tags', Tag::count())
                ->description('Tags yang aktif')
                ->icon('heroicon-o-rectangle-stack'),
        ];
    }
}
