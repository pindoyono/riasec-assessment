<?php

namespace App\Filament\Resources\RiasecCategoryResource\Pages;

use App\Filament\Resources\RiasecCategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewRiasecCategory extends ViewRecord
{
    protected static string $resource = RiasecCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
            Actions\Action::make('activities')
                ->label('Aktivitas')
                ->icon('heroicon-o-clock')
                ->url(fn (): string => RiasecCategoryResource::getUrl('activities', ['record' => $this->record])),
        ];
    }
}
