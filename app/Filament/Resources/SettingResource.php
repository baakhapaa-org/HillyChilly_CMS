<?php
namespace App\Filament\Resources;

use App\Filament\Resources\SettingResource\Pages;
use App\Models\Setting;
use Filament\Forms\Components\{Grid, Section, Select, TextInput, Textarea};
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Actions\{EditAction};
use Filament\Tables\Table;

class SettingResource extends Resource
{
    protected static ?string $model = Setting::class;
    protected static ?string $navigationIcon  = 'heroicon-o-cog-6-tooth';
    protected static ?string $navigationGroup = 'System';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Section::make()->schema([
                Grid::make(2)->schema([
                    TextInput::make('key')->required()->unique(ignoreRecord: true)->disabled(fn($record) => $record !== null),
                    Select::make('group')->options([
                        'general'  => 'General',
                        'app'      => 'App',
                        'social'   => 'Social',
                        'contact'  => 'Contact',
                        'seo'      => 'SEO',
                    ])->required(),
                ]),
                TextInput::make('label'),
                Textarea::make('value')->rows(3)->columnSpanFull(),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('key')->searchable()->weight('bold')->fontFamily('mono'),
            TextColumn::make('label'),
            TextColumn::make('group')->badge(),
            TextColumn::make('value')->limit(50),
            TextColumn::make('updated_at')->date()->sortable(),
        ])
        ->filters([SelectFilter::make('group')->options(['general' => 'General', 'app' => 'App', 'social' => 'Social', 'contact' => 'Contact', 'seo' => 'SEO'])])
        ->actions([EditAction::make()]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListSettings::route('/'),
            'create' => Pages\CreateSetting::route('/create'),
            'edit'   => Pages\EditSetting::route('/{record}/edit'),
        ];
    }
}
