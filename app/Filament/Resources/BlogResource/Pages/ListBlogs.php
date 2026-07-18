<?php
namespace App\Filament\Resources\BlogResource\Pages;
use App\Filament\Resources\BlogResource;
use Filament\Resources\Pages\ListRecords;
use Filament\Actions\CreateAction;
class ListBlogs extends ListRecords {
    protected static string $resource = BlogResource::class;
    protected function getHeaderActions(): array { return [CreateAction::make()]; }
}
