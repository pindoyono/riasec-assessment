<?php

namespace App\Filament\Resources\ForcedChoiceQuestionResource\Pages;

use App\Filament\Resources\ForcedChoiceQuestionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListForcedChoiceQuestions extends ListRecords
{
    protected static string $resource = ForcedChoiceQuestionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
