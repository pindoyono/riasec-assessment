<?php

namespace App\Filament\Resources\SmkMajorResource\Pages;

use App\Filament\Resources\SmkMajorResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewSmkMajor extends ViewRecord
{
    protected static string $resource = SmkMajorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
            Actions\Action::make('activities')
                ->label('Aktivitas')
                ->icon('heroicon-o-clock')
                ->url(fn (): string => SmkMajorResource::getUrl('activities', ['record' => $this->record])),
        ];
    }
}
