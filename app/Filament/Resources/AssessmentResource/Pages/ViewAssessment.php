<?php

namespace App\Filament\Resources\AssessmentResource\Pages;

use App\Filament\Resources\AssessmentResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewAssessment extends ViewRecord
{
    protected static string $resource = AssessmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('downloadPdf')
                ->label('Download PDF')
                ->icon('heroicon-o-document-arrow-down')
                ->color('success')
                ->visible(fn (): bool => $this->record->status === 'completed')
                ->url(fn (): string => route('assessment.pdf', $this->record->assessment_code))
                ->openUrlInNewTab(),
        ];
    }
}
