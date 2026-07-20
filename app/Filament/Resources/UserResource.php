<?php
namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms\Components\{Grid, Section, TextInput, Toggle};
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Columns\{IconColumn, TextColumn};
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Actions\{EditAction, DeleteAction};
use Filament\Tables\Table;
use Illuminate\Support\Facades\Hash;

class UserResource extends Resource
{
    protected static ?string $model = User::class;
    protected static ?string $navigationIcon  = 'heroicon-o-users';
    protected static ?string $navigationGroup = 'Commerce';
    protected static ?int    $navigationSort  = 2;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Section::make()->schema([
                Grid::make(2)->schema([
                    TextInput::make('name')->required()->maxLength(255),
                    TextInput::make('email')->email()->required()->unique(ignoreRecord: true),
                ]),
                Grid::make(2)->schema([
                    TextInput::make('password')->password()->revealable()
                        ->dehydrateStateUsing(fn($s) => $s ? Hash::make($s) : null)
                        ->dehydrated(fn($s) => filled($s))
                        ->required(fn($livewire) => $livewire instanceof \Filament\Resources\Pages\CreateRecord),
                    TextInput::make('points_balance')->numeric()->default(0)->prefix('⭐'),
                ]),
                Grid::make(2)->schema([
                    TextInput::make('avatar_url')->url(),
                    Toggle::make('is_admin')->label('Admin Access'),
                ]),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->searchable()->sortable()->weight('bold'),
                TextColumn::make('email')->searchable(),
                TextColumn::make('points_balance')->prefix('⭐ ')->sortable(),
                TextColumn::make('bookings_count')->counts('bookings')->label('Bookings'),
                IconColumn::make('is_admin')->boolean()->label('Admin'),
                TextColumn::make('created_at')->date()->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([TernaryFilter::make('is_admin')->label('Admin')])
            ->actions([EditAction::make(), DeleteAction::make()]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit'   => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
