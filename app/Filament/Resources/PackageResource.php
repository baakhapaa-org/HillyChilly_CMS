<?php
namespace App\Filament\Resources;

use App\Filament\Resources\PackageResource\Pages;
use App\Models\Package;
use Filament\Forms\Components\{FileUpload, Grid, RichEditor, Section, Select, TextInput, Textarea, Toggle};
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Columns\{BadgeColumn, BooleanColumn, ImageColumn, TextColumn};
use Filament\Tables\Filters\{SelectFilter, TernaryFilter};
use Filament\Tables\Actions\{EditAction, DeleteAction, ViewAction};
use Filament\Tables\Table;

class PackageResource extends Resource
{
    protected static ?string $model = Package::class;
    protected static ?string $navigationIcon  = 'heroicon-o-map';
    protected static ?string $navigationGroup = 'Catalogue';
    protected static ?string $label           = 'Quest Package';
    protected static ?string $pluralLabel     = 'Quest Packages';
    protected static ?int    $navigationSort  = 1;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Section::make('Basic Info')->schema([
                Grid::make(2)->schema([
                    TextInput::make('title')->required()->maxLength(255)->live(onBlur: true)
                        ->afterStateUpdated(fn($state, $set) => $set('slug', str($state)->slug())),
                    TextInput::make('slug')->required()->unique(ignoreRecord: true),
                ]),
                Grid::make(3)->schema([
                    Select::make('category')->options([
                        'trekking'  => 'Trekking',
                        'adventure' => 'Adventure',
                        'wildlife'  => 'Wildlife',
                        'cultural'  => 'Cultural',
                        'spiritual' => 'Spiritual',
                        'lakeside'  => 'Lakeside',
                    ])->required(),
                    TextInput::make('duration_days')->numeric()->required()->suffix('days'),
                    TextInput::make('price_npr')->numeric()->required()->prefix('NPR'),
                ]),
                Grid::make(2)->schema([
                    TextInput::make('points_reward')->numeric()->default(0)->prefix('⭐'),
                    TextInput::make('image_url')->url()->placeholder('https://...'),
                ]),
                Textarea::make('description')->rows(4)->columnSpanFull(),
            ])->columns(1),

            Section::make('Location')->schema([
                Grid::make(3)->schema([
                    TextInput::make('location_label')->placeholder('Pokhara, Nepal'),
                    TextInput::make('location_lat')->numeric()->placeholder('28.2096'),
                    TextInput::make('location_lng')->numeric()->placeholder('83.9856'),
                ]),
            ]),

            Section::make('Status')->schema([
                Grid::make(2)->schema([
                    Toggle::make('is_active')->default(true)->label('Active / Live'),
                    Toggle::make('is_featured')->label('Featured on Homepage'),
                ]),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image_url')->circular()->label(''),
                TextColumn::make('title')->searchable()->sortable()->weight('bold'),
                TextColumn::make('category')->badge()
                    ->colors(['primary' => 'trekking', 'success' => 'adventure', 'warning' => 'wildlife', 'info' => 'cultural']),
                TextColumn::make('duration_days')->suffix(' days')->sortable(),
                TextColumn::make('price_npr')->money('NPR')->sortable(),
                TextColumn::make('points_reward')->prefix('⭐ ')->sortable(),
                BooleanColumn::make('is_active')->label('Live'),
                BooleanColumn::make('is_featured')->label('Featured'),
                TextColumn::make('bookings_count')->counts('bookings')->label('Bookings'),
            ])
            ->filters([
                SelectFilter::make('category')->options(['trekking' => 'Trekking', 'adventure' => 'Adventure', 'wildlife' => 'Wildlife', 'cultural' => 'Cultural', 'spiritual' => 'Spiritual']),
                TernaryFilter::make('is_active')->label('Active'),
                TernaryFilter::make('is_featured')->label('Featured'),
            ])
            ->actions([ViewAction::make(), EditAction::make(), DeleteAction::make()]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListPackages::route('/'),
            'create' => Pages\CreatePackage::route('/create'),
            'edit'   => Pages\EditPackage::route('/{record}/edit'),
        ];
    }
}
