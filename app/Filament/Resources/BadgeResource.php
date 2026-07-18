<?php
namespace App\Filament\Resources;

use App\Filament\Resources\BadgeResource\Pages;
use App\Models\Badge;
use Filament\Forms\Components\{Grid, Section, TextInput, Textarea};
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Columns\{TextColumn};
use Filament\Tables\Actions\{EditAction, DeleteAction};
use Filament\Tables\Table;

class BadgeResource extends Resource
{
    protected static ?string $model = Badge::class;
    protected static ?string $navigationIcon  = 'heroicon-o-trophy';
    protected static ?string $navigationGroup = 'Rewards';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Section::make()->schema([
                Grid::make(2)->schema([
                    TextInput::make('name')->required(),
                    TextInput::make('category')->required(),
                ]),
                Grid::make(2)->schema([
                    TextInput::make('icon_url')->url(),
                    TextInput::make('required_points')->numeric()->default(0)->prefix('⭐'),
                ]),
                Textarea::make('description')->rows(3)->columnSpanFull(),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('name')->searchable()->weight('bold'),
            TextColumn::make('category')->badge(),
            TextColumn::make('required_points')->prefix('⭐ ')->sortable(),
            TextColumn::make('users_count')->counts('users')->label('Earned by'),
        ])->actions([EditAction::make(), DeleteAction::make()]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListBadges::route('/'),
            'create' => Pages\CreateBadge::route('/create'),
            'edit'   => Pages\EditBadge::route('/{record}/edit'),
        ];
    }
}
