<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SchoolResource\Pages;
use App\Models\School;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Schemas\Components\Section;
use UnitEnum;

class SchoolResource extends Resource
{
    protected static ?string $model = School::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-building-library';

    protected static string|UnitEnum|null $navigationGroup = 'Master Data';

    protected static ?string $navigationLabel = 'Lokasi Test';

    protected static ?string $modelLabel = 'Lokasi Test';

    protected static ?string $pluralModelLabel = 'Lokasi Test';

    protected static ?int $navigationSort = 3;

    public static function canCreate(): bool
    {
        $user = auth()->user();

        // Only super_admin can create new schools
        return $user && $user->hasRole('super_admin');
    }

    public static function canDelete(\Illuminate\Database\Eloquent\Model $record): bool
    {
        $user = auth()->user();

        // Only super_admin can delete schools
        return $user && $user->hasRole('super_admin');
    }

    public static function form(Schema $form): Schema
    {
        return $form
            ->components([
                Section::make('Informasi Sekolah')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Nama Sekolah')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('npsn')
                            ->label('NPSN')
                            ->maxLength(20)
                            ->unique(ignoreRecord: true),

                        Forms\Components\Select::make('type')
                            ->label('Jenis')
                            ->options([
                                'smk' => 'SMK',
                                'mak' => 'MAK',
                            ])
                            ->default('smk')
                            ->required(),

                        Forms\Components\Toggle::make('is_active')
                            ->label('Aktif')
                            ->default(true),
                    ])->columns(2),

                Section::make('Alamat')
                    ->schema([
                        Forms\Components\Textarea::make('address')
                            ->label('Alamat Lengkap')
                            ->rows(2)
                            ->columnSpanFull(),

                        Forms\Components\TextInput::make('district')
                            ->label('Kecamatan'),

                        Forms\Components\TextInput::make('city')
                            ->label('Kabupaten/Kota'),

                        Forms\Components\TextInput::make('province')
                            ->label('Provinsi'),
                    ])->columns(3),

                Section::make('Token Registrasi')
                    ->description('Token digunakan siswa untuk login ke assessment')
                    ->schema([
                        Forms\Components\TextInput::make('token_valid_minutes')
                            ->label('Masa Berlaku Token (Menit)')
                            ->numeric()
                            ->default(60)
                            ->minValue(5)
                            ->maxValue(1440)
                            ->suffix('menit')
                            ->helperText('Token akan expired setelah waktu ini sejak di-generate'),

                        Forms\Components\Placeholder::make('registration_token_display')
                            ->label('Token Aktif')
                            ->content(fn (?School $record): string => $record?->registration_token ?? 'Belum di-generate')
                            ->visible(fn (?School $record): bool => $record !== null),

                        Forms\Components\Placeholder::make('token_status')
                            ->label('Status Token')
                            ->content(function (?School $record): string {
                                if (!$record || !$record->registration_token) {
                                    return 'Belum ada token';
                                }
                                if ($record->isTokenExpired()) {
                                    return '❌ Expired pada ' . $record->token_expires_at->format('d M Y H:i');
                                }
                                return '✅ Valid hingga ' . $record->token_expires_at->format('d M Y H:i') . ' (' . $record->token_remaining_time . ')';
                            })
                            ->visible(fn (?School $record): bool => $record !== null),
                    ])->columns(1),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('npsn')
                    ->label('NPSN')
                    ->searchable(),

                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Sekolah')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('type')
                    ->label('Jenis')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => strtoupper($state)),

                Tables\Columns\TextColumn::make('registration_token')
                    ->label('Token')
                    ->badge()
                    ->color(fn (School $record): string => $record->isTokenValid() ? 'success' : 'danger')
                    ->copyable()
                    ->copyMessage('Token disalin!')
                    ->placeholder('Belum ada'),

                Tables\Columns\TextColumn::make('token_expires_at')
                    ->label('Expired')
                    ->dateTime('d M Y H:i')
                    ->color(fn (School $record): string => $record->isTokenExpired() ? 'danger' : 'success')
                    ->placeholder('-'),

                Tables\Columns\TextColumn::make('students_count')
                    ->label('Siswa')
                    ->counts('students')
                    ->badge(),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->label('Jenis')
                    ->options([
                        'smk' => 'SMK',
                        'mak' => 'MAK',
                    ]),

                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Status'),
            ])
            ->actions([
                Actions\Action::make('generateToken')
                    ->label('Generate Token')
                    ->icon('heroicon-o-key')
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalHeading('Generate Token Baru')
                    ->modalDescription('Token lama akan diganti dengan token baru. Siswa yang menggunakan token lama tidak akan bisa login.')
                    ->modalSubmitActionLabel('Ya, Generate Token Baru')
                    ->action(function (School $record) {
                        $token = $record->generateToken();
                        Notification::make()
                            ->title('Token berhasil di-generate!')
                            ->body("Token baru: {$token}")
                            ->success()
                            ->send();
                    }),
                Actions\Action::make('activities')
                    ->label('Aktivitas')
                    ->icon('heroicon-o-clock')
                    ->url(fn (School $record): string => static::getUrl('activities', ['record' => $record])),
                Actions\EditAction::make(),
                Actions\DeleteAction::make(),
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

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        $query = parent::getEloquentQuery();

        $user = auth()->user();

        // Super admin can see all schools
        if ($user && $user->hasRole('super_admin')) {
            return $query;
        }

        // Filter by user's school_id - user can only see their own school
        if ($user && $user->school_id) {
            return $query->where('id', $user->school_id);
        }

        // If user has no school_id, show nothing
        return $query->whereRaw('1 = 0');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSchools::route('/'),
            'create' => Pages\CreateSchool::route('/create'),
            'activities' => Pages\ListSchoolActivities::route('/{record}/activities'),
            'edit' => Pages\EditSchool::route('/{record}/edit'),
        ];
    }
}
