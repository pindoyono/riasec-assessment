<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StudentResource\Pages;
use App\Models\Student;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Actions;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Schemas\Components\Section;
use Illuminate\Database\Eloquent\Builder;
use UnitEnum;

class StudentResource extends Resource
{
    protected static ?string $model = Student::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-user-group';

    protected static string|UnitEnum|null $navigationGroup = 'Peserta';

    protected static ?string $navigationLabel = 'Siswa';

    protected static ?string $modelLabel = 'Siswa';

    protected static ?string $pluralModelLabel = 'Siswa';

    protected static ?int $navigationSort = 1;

    public static function form(Schema $form): Schema
    {
        return $form
            ->components([
                Section::make('Data Siswa')
                    ->schema([
                        Forms\Components\TextInput::make('nisn')
                            ->label('NISN')
                            ->numeric()
                            ->length(10)
                            ->unique(ignoreRecord: true)
                            ->validationMessages([
                                'numeric' => 'NISN harus berupa angka.',
                                'size' => 'NISN harus 10 digit.',
                            ]),

                        Forms\Components\TextInput::make('name')
                            ->label('Nama Lengkap')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\Select::make('gender')
                            ->label('Jenis Kelamin')
                            ->options([
                                'L' => 'Laki-laki',
                                'P' => 'Perempuan',
                            ])
                            ->required(),

                        Forms\Components\TextInput::make('birth_place')
                            ->label('Tempat Lahir')
                            ->maxLength(100),

                        Forms\Components\DatePicker::make('birth_date')
                            ->label('Tanggal Lahir')
                            ->maxDate(now()),

                        Forms\Components\TextInput::make('asal_sekolah')
                            ->label('Asal Sekolah')
                            ->placeholder('Contoh: SMP Negeri 1 Bandung')
                            ->maxLength(255),

                        Forms\Components\Select::make('school_id')
                            ->label('Lokasi Tempat Test')
                            ->relationship('school', 'name')
                            ->searchable()
                            ->preload()
                            ->default(fn () => auth()->user()?->school_id)
                            ->disabled(fn () => auth()->user()?->school_id && !auth()->user()?->hasRole('super_admin'))
                            ->dehydrated()
                            ->createOptionForm([
                                Forms\Components\TextInput::make('name')
                                    ->label('Nama Sekolah')
                                    ->required(),
                                Forms\Components\TextInput::make('npsn')
                                    ->label('NPSN'),
                                Forms\Components\Select::make('type')
                                    ->label('Jenis')
                                    ->options([
                                        'smk' => 'SMK',
                                        'mak' => 'MAK',
                                    ])
                                    ->default('smk'),
                            ]),

                        Forms\Components\TextInput::make('class')
                            ->label('Kelas')
                            ->maxLength(20)
                            ->placeholder('Contoh: 9A'),
                    ])->columns(2),

                Section::make('Kontak')
                    ->schema([
                        Forms\Components\TextInput::make('phone')
                            ->label('No. HP Siswa')
                            ->tel()
                            ->maxLength(20),

                        Forms\Components\TextInput::make('email')
                            ->label('Email')
                            ->email()
                            ->maxLength(255),

                        Forms\Components\Textarea::make('address')
                            ->label('Alamat')
                            ->rows(2)
                            ->columnSpanFull(),
                    ])->columns(2),

                Section::make('Data Orang Tua')
                    ->schema([
                        Forms\Components\TextInput::make('parent_name')
                            ->label('Nama Orang Tua')
                            ->maxLength(255),

                        Forms\Components\TextInput::make('parent_phone')
                            ->label('No. HP Orang Tua')
                            ->tel()
                            ->maxLength(20),
                    ])->columns(2),

                Section::make('Status')
                    ->schema([
                        Forms\Components\Toggle::make('is_active')
                            ->label('Aktif')
                            ->default(true),
                    ])->columns(1),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nisn')
                    ->label('NISN')
                    ->searchable(),

                Tables\Columns\TextColumn::make('name')
                    ->label('Nama')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('gender')
                    ->label('JK')
                    ->badge()
                    ->color(fn (string $state): string => $state === 'L' ? 'info' : 'pink'),

                Tables\Columns\TextColumn::make('asal_sekolah')
                    ->label('Asal Sekolah')
                    ->searchable()
                    ->limit(25)
                    ->placeholder('-'),

                Tables\Columns\TextColumn::make('school.name')
                    ->label('Lokasi Tempat Test')
                    ->searchable()
                    ->limit(30),

                Tables\Columns\TextColumn::make('class')
                    ->label('Kelas'),

                Tables\Columns\TextColumn::make('latestAssessment.status')
                    ->label('Status Assessment')
                    ->badge()
                    ->color(fn (?string $state): string => match ($state) {
                        'completed' => 'success',
                        'in_progress' => 'warning',
                        'pending' => 'gray',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (?string $state): string => match ($state) {
                        'completed' => 'Selesai',
                        'in_progress' => 'Sedang Mengerjakan',
                        'pending' => 'Belum Mulai',
                        default => 'Belum Ada',
                    }),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('school_id')
                    ->label('Lokasi Tempat Test')
                    ->relationship('school', 'name')
                    ->searchable()
                    ->preload(),

                Tables\Filters\SelectFilter::make('gender')
                    ->label('Jenis Kelamin')
                    ->options([
                        'L' => 'Laki-laki',
                        'P' => 'Perempuan',
                    ]),

                Tables\Filters\Filter::make('has_completed_assessment')
                    ->label('Sudah Assessment')
                    ->query(fn (Builder $query): Builder => $query->whereHas('assessments', fn ($q) => $q->where('status', 'completed'))),

                Tables\Filters\Filter::make('no_assessment')
                    ->label('Belum Assessment')
                    ->query(fn (Builder $query): Builder => $query->whereDoesntHave('assessments')),
            ])
            ->actions([
                Actions\ViewAction::make(),
                Actions\EditAction::make(),
                Actions\Action::make('createAssessment')
                    ->label('Buat Assessment')
                    ->icon('heroicon-o-clipboard-document-list')
                    ->color('success')
                    ->visible(fn (Student $record): bool => !$record->hasCompletedAssessment())
                    ->action(function (Student $record) {
                        $assessment = $record->assessments()->create([
                            'status' => 'pending',
                        ]);

                        return redirect()->route('filament.admin.resources.assessments.view', $assessment);
                    }),
            ])
            ->bulkActions([
                Actions\BulkActionGroup::make([
                    Actions\DeleteBulkAction::make(),
                ]),
            ]);
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

        // Super admin can see all students
        if ($user && $user->hasRole('super_admin')) {
            return $query;
        }

        // Filter by user's school_id
        if ($user && $user->school_id) {
            return $query->where('school_id', $user->school_id);
        }

        return $query;
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListStudents::route('/'),
            'create' => Pages\CreateStudent::route('/create'),
            'view' => Pages\ViewStudent::route('/{record}'),
            'edit' => Pages\EditStudent::route('/{record}/edit'),
        ];
    }
}
