<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SmkMajorResource\Pages;
use App\Models\SmkMajor;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Actions;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Schemas\Components\Section;
use UnitEnum;

class SmkMajorResource extends Resource
{
    protected static ?string $model = SmkMajor::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-academic-cap';

    protected static string|UnitEnum|null $navigationGroup = 'Master Data';

    protected static ?string $navigationLabel = 'Jurusan SMK';

    protected static ?string $modelLabel = 'Jurusan SMK';

    protected static ?string $pluralModelLabel = 'Jurusan SMK';

    protected static ?int $navigationSort = 4;

    public static function form(Schema $form): Schema
    {
        return $form
            ->components([
                Section::make('Informasi Jurusan')
                    ->schema([
                        Forms\Components\TextInput::make('code')
                            ->label('Kode Jurusan')
                            ->required()
                            ->maxLength(50)
                            ->unique(ignoreRecord: true),

                        Forms\Components\TextInput::make('name')
                            ->label('Nama Kompetensi Keahlian')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('program_keahlian')
                            ->label('Program Keahlian')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('bidang_keahlian')
                            ->label('Bidang Keahlian')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\Textarea::make('description')
                            ->label('Deskripsi')
                            ->rows(3)
                            ->columnSpanFull(),
                    ])->columns(2),

                Section::make('Profil RIASEC')
                    ->description('Pilih kode RIASEC yang sesuai dengan jurusan ini (urutkan dari yang paling sesuai)')
                    ->schema([
                        Forms\Components\Select::make('riasec_profile')
                            ->label('Kode RIASEC')
                            ->multiple()
                            ->options([
                                'R' => 'R - Realistic',
                                'I' => 'I - Investigative',
                                'A' => 'A - Artistic',
                                'S' => 'S - Social',
                                'E' => 'E - Enterprising',
                                'C' => 'C - Conventional',
                            ])
                            ->maxItems(3)
                            ->helperText('Pilih maksimal 3 kode, urutkan dari yang paling sesuai'),
                    ]),

                Section::make('Informasi Karir')
                    ->schema([
                        Forms\Components\Textarea::make('career_prospects')
                            ->label('Prospek Karir')
                            ->rows(3)
                            ->helperText('Contoh pekerjaan yang bisa dijalani setelah lulus'),

                        Forms\Components\Textarea::make('skills_learned')
                            ->label('Keterampilan yang Dipelajari')
                            ->rows(3),
                    ])->columns(1),

                Section::make('Status')
                    ->schema([
                        Forms\Components\Toggle::make('is_active')
                            ->label('Aktif')
                            ->default(true),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('code')
                    ->label('Kode')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('name')
                    ->label('Kompetensi Keahlian')
                    ->searchable()
                    ->sortable()
                    ->wrap(),

                Tables\Columns\TextColumn::make('program_keahlian')
                    ->label('Program Keahlian')
                    ->searchable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('bidang_keahlian')
                    ->label('Bidang Keahlian')
                    ->searchable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('riasec_profile')
                    ->label('RIASEC')
                    ->badge()
                    ->formatStateUsing(fn ($state) => is_array($state) ? implode('', $state) : $state)
                    ->color('primary'),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('bidang_keahlian')
                    ->label('Bidang Keahlian')
                    ->options(fn () => SmkMajor::distinct()->pluck('bidang_keahlian', 'bidang_keahlian')),

                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Status'),
            ])
            ->actions([
                Actions\ViewAction::make(),
                Actions\EditAction::make(),
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
            'index' => Pages\ListSmkMajors::route('/'),
            'create' => Pages\CreateSmkMajor::route('/create'),
            'view' => Pages\ViewSmkMajor::route('/{record}'),
            'edit' => Pages\EditSmkMajor::route('/{record}/edit'),
        ];
    }
}
