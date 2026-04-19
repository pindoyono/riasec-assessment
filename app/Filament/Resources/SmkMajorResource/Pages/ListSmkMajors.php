<?php

namespace App\Filament\Resources\SmkMajorResource\Pages;

use App\Filament\Resources\SmkMajorResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSmkMajors extends ListRecords
{
    protected static string $resource = SmkMajorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
