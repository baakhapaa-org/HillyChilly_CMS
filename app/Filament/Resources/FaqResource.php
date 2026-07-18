<?php
namespace App\Filament\Resources;

use App\Filament\Resources\FaqResource\Pages;
use App\Models\Faq;
use Filament\Forms\Components\{Grid, Section, Select, TextInput, Textarea, Toggle};
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Columns\{BooleanColumn, TextColumn};
use Filament\Tables\Actions\{EditAction, DeleteAction};
use Filament\Tables\Table;

class FaqResource extends Resource
{
    protected static ?string $model = Faq::class;
    protected static ?string $navigationIcon  = 'heroicon-o-question-mark-circle';
    protected static ?string $navigationGroup = 'Content';
    protected static ?string $label           = 'FAQ';
    protected static ?string $pluralLabel     = 'FAQs';
    protected static ?int    $navigationSort  = 4;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Section::make()->schema([
                Grid::make(2)->schema([
                    Select::make('category')->options([
                        'general'  => 'General',
                        'bookings' => 'Bookings',
                        'payments' => 'Payments',
                        'rewards'  => 'Rewards',
                        'app'      => 'App Usage',
                    ])->required(),
                    TextInput::make('sort_order')->numeric()->default(0),
                ]),
                TextInput::make('question')->required()->columnSpanFull(),
                Textarea::make('answer')->rows(4)->required()->columnSpanFull(),
                Toggle::make('is_visible')->default(true)->label('Show in app & website'),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('question')->searchable()->limit(60)->weight('bold'),
            TextColumn::make('category')->badge(),
            BooleanColumn::make('is_visible')->label('Visible'),
            TextColumn::make('sort_order')->sortable(),
        ])->defaultSort('sort_order')->actions([EditAction::make(), DeleteAction::make()]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListFaqs::route('/'),
            'create' => Pages\CreateFaq::route('/create'),
            'edit'   => Pages\EditFaq::route('/{record}/edit'),
        ];
    }
}
