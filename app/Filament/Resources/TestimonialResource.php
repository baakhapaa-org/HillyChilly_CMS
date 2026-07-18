<?php
namespace App\Filament\Resources;

use App\Filament\Resources\TestimonialResource\Pages;
use App\Models\Testimonial;
use Filament\Forms\Components\{Grid, Section, Select, TextInput, Textarea, Toggle};
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Columns\{BooleanColumn, TextColumn};
use Filament\Tables\Actions\{EditAction, DeleteAction};
use Filament\Tables\Table;

class TestimonialResource extends Resource
{
    protected static ?string $model = Testimonial::class;
    protected static ?string $navigationIcon  = 'heroicon-o-chat-bubble-left-right';
    protected static ?string $navigationGroup = 'Content';
    protected static ?int    $navigationSort  = 3;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Section::make()->schema([
                Grid::make(2)->schema([
                    TextInput::make('name')->required(),
                    TextInput::make('package_name'),
                ]),
                Grid::make(2)->schema([
                    Select::make('rating')->options([1=>1,2=>2,3=>3,4=>4,5=>5])->default(5)->required(),
                    TextInput::make('sort_order')->numeric()->default(0),
                ]),
                TextInput::make('avatar_url')->url()->columnSpanFull(),
                Textarea::make('content')->rows(4)->required()->columnSpanFull(),
                Toggle::make('is_visible')->default(true)->label('Visible on website'),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('name')->searchable()->weight('bold'),
            TextColumn::make('package_name'),
            TextColumn::make('rating')->badge()->color('warning'),
            TextColumn::make('content')->limit(60),
            BooleanColumn::make('is_visible'),
        ])->defaultSort('sort_order')->actions([EditAction::make(), DeleteAction::make()]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListTestimonials::route('/'),
            'create' => Pages\CreateTestimonial::route('/create'),
            'edit'   => Pages\EditTestimonial::route('/{record}/edit'),
        ];
    }
}
