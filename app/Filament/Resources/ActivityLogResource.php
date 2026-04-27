<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ActivityLogResource\Pages;
use Filament\Actions;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Spatie\Activitylog\Models\Activity;
use UnitEnum;

class ActivityLogResource extends Resource
{
    protected static ?string $model = Activity::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-clipboard-document-list';

    protected static string|UnitEnum|null $navigationGroup = 'Manajemen Sistem';

    protected static ?string $navigationLabel = 'Activity Log';

    protected static ?string $modelLabel = 'Activity Log';

    protected static ?string $pluralModelLabel = 'Activity Log';

    protected static ?int $navigationSort = 10;

    public static function canCreate(): bool
    {
        return false;
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('log_name')
                    ->label('Modul')
                    ->badge()
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('event')
                    ->label('Event')
                    ->badge()
                    ->color(fn (?string $state): string => match ($state) {
                        'created'  => 'success',
                        'updated'  => 'warning',
                        'deleted'  => 'danger',
                        default    => 'gray',
                    })
                    ->sortable(),

                Tables\Columns\TextColumn::make('description')
                    ->label('Deskripsi')
                    ->searchable()
                    ->limit(50)
                    ->tooltip(fn (Activity $record): string => $record->description),

                Tables\Columns\TextColumn::make('subject_type')
                    ->label('Model')
                    ->formatStateUsing(fn (?string $state): string => $state ? class_basename($state) : '-')
                    ->sortable(),

                Tables\Columns\TextColumn::make('subject_id')
                    ->label('ID Record')
                    ->sortable(),

                Tables\Columns\TextColumn::make('causer.name')
                    ->label('Dilakukan Oleh')
                    ->default('-')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Waktu')
                    ->dateTime('d M Y H:i:s')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('log_name')
                    ->label('Modul')
                    ->options([
                        'user'            => 'User',
                        'student'         => 'Student',
                        'assessment'      => 'Assessment',
                        'question'        => 'Question',
                        'riasec_category' => 'RIASEC Category',
                        'school'          => 'School',
                        'smk_major'       => 'SMK Major',
                    ]),

                Tables\Filters\SelectFilter::make('event')
                    ->label('Event')
                    ->options([
                        'created' => 'Created',
                        'updated' => 'Updated',
                        'deleted' => 'Deleted',
                    ]),
            ])
            ->defaultSort('created_at', 'desc')
            ->actions([
                Actions\Action::make('detail')
                    ->label('Detail Perubahan')
                    ->icon('heroicon-o-eye')
                    ->color('gray')
                    ->modalHeading('Detail Perubahan')
                    ->modalContent(fn (Activity $record) => view(
                        'filament.activity-log.detail',
                        ['activity' => $record]
                    ))
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Tutup'),
            ])
            ->bulkActions([]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListActivityLogs::route('/'),
        ];
    }
}
