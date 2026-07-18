<?php
namespace App\Filament\Resources;

use App\Filament\Resources\BlogResource\Pages;
use App\Models\Blog;
use Filament\Forms\Components\{Grid, RichEditor, Section, Select, TextInput, Textarea, Toggle};
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Columns\{BooleanColumn, TextColumn};
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Actions\{EditAction, DeleteAction};
use Filament\Tables\Table;

class BlogResource extends Resource
{
    protected static ?string $model = Blog::class;
    protected static ?string $navigationIcon  = 'heroicon-o-newspaper';
    protected static ?string $navigationGroup = 'Content';
    protected static ?int    $navigationSort  = 1;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Section::make('Post Details')->schema([
                Grid::make(2)->schema([
                    TextInput::make('title')->required()->live(onBlur: true)
                        ->afterStateUpdated(fn($state, $set) => $set('slug', str($state)->slug())),
                    TextInput::make('slug')->required()->unique(ignoreRecord: true),
                ]),
                Grid::make(2)->schema([
                    Select::make('category')->options([
                        'adventure'  => 'Adventure',
                        'trekking'   => 'Trekking',
                        'cultural'   => 'Cultural',
                        'wildlife'   => 'Wildlife',
                        'travel-tip' => 'Travel Tip',
                    ])->required(),
                    TextInput::make('image_url')->url(),
                ]),
                Textarea::make('excerpt')->rows(2),
                RichEditor::make('content')->required()->columnSpanFull(),
            ])->columns(1),
            Section::make('SEO & Publishing')->schema([
                Grid::make(2)->schema([
                    TextInput::make('meta_title'),
                    TextInput::make('meta_description'),
                ]),
                Toggle::make('is_published')->label('Publish'),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')->searchable()->limit(40)->weight('bold'),
                TextColumn::make('category')->badge(),
                TextColumn::make('view_count')->suffix(' views')->sortable(),
                BooleanColumn::make('is_published')->label('Published'),
                TextColumn::make('created_at')->date()->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([TernaryFilter::make('is_published')->label('Published')])
            ->actions([EditAction::make(), DeleteAction::make()]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListBlogs::route('/'),
            'create' => Pages\CreateBlog::route('/create'),
            'edit'   => Pages\EditBlog::route('/{record}/edit'),
        ];
    }
}
