<?php

namespace App\Filament\Resources\AssessmentResource\RelationManagers;

use App\Models\Question;
use Filament\Forms;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Actions;

class AssessmentAnswersRelationManager extends RelationManager
{
    protected static string $relationship = 'answers';

    protected static ?string $title = 'Jawaban Likert';

    protected static ?string $modelLabel = 'Jawaban';

    public static function canViewForRecord(\Illuminate\Database\Eloquent\Model $ownerRecord, string $pageClass): bool
    {
        $user = auth()->user();
        return $user && $user->hasRole('super_admin');
    }

    public function form(Schema $form): Schema
    {
        return $form->components([
            Forms\Components\Select::make('question_id')
                ->label('Pertanyaan')
                ->options(
                    Question::query()
                        ->with('riasecCategory')
                        ->get()
                        ->mapWithKeys(fn (Question $q) => [
                            $q->id => "[{$q->riasecCategory?->code}] {$q->question_text}",
                        ])
                )
                ->searchable()
                ->disabled()
                ->columnSpanFull(),

            Forms\Components\Select::make('answer')
                ->label('Jawaban')
                ->options([
                    1 => '1 – Sangat Tidak Setuju',
                    2 => '2 – Tidak Setuju',
                    3 => '3 – Netral',
                    4 => '4 – Setuju',
                    5 => '5 – Sangat Setuju',
                ])
                ->required(),

            Forms\Components\DateTimePicker::make('answered_at')
                ->label('Waktu Menjawab')
                ->disabled(),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->heading('Jawaban Likert Scale')
            ->columns([
                Tables\Columns\TextColumn::make('question.riasecCategory.code')
                    ->label('Tipe')
                    ->badge()
                    ->color(fn (?string $state): string => match ($state) {
                        'R' => 'danger',
                        'I' => 'warning',
                        'A' => 'success',
                        'S' => 'info',
                        'E' => 'primary',
                        'C' => 'gray',
                        default => 'gray',
                    })
                    ->width(60),

                Tables\Columns\TextColumn::make('question.question_text')
                    ->label('Pertanyaan')
                    ->limit(60)
                    ->searchable(),

                Tables\Columns\TextColumn::make('answer')
                    ->label('Jawaban')
                    ->badge()
                    ->color(fn (int $state): string => match ($state) {
                        1, 2 => 'danger',
                        3    => 'warning',
                        4, 5 => 'success',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (int $state): string => match ($state) {
                        1 => '1 – Sangat Tidak Setuju',
                        2 => '2 – Tidak Setuju',
                        3 => '3 – Netral',
                        4 => '4 – Setuju',
                        5 => '5 – Sangat Setuju',
                        default => (string) $state,
                    }),

                Tables\Columns\TextColumn::make('answered_at')
                    ->label('Waktu')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
            ])
            ->defaultSort('question.order')
            ->actions([
                Actions\EditAction::make()
                    ->visible(fn () => auth()->user()?->hasRole('super_admin'))
                    ->after(function () {
                        // Recalculate Likert scores after editing an answer
                        $assessment = $this->getOwnerRecord();
                        $assessment->calculateScores();
                        $assessment->refresh();
                        $assessment->generateRiasecCode();
                    }),
            ])
            ->headerActions([])
            ->bulkActions([]);
    }
}
