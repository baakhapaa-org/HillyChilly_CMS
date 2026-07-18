<?php
namespace App\Filament\Resources;

use App\Filament\Resources\BookingResource\Pages;
use App\Models\Booking;
use Filament\Forms\Components\{DatePicker, Grid, Section, Select, TextInput};
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Columns\{BadgeColumn, TextColumn};
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Actions\{EditAction, ViewAction};
use Filament\Tables\Table;

class BookingResource extends Resource
{
    protected static ?string $model = Booking::class;
    protected static ?string $navigationIcon  = 'heroicon-o-calendar-days';
    protected static ?string $navigationGroup = 'Commerce';
    protected static ?int    $navigationSort  = 1;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Section::make()->schema([
                Grid::make(2)->schema([
                    Select::make('user_id')->relationship('user', 'name')->searchable()->preload()->required(),
                    Select::make('package_id')->relationship('package', 'title')->searchable()->preload()->required(),
                ]),
                Grid::make(3)->schema([
                    DatePicker::make('start_date')->required(),
                    TextInput::make('participants')->numeric()->default(1)->min(1)->max(20),
                    TextInput::make('total_amount_npr')->numeric()->prefix('NPR')->required(),
                ]),
                Grid::make(2)->schema([
                    Select::make('status')->options([
                        'pending'   => 'Pending',
                        'confirmed' => 'Confirmed',
                        'cancelled' => 'Cancelled',
                        'completed' => 'Completed',
                    ])->required(),
                    Select::make('payment_method')->options([
                        'esewa'  => 'eSewa',
                        'khalti' => 'Khalti',
                        'bank'   => 'Bank Transfer',
                        'cash'   => 'Cash',
                    ]),
                ]),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->label('#')->sortable(),
                TextColumn::make('user.name')->searchable()->label('User'),
                TextColumn::make('package.title')->searchable()->label('Package')->limit(30),
                TextColumn::make('start_date')->date()->sortable(),
                TextColumn::make('participants')->suffix(' pax'),
                TextColumn::make('total_amount_npr')->money('NPR')->sortable(),
                TextColumn::make('payment_method')->badge(),
                TextColumn::make('status')->badge()
                    ->colors([
                        'warning' => 'pending',
                        'success' => 'confirmed',
                        'danger'  => 'cancelled',
                        'info'    => 'completed',
                    ]),
                TextColumn::make('created_at')->date()->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('status')->options(['pending' => 'Pending', 'confirmed' => 'Confirmed', 'cancelled' => 'Cancelled', 'completed' => 'Completed']),
                SelectFilter::make('payment_method')->options(['esewa' => 'eSewa', 'khalti' => 'Khalti', 'bank' => 'Bank', 'cash' => 'Cash']),
            ])
            ->actions([ViewAction::make(), EditAction::make()]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListBookings::route('/'),
            'create' => Pages\CreateBooking::route('/create'),
            'edit'   => Pages\EditBooking::route('/{record}/edit'),
        ];
    }
}
