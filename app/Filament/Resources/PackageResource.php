<?php
namespace App\Filament\Resources;

use App\Filament\Resources\PackageResource\Pages;
use App\Models\Package;
use Filament\Forms\Components\{Grid, Repeater, Section, Select, Textarea, TextInput, Toggle};
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Columns\{IconColumn, ImageColumn, TextColumn};
use Filament\Tables\Filters\{SelectFilter, TernaryFilter};
use Filament\Tables\Actions\{EditAction, DeleteAction, ViewAction, Action};
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

            Section::make('Tasks')
                ->description('Add tasks users must complete during this quest. Config fields vary by type.')
                ->collapsible()
                ->schema([
                    Repeater::make('tasks')
                        ->relationship('tasks')
                        ->orderColumn('sort_order')
                        ->addActionLabel('Add Task')
                        ->collapsed()
                        ->itemLabel(fn (array $state): string => ($state['title'] ?? 'New Task') . ' — ' . ($state['type'] ?? ''))
                        ->schema([
                            Grid::make(3)->schema([
                                Select::make('type')
                                    ->options([
                                        'photo_proof' => '📷 Photo Proof',
                                        'gps_checkin' => '📍 GPS Check-In',
                                        'qr_scan'     => '🔳 QR Scan',
                                        'code_entry'  => '🔑 Code Entry',
                                        'quiz'        => '📝 Quiz',
                                    ])
                                    ->required()
                                    ->live()
                                    ->label('Task Type'),
                                TextInput::make('title')
                                    ->required()
                                    ->maxLength(255)
                                    ->placeholder('e.g. Take a photo at the summit'),
                                TextInput::make('points')
                                    ->numeric()
                                    ->default(10)
                                    ->suffix('pts')
                                    ->minValue(0)
                                    ->required(),
                            ]),

                            // GPS Check-In fields
                            Grid::make(3)->schema([
                                TextInput::make('config.lat')
                                    ->numeric()
                                    ->placeholder('27.7172')
                                    ->label('Latitude'),
                                TextInput::make('config.lng')
                                    ->numeric()
                                    ->placeholder('85.3240')
                                    ->label('Longitude'),
                                TextInput::make('config.radiusMeters')
                                    ->numeric()
                                    ->default(100)
                                    ->suffix('m')
                                    ->label('Radius (metres)'),
                            ])->visible(fn (\Filament\Forms\Get $get): bool => $get('type') === 'gps_checkin'),

                            // QR Scan fields
                            TextInput::make('config.qrCode')
                                ->label('QR Code Value')
                                ->placeholder('The exact string encoded in the QR code')
                                ->visible(fn (\Filament\Forms\Get $get): bool => $get('type') === 'qr_scan'),

                            // Code Entry fields
                            Grid::make(2)->schema([
                                TextInput::make('config.codeHash')
                                    ->label('Secret Code Hash (SHA-256)')
                                    ->placeholder('e.g. sha256("summit2026") — use an online SHA-256 tool')
                                    ->helperText('Enter the SHA-256 hex hash of the secret code participants must type.'),
                                TextInput::make('config.hint')
                                    ->label('Hint shown to users')
                                    ->placeholder('Look near the entrance…'),
                            ])->visible(fn (\Filament\Forms\Get $get): bool => $get('type') === 'code_entry'),

                            // Quiz fields — stored as JSON textarea for flexibility
                            Textarea::make('config.questions')
                                ->label('Quiz Questions (JSON array)')
                                ->placeholder('[{"question":"What colour is the flag?","options":["Red","Blue","Green"],"correctIndex":0}]')
                                ->helperText('Paste a JSON array of {question, options[], correctIndex}.')
                                ->rows(5)
                                ->visible(fn (\Filament\Forms\Get $get): bool => $get('type') === 'quiz'),
                        ])
                        ->columns(1)
                        ->columnSpanFull(),
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
                    ->color(fn (string $state): string => match ($state) {
                        'trekking'  => 'primary',
                        'adventure' => 'success',
                        'wildlife'  => 'warning',
                        'cultural'  => 'info',
                        default     => 'gray',
                    }),
                TextColumn::make('duration_days')->suffix(' days')->sortable(),
                TextColumn::make('price_npr')->money('NPR')->sortable(),
                TextColumn::make('points_reward')->prefix('⭐ ')->sortable(),
                IconColumn::make('is_active')->boolean()->label('Live'),
                IconColumn::make('is_featured')->boolean()->label('Featured'),
                TextColumn::make('bookings_count')->counts('bookings')->label('Bookings'),
            ])
            ->filters([
                SelectFilter::make('category')->options(['trekking' => 'Trekking', 'adventure' => 'Adventure', 'wildlife' => 'Wildlife', 'cultural' => 'Cultural', 'spiritual' => 'Spiritual']),
                TernaryFilter::make('is_active')->label('Active'),
                TernaryFilter::make('is_featured')->label('Featured'),
            ])
            ->actions([
                ViewAction::make(),
                EditAction::make(),
                Action::make('qr_codes')
                    ->label('QR Codes')
                    ->icon('heroicon-o-qr-code')
                    ->color('info')
                    ->url(fn (Package $record): string => static::getUrl('qr-codes', ['record' => $record]))
                    ->visible(fn (Package $record): bool => $record->tasks()->where('type', 'qr_scan')->exists()),
                DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index'     => Pages\ListPackages::route('/'),
            'create'    => Pages\CreatePackage::route('/create'),
            'edit'      => Pages\EditPackage::route('/{record}/edit'),
            'qr-codes'  => Pages\QrCodesPage::route('/{record}/qr-codes'),
        ];
    }
}
