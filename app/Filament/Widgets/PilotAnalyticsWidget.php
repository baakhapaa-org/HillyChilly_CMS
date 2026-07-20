<?php

namespace App\Filament\Widgets;

use App\Models\Booking;
use App\Models\BookingTaskCompletion;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

/**
 * Pilot Analytics Widget
 *
 * Surfaces the key Stage-0 metrics from the Palungtar MVP research doc:
 *   • Pilot participants (users with ≥1 booking)
 *   • Quest starts vs completions → completion rate
 *   • Task completions (proxy for check-in success count)
 *   • Average tasks completed per active quest
 *   • New users today (DAU approximation)
 */
class PilotAnalyticsWidget extends BaseWidget
{
    protected static ?int $sort = 1; // Show before the general stats widget

    protected function getStats(): array
    {
        // ── Participants ──────────────────────────────────────────────────────
        $participantCount = User::whereHas('bookings')->count();

        // ── Quest funnel ──────────────────────────────────────────────────────
        $questStarts = Booking::whereIn('status', ['active', 'upcoming', 'completed'])->count();
        $questCompletions = Booking::where('status', 'completed')->count();
        $completionRate = $questStarts > 0
            ? round(($questCompletions / $questStarts) * 100)
            : 0;

        // ── Task completions (= successful check-in events) ───────────────────
        $totalTaskCompletions = BookingTaskCompletion::count();

        $avgTasksPerBooking = $questStarts > 0
            ? round($totalTaskCompletions / $questStarts, 1)
            : 0;

        // ── Daily active users (new bookings today) ───────────────────────────
        $dauToday = User::whereHas('bookings', function ($q) {
            $q->whereDate('created_at', today());
        })->count();

        return [
            Stat::make('Pilot Participants', $participantCount)
                ->description('Users with ≥1 quest booking')
                ->icon('heroicon-o-user-group')
                ->color('success'),

            Stat::make('Quest Completion Rate', "{$completionRate}%")
                ->description("{$questCompletions} completed of {$questStarts} started")
                ->icon('heroicon-o-trophy')
                ->color($completionRate >= 40 ? 'success' : ($completionRate >= 20 ? 'warning' : 'danger')),

            Stat::make('Task Check-Ins', $totalTaskCompletions)
                ->description("Avg {$avgTasksPerBooking} tasks per quest")
                ->icon('heroicon-o-check-badge')
                ->color('info'),

            Stat::make('Active Today (DAU)', $dauToday)
                ->description('Users with a booking created today')
                ->icon('heroicon-o-calendar-days')
                ->color('warning'),
        ];
    }
}
