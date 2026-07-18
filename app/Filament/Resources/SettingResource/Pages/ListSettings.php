<?php
namespace App\Filament\Resources\SettingResource\Pages;
use App\Filament\Resources\SettingResource;
use Filament\Resources\Pages\ListRecords;
use Filament\Actions\CreateAction;
class ListSettings extends ListRecords {
    protected static string $resource = SettingResource::class;
    protected function getHeaderActions(): array { return [CreateAction::make()]; }
}
