<?php

namespace App\Filament\Resources\StudentResource\Pages;

use App\Filament\Resources\StudentResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewStudent extends ViewRecord
{
    protected static string $resource = StudentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
            Actions\Action::make('activities')
                ->label('Aktivitas')
                ->icon('heroicon-o-clock')
                ->url(fn (): string => StudentResource::getUrl('activities', ['record' => $this->record])),
        ];
    }
}
