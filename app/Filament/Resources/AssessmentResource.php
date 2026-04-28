<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AssessmentResource\Pages;
use App\Models\Assessment;
use Filament\Forms;
use Filament\Infolists;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Actions;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Grid;
use Illuminate\Database\Eloquent\Builder;
use UnitEnum;

class AssessmentResource extends Resource
{
    protected static ?string $model = Assessment::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-clipboard-document-check';

    protected static string|UnitEnum|null $navigationGroup = 'Peserta';

    protected static ?string $navigationLabel = 'Hasil Assessment';

    protected static ?string $modelLabel = 'Assessment';

    protected static ?string $pluralModelLabel = 'Assessment';

    protected static ?int $navigationSort = 2;

    public static function canEdit(\Illuminate\Database\Eloquent\Model $record): bool
    {
        $user = auth()->user();

        return $user && $user->hasRole('super_admin');
    }

    public static function canDelete(\Illuminate\Database\Eloquent\Model $record): bool
    {
        $user = auth()->user();

        return $user && $user->hasRole('super_admin');
    }

    public static function canDeleteAny(): bool
    {
        $user = auth()->user();

        return $user && $user->hasRole('super_admin');
    }

    public static function form(Schema $form): Schema
    {
        return $form
            ->components([
                Section::make('Informasi Assessment')
                    ->schema([
                        Forms\Components\Select::make('student_id')
                            ->label('Siswa')
                            ->relationship('student', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),

                        Forms\Components\TextInput::make('assessment_code')
                            ->label('Kode Assessment')
                            ->disabled(),

                        Forms\Components\Select::make('status')
                            ->label('Status')
                            ->options([
                                'pending' => 'Pending',
                                'in_progress' => 'Sedang Mengerjakan',
                                'completed' => 'Selesai',
                                'expired' => 'Kadaluarsa',
                            ])
                            ->required(),
                    ])->columns(3),
            ]);
    }

    public static function infolist(Schema $infolist): Schema
    {
        return $infolist
            ->components([
                Section::make('Informasi Siswa')
                    ->schema([
                        Infolists\Components\TextEntry::make('student.name')
                            ->label('Nama Siswa'),
                        Infolists\Components\TextEntry::make('student.nisn')
                            ->label('NISN'),
                        Infolists\Components\TextEntry::make('student.school.name')
                            ->label('Asal Sekolah'),
                        Infolists\Components\TextEntry::make('student.class')
                            ->label('Kelas'),
                    ])->columns(4),

                Section::make('Informasi Assessment')
                    ->schema([
                        Infolists\Components\TextEntry::make('assessment_code')
                            ->label('Kode Assessment')
                            ->badge(),
                        Infolists\Components\TextEntry::make('status')
                            ->label('Status')
                            ->badge()
                            ->color(fn (string $state): string => match ($state) {
                                'completed' => 'success',
                                'in_progress' => 'warning',
                                'pending' => 'gray',
                                'expired' => 'danger',
                                default => 'gray',
                            })
                            ->formatStateUsing(fn (string $state): string => match ($state) {
                                'completed' => 'Selesai',
                                'in_progress' => 'Sedang Mengerjakan',
                                'pending' => 'Belum Mulai',
                                'expired' => 'Kadaluarsa',
                                default => $state,
                            }),
                        Infolists\Components\TextEntry::make('started_at')
                            ->label('Waktu Mulai')
                            ->dateTime('d M Y H:i'),
                        Infolists\Components\TextEntry::make('completed_at')
                            ->label('Waktu Selesai')
                            ->dateTime('d M Y H:i'),
                        Infolists\Components\TextEntry::make('formatted_duration')
                            ->label('Durasi'),
                    ])->columns(5),

                Section::make('Hasil RIASEC')
                    ->schema([
                        Infolists\Components\TextEntry::make('riasec_code')
                            ->label('Kode RIASEC')
                            ->badge()
                            ->size('lg')
                            ->color('success'),
                        Grid::make(6)
                            ->schema([
                                Infolists\Components\TextEntry::make('score_r')
                                    ->label('Realistic')
                                    ->suffix('%')
                                    ->badge()
                                    ->color('danger'),
                                Infolists\Components\TextEntry::make('score_i')
                                    ->label('Investigative')
                                    ->suffix('%')
                                    ->badge()
                                    ->color('info'),
                                Infolists\Components\TextEntry::make('score_a')
                                    ->label('Artistic')
                                    ->suffix('%')
                                    ->badge()
                                    ->color('warning'),
                                Infolists\Components\TextEntry::make('score_s')
                                    ->label('Social')
                                    ->suffix('%')
                                    ->badge()
                                    ->color('success'),
                                Infolists\Components\TextEntry::make('score_e')
                                    ->label('Enterprising')
                                    ->suffix('%')
                                    ->badge()
                                    ->color('primary'),
                                Infolists\Components\TextEntry::make('score_c')
                                    ->label('Conventional')
                                    ->suffix('%')
                                    ->badge()
                                    ->color('gray'),
                            ]),
                    ])
                    ->visible(fn (Assessment $record): bool => $record->status === 'completed'),

                Section::make('Rekomendasi Jurusan SMK')
                    ->schema([
                        Infolists\Components\RepeatableEntry::make('recommendations')
                            ->label('')
                            ->schema([
                                Infolists\Components\TextEntry::make('rank')
                                    ->label('Peringkat')
                                    ->badge()
                                    ->color(fn (int $state): string => match ($state) {
                                        1 => 'success',
                                        2 => 'info',
                                        3 => 'warning',
                                        default => 'gray',
                                    }),
                                Infolists\Components\TextEntry::make('smkMajor.name')
                                    ->label('Kompetensi Keahlian'),
                                Infolists\Components\TextEntry::make('smkMajor.program_keahlian')
                                    ->label('Program Keahlian'),
                                Infolists\Components\TextEntry::make('match_percentage')
                                    ->label('Kecocokan')
                                    ->badge()
                                    ->color('success'),
                                Infolists\Components\TextEntry::make('match_reason')
                                    ->label('Alasan'),
                            ])
                            ->columns(5),
                    ])
                    ->visible(fn (Assessment $record): bool => $record->status === 'completed'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('assessment_code')
                    ->label('Kode')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('student.name')
                    ->label('Nama Siswa')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('student.school.name')
                    ->label('Asal Sekolah')
                    ->searchable()
                    ->limit(25)
                    ->toggleable(),

                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'completed' => 'success',
                        'in_progress' => 'warning',
                        'pending' => 'gray',
                        'expired' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'completed' => 'Selesai',
                        'in_progress' => 'Sedang Mengerjakan',
                        'pending' => 'Belum Mulai',
                        'expired' => 'Kadaluarsa',
                        default => $state,
                    }),

                Tables\Columns\TextColumn::make('riasec_code')
                    ->label('RIASEC')
                    ->badge()
                    ->color('primary'),

                Tables\Columns\TextColumn::make('completed_at')
                    ->label('Tanggal Selesai')
                    ->dateTime('d M Y H:i')
                    ->sortable(),

                Tables\Columns\TextColumn::make('formatted_duration')
                    ->label('Durasi')
                    ->toggleable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'pending' => 'Pending',
                        'in_progress' => 'Sedang Mengerjakan',
                        'completed' => 'Selesai',
                        'expired' => 'Kadaluarsa',
                    ]),

                Tables\Filters\SelectFilter::make('student.school_id')
                    ->label('Sekolah')
                    ->relationship('student.school', 'name')
                    ->searchable()
                    ->preload(),

                Tables\Filters\Filter::make('completed_at')
                    ->form([
                        Forms\Components\DatePicker::make('from')
                            ->label('Dari Tanggal'),
                        Forms\Components\DatePicker::make('until')
                            ->label('Sampai Tanggal'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('completed_at', '>=', $date),
                            )
                            ->when(
                                $data['until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('completed_at', '<=', $date),
                            );
                    }),
            ])
            ->actions([
                Actions\ViewAction::make(),
                Actions\EditAction::make()
                    ->visible(fn (): bool => auth()->user()?->hasRole('super_admin') === true),
                Actions\DeleteAction::make()
                    ->requiresConfirmation()
                    ->modalHeading('Hapus Hasil Assessment?')
                    ->modalDescription(fn (Assessment $record): string => "Data hasil assessment {$record->assessment_code} milik {$record->student?->name} akan dihapus permanen dan tidak dapat dikembalikan.")
                    ->modalSubmitActionLabel('Ya, Hapus Sekarang')
                    ->successNotificationTitle('Hasil assessment berhasil dihapus.')
                    ->failureNotificationTitle('Gagal menghapus hasil assessment.')
                    ->visible(fn (): bool => auth()->user()?->hasRole('super_admin') === true),
                Actions\Action::make('downloadPdf')
                    ->label('Download PDF')
                    ->icon('heroicon-o-document-arrow-down')
                    ->color('success')
                    ->visible(fn (Assessment $record): bool => $record->status === 'completed')
                    ->url(fn (Assessment $record): string => route('assessment.pdf', $record->assessment_code))
                    ->openUrlInNewTab(),
            ])
            ->bulkActions([
                Actions\BulkActionGroup::make([
                    Actions\DeleteBulkAction::make()
                        ->requiresConfirmation()
                        ->modalHeading('Hapus Data Assessment Terpilih?')
                        ->modalDescription('Semua data assessment yang dipilih akan dihapus permanen dan tidak dapat dikembalikan.')
                        ->modalSubmitActionLabel('Ya, Hapus Semua yang Dipilih')
                        ->successNotificationTitle('Data assessment terpilih berhasil dihapus.')
                        ->failureNotificationTitle('Gagal menghapus data assessment terpilih.')
                        ->visible(fn (): bool => auth()->user()?->hasRole('super_admin') === true),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        $user = auth()->user();

        // Super admin can see all assessments
        if ($user && $user->hasRole('super_admin')) {
            return $query;
        }

        // Filter by user's school_id through student relationship
        if ($user && $user->school_id) {
            return $query->whereHas('student', fn ($q) => $q->where('school_id', $user->school_id));
        }

        return $query;
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAssessments::route('/'),
            'view' => Pages\ViewAssessment::route('/{record}'),
            'edit' => Pages\EditAssessment::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        $query = static::getModel()::where('status', 'completed');

        $user = auth()->user();
        if ($user && $user->school_id && !$user->hasRole('super_admin')) {
            $query->whereHas('student', fn ($q) => $q->where('school_id', $user->school_id));
        }

        return $query->count();
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'success';
    }
}
