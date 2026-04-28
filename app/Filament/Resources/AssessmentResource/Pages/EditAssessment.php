<?php

namespace App\Filament\Resources\AssessmentResource\Pages;

use App\Filament\Resources\AssessmentResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAssessment extends EditRecord
{
    protected static string $resource = AssessmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->requiresConfirmation()
                ->modalHeading('Hapus Hasil Assessment?')
                ->modalDescription(fn (): string => "Data hasil assessment {$this->record->assessment_code} milik {$this->record->student?->name} akan dihapus permanen dan tidak dapat dikembalikan.")
                ->modalSubmitActionLabel('Ya, Hapus Sekarang')
                ->successNotificationTitle('Hasil assessment berhasil dihapus.')
                ->failureNotificationTitle('Gagal menghapus hasil assessment.')
                ->visible(fn (): bool => auth()->user()?->hasRole('super_admin') === true),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
