<?php
namespace App\Filament\Resources\TestimonialResource\Pages;
use App\Filament\Resources\TestimonialResource;
use Filament\Resources\Pages\ListRecords;
use Filament\Actions\CreateAction;
class ListTestimonials extends ListRecords {
    protected static string $resource = TestimonialResource::class;
    protected function getHeaderActions(): array { return [CreateAction::make()]; }
}
