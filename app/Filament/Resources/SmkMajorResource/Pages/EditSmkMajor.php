<?php

namespace App\Filament\Resources\SmkMajorResource\Pages;

use App\Filament\Resources\SmkMajorResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSmkMajor extends EditRecord
{
    protected static string $resource = SmkMajorResource::class;

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
