<?php
namespace App\Filament\Resources\BadgeResource\Pages;
use App\Filament\Resources\BadgeResource;
use Filament\Resources\Pages\ListRecords;
use Filament\Actions\CreateAction;
class ListBadges extends ListRecords {
    protected static string $resource = BadgeResource::class;
    protected function getHeaderActions(): array { return [CreateAction::make()]; }
}
