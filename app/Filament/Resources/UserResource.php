<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Actions;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Schemas\Components\Section;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use UnitEnum;

class UserResource extends Resource
{
    /**
     * Batasi data pengguna yang tampil hanya milik user tersebut,
     * kecuali superadmin bisa melihat semua.
     */
    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        $query = parent::getEloquentQuery();
        $user = auth()->user();
        if (!$user?->hasRole('super_admin')) {
            $query->where('id', $user->id);
        }
        return $query;
    }
    protected static ?string $model = User::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-users';

    protected static string|UnitEnum|null $navigationGroup = 'Manajemen Pengguna';

    protected static ?string $navigationLabel = 'Pengguna';

    protected static ?string $modelLabel = 'Pengguna';

    protected static ?string $pluralModelLabel = 'Pengguna';

    protected static ?int $navigationSort = 1;

    public static function form(Schema $form): Schema
    {
        $isSuperAdmin = auth()->user()?->hasRole('super_admin');
        return $form
            ->components([
                Section::make('Informasi Pengguna')
                    ->schema(array_filter([
                        Forms\Components\TextInput::make('name')
                            ->label('Nama')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('email')
                            ->label('Email')
                            ->email()
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true),

                        Forms\Components\TextInput::make('password')
                            ->label('Password')
                            ->password()
                            ->dehydrateStateUsing(fn (string $state): string => Hash::make($state))
                            ->dehydrated(fn (?string $state): bool => filled($state))
                            ->required(fn (string $operation): bool => $operation === 'create')
                            ->minLength(8)
                            ->maxLength(255),

                        $isSuperAdmin ? Forms\Components\Select::make('school_id')
                            ->label('Sekolah')
                            ->relationship('school', 'name')
                            ->searchable()
                            ->preload()
                            ->placeholder('Pilih Sekolah (Opsional)') : null,

                        $isSuperAdmin ? Forms\Components\Select::make('roles')
                            ->label('Role')
                            ->relationship('roles', 'name')
                            ->multiple()
                            ->preload()
                            ->searchable() : null,

                        $isSuperAdmin ? Forms\Components\Toggle::make('is_active')
                            ->label('Aktif')
                            ->default(true) : null,
                    ]))
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextInputColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->sortable()
                    ->rules(['required', 'email', 'max:255'])
                    ->updateStateUsing(function (User $record, string $state): string {
                        $state = trim($state);
                        // Ensure uniqueness excluding the current record
                        $exists = User::where('email', $state)
                            ->where('id', '!=', $record->id)
                            ->exists();
                        if ($exists) {
                            \Filament\Notifications\Notification::make()
                                ->title('Email sudah digunakan oleh pengguna lain.')
                                ->danger()
                                ->send();
                            return $record->email;
                        }
                        $record->update(['email' => $state]);
                        \Filament\Notifications\Notification::make()
                            ->title('Email berhasil diperbarui.')
                            ->success()
                            ->send();
                        return $state;
                    }),

                Tables\Columns\TextColumn::make('school.name')
                    ->label('Sekolah')
                    ->searchable()
                    ->sortable()
                    ->placeholder('-'),

                // Kolom Role hanya untuk super_admin
                ...(
                    auth()->user()?->hasRole('super_admin')
                        ? [
                            Tables\Columns\SelectColumn::make('current_role')
                                ->label('Role')
                                ->getStateUsing(fn (User $record): ?string => $record->roles()->pluck('name')->first())
                                ->options(fn (): array => Role::query()->orderBy('name')->pluck('name', 'name')->toArray())
                                ->searchable()
                                ->placeholder('-')
                                ->rules(['required'])
                                ->disabled(fn (): bool => auth()->user()?->hasRole('super_admin') !== true)
                                ->updateStateUsing(function (User $record, ?string $state): ?string {
                                    if (auth()->user()?->hasRole('super_admin') !== true) {
                                        \Filament\Notifications\Notification::make()
                                            ->title('Hanya super admin yang dapat mengubah role pengguna.')
                                            ->danger()
                                            ->send();

                                        return $record->roles()->pluck('name')->first();
                                    }

                                    if (blank($state)) {
                                        return $record->roles()->pluck('name')->first();
                                    }

                                    $record->syncRoles([$state]);

                                    \Filament\Notifications\Notification::make()
                                        ->title('Role pengguna berhasil diperbarui.')
                                        ->success()
                                        ->send();

                                    return $state;
                                }),
                        ]
                        : []
                ),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('school')
                    ->label('Sekolah')
                    ->relationship('school', 'name')
                    ->searchable()
                    ->preload(),

                Tables\Filters\SelectFilter::make('roles')
                    ->label('Role')
                    ->relationship('roles', 'name')
                    ->searchable()
                    ->preload(),

                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Status'),
            ])
            ->actions([
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
