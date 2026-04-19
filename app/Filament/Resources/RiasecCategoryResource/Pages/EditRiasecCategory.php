<?php

namespace App\Filament\Resources\RiasecCategoryResource\Pages;

use App\Filament\Resources\RiasecCategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditRiasecCategory extends EditRecord
{
    protected static string $resource = RiasecCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
