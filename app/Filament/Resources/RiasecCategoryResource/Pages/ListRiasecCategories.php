<?php

namespace App\Filament\Resources\RiasecCategoryResource\Pages;

use App\Filament\Resources\RiasecCategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListRiasecCategories extends ListRecords
{
    protected static string $resource = RiasecCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
