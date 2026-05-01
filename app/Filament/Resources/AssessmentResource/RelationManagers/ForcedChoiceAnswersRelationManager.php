<?php

namespace App\Filament\Resources\AssessmentResource\RelationManagers;

use App\Models\ForcedChoiceAssessmentAnswer;
use App\Models\ForcedChoiceQuestion;
use Filament\Forms;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Actions;

class ForcedChoiceAnswersRelationManager extends RelationManager
{
    protected static string $relationship = 'forcedChoiceAnswers';

    protected static ?string $title = 'Jawaban Forced Choice';

    protected static ?string $modelLabel = 'Jawaban FC';

    public static function canViewForRecord(\Illuminate\Database\Eloquent\Model $ownerRecord, string $pageClass): bool
    {
        $user = auth()->user();
        return $user && $user->hasRole('super_admin');
    }

    public function form(Schema $form): Schema
    {
        return $form->components([
            Forms\Components\Placeholder::make('question_display')
                ->label('Pertanyaan')
                ->content(function ($record): string {
                    if (!$record) {
                        return '-';
                    }
                    $q = $record->question;
                    return $q
                        ? "A: {$q->option_a_text} [{$q->option_a_type}]  |  B: {$q->option_b_text} [{$q->option_b_type}]"
                        : '-';
                })
                ->columnSpanFull(),

            Forms\Components\Select::make('selected_option')
                ->label('Pilihan Dipilih')
                ->options(['A' => 'A', 'B' => 'B'])
                ->required()
                ->reactive()
                ->afterStateUpdated(function ($state, Forms\Set $set, $record) {
                    if (!$record || !$state) {
                        return;
                    }
                    $question = $record->question;
                    if (!$question) {
                        return;
                    }
                    $type = $state === 'A'
                        ? $question->option_a_type
                        : $question->option_b_type;
                    $set('selected_type', $type);
                }),

            Forms\Components\TextInput::make('selected_type')
                ->label('Tipe RIASEC')
                ->disabled()
                ->dehydrated(true),

            Forms\Components\DateTimePicker::make('answered_at')
                ->label('Waktu Menjawab')
                ->disabled(),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->heading('Jawaban Forced Choice')
            ->columns([
                Tables\Columns\TextColumn::make('question.order')
                    ->label('No')
                    ->sortable()
                    ->width(50),

                Tables\Columns\TextColumn::make('question.option_a_text')
                    ->label('Pilihan A')
                    ->limit(35),

                Tables\Columns\TextColumn::make('question.option_b_text')
                    ->label('Pilihan B')
                    ->limit(35),

                Tables\Columns\TextColumn::make('selected_option')
                    ->label('Dipilih')
                    ->badge()
                    ->color(fn (string $state): string => $state === 'A' ? 'primary' : 'info'),

                Tables\Columns\TextColumn::make('selected_type')
                    ->label('Tipe')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'R' => 'danger',
                        'I' => 'warning',
                        'A' => 'success',
                        'S' => 'info',
                        'E' => 'primary',
                        'C' => 'gray',
                        default => 'gray',
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
                        // Recalculate combined scores after editing a FC answer
                        $assessment = $this->getOwnerRecord();
                        $assessment->refresh();

                        $fcNormalized = ForcedChoiceAssessmentAnswer::calculateNormalizedScores($assessment->id);
                        $likert = [
                            'R' => $assessment->score_r ?? 0,
                            'I' => $assessment->score_i ?? 0,
                            'A' => $assessment->score_a ?? 0,
                            'S' => $assessment->score_s ?? 0,
                            'E' => $assessment->score_e ?? 0,
                            'C' => $assessment->score_c ?? 0,
                        ];
                        $combined = ForcedChoiceAssessmentAnswer::combineScores($likert, $fcNormalized);

                        arsort($combined);
                        $riasecCode = implode('', array_slice(array_keys($combined), 0, 3));

                        $assessment->update([
                            'score_r'     => $combined['R'],
                            'score_i'     => $combined['I'],
                            'score_a'     => $combined['A'],
                            'score_s'     => $combined['S'],
                            'score_e'     => $combined['E'],
                            'score_c'     => $combined['C'],
                            'riasec_code' => $riasecCode,
                        ]);

                        $assessment->refresh();
                        $assessment->generateRecommendations();
                    }),
            ])
            ->headerActions([])
            ->bulkActions([]);
    }
}
