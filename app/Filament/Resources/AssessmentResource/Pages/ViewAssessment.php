<?php

namespace App\Filament\Resources\AssessmentResource\Pages;

use App\Filament\Resources\AssessmentResource;
use App\Filament\Resources\AssessmentResource\RelationManagers\AssessmentAnswersRelationManager;
use App\Filament\Resources\AssessmentResource\RelationManagers\ForcedChoiceAnswersRelationManager;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewAssessment extends ViewRecord
{
    protected static string $resource = AssessmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make()
                ->visible(fn (): bool => auth()->user()?->hasRole('super_admin') === true),
            Actions\DeleteAction::make()
                ->requiresConfirmation()
                ->modalHeading('Hapus Hasil Assessment?')
                ->modalDescription(fn (): string => "Data hasil assessment {$this->record->assessment_code} milik {$this->record->student?->name} akan dihapus permanen dan tidak dapat dikembalikan.")
                ->modalSubmitActionLabel('Ya, Hapus Sekarang')
                ->successNotificationTitle('Hasil assessment berhasil dihapus.')
                ->failureNotificationTitle('Gagal menghapus hasil assessment.')
                ->visible(fn (): bool => auth()->user()?->hasRole('super_admin') === true),
            Actions\Action::make('downloadPdf')
                ->label('Download PDF')
                ->icon('heroicon-o-document-arrow-down')
                ->color('success')
                ->visible(fn (): bool => $this->record->status === 'completed')
                ->url(fn (): string => route('assessment.pdf', $this->record->assessment_code))
                ->openUrlInNewTab(),
        ];
    }

    public function getRelationManagers(): array
    {
        if (auth()->user()?->hasRole('super_admin')) {
            return [
                AssessmentAnswersRelationManager::class,
                ForcedChoiceAnswersRelationManager::class,
            ];
        }

        return [];
    }
}
