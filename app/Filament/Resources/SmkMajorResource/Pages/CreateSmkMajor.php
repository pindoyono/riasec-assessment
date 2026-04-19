<?php

namespace App\Filament\Resources\SmkMajorResource\Pages;

use App\Filament\Resources\SmkMajorResource;
use Filament\Resources\Pages\CreateRecord;

class CreateSmkMajor extends CreateRecord
{
    protected static string $resource = SmkMajorResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
