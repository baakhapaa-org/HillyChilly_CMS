<?php
namespace App\Filament\Widgets;

use App\Models\Booking;
use App\Models\Package;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverviewWidget extends BaseWidget
{
    protected function getStats(): array
    {
        $revenue = Booking::where('status', '!=', 'cancelled')->sum('total_amount_npr');
        return [
            Stat::make('Total Users', User::count())
                ->description('Registered adventurers')
                ->icon('heroicon-o-users')
                ->color('success'),
            Stat::make('Active Packages', Package::where('is_active', true)->count())
                ->description('Quest packages live')
                ->icon('heroicon-o-map')
                ->color('warning'),
            Stat::make('Total Bookings', Booking::count())
                ->description('All-time bookings')
                ->icon('heroicon-o-calendar')
                ->color('info'),
            Stat::make('Revenue', 'NPR ' . number_format($revenue))
                ->description('Total earnings')
                ->icon('heroicon-o-currency-rupee')
                ->color('success'),
        ];
    }
}
