<?php

namespace App\Filament\Resources\BookingResource\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;

class TaskCompletionsRelationManager extends RelationManager
{
    protected static string $relationship = 'taskCompletions';
    protected static ?string $title = 'Completed Tasks';

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('task_id')
            ->columns([
                TextColumn::make('task.title')
                    ->label('Task')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('task.type')
                    ->label('Type')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'photo_proof' => '📷 Photo Proof',
                        'gps_checkin' => '📍 GPS Check-In',
                        'qr_scan'     => '🔳 QR Scan',
                        'code_entry'  => '🔑 Code Entry',
                        'quiz'        => '📝 Quiz',
                        default       => $state,
                    }),
                TextColumn::make('task.points')
                    ->label('Points')
                    ->suffix(' pts')
                    ->sortable(),
                TextColumn::make('proof_path')
                    ->label('Photo Proof')
                    ->url(fn ($record) => $record->proof_path ? asset('storage/' . $record->proof_path) : null)
                    ->openUrlInNewTab()
                    ->default('—'),
                TextColumn::make('completed_at')
                    ->label('Completed At')
                    ->dateTime('d M Y, H:i')
                    ->sortable(),
            ])
            ->defaultSort('completed_at', 'desc')
            ->paginated(false);
    }
}
