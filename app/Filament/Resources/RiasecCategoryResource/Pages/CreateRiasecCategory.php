<?php

namespace App\Filament\Resources\RiasecCategoryResource\Pages;

use App\Filament\Resources\RiasecCategoryResource;
use Filament\Resources\Pages\CreateRecord;

class CreateRiasecCategory extends CreateRecord
{
    protected static string $resource = RiasecCategoryResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
