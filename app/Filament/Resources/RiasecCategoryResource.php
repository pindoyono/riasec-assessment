<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RiasecCategoryResource\Pages;
use App\Models\RiasecCategory;
use Filament\Actions;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Schemas\Components\Section;
use UnitEnum;

class RiasecCategoryResource extends Resource
{
    protected static ?string $model = RiasecCategory::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-puzzle-piece';

    protected static string|UnitEnum|null $navigationGroup = 'Master Data';

    protected static ?string $navigationLabel = 'Kategori RIASEC';

    protected static ?string $modelLabel = 'Kategori RIASEC';

    protected static ?string $pluralModelLabel = 'Kategori RIASEC';

    protected static ?int $navigationSort = 1;

    public static function form(Schema $form): Schema
    {
        return $form
            ->components([
                Section::make('Informasi Kategori')
                    ->schema([
                        Forms\Components\TextInput::make('code')
                            ->label('Kode')
                            ->required()
                            ->maxLength(1)
                            ->unique(ignoreRecord: true)
                            ->helperText('Satu huruf: R, I, A, S, E, atau C'),

                        Forms\Components\TextInput::make('name')
                            ->label('Nama Kategori')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\Textarea::make('description')
                            ->label('Deskripsi')
                            ->required()
                            ->columnSpanFull(),

                        Forms\Components\Textarea::make('characteristics')
                            ->label('Karakteristik Kepribadian')
                            ->required()
                            ->columnSpanFull(),

                        Forms\Components\Textarea::make('preferred_activities')
                            ->label('Aktivitas yang Disukai')
                            ->required()
                            ->columnSpanFull(),

                        Forms\Components\Textarea::make('strengths')
                            ->label('Kekuatan')
                            ->required()
                            ->columnSpanFull(),
                    ])->columns(2),

                Section::make('Tampilan')
                    ->schema([
                        Forms\Components\ColorPicker::make('color')
                            ->label('Warna'),

                        Forms\Components\TextInput::make('icon')
                            ->label('Icon')
                            ->helperText('Nama icon Heroicon'),

                        Forms\Components\TextInput::make('order')
                            ->label('Urutan')
                            ->numeric()
                            ->default(0),
                    ])->columns(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('code')
                    ->label('Kode')
                    ->badge()
                    ->color(fn (RiasecCategory $record): string => match ($record->code) {
                        'R' => 'danger',
                        'I' => 'info',
                        'A' => 'warning',
                        'S' => 'success',
                        'E' => 'primary',
                        'C' => 'gray',
                        default => 'secondary',
                    })
                    ->searchable(),

                Tables\Columns\TextColumn::make('name')
                    ->label('Nama')
                    ->searchable(),

                Tables\Columns\TextColumn::make('questions_count')
                    ->label('Jumlah Soal')
                    ->counts('questions')
                    ->badge(),

                Tables\Columns\TextColumn::make('order')
                    ->label('Urutan')
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Actions\ViewAction::make(),
                Actions\EditAction::make(),
            ])
            ->bulkActions([
                Actions\BulkActionGroup::make([
                    Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('order');
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
            'index' => Pages\ListRiasecCategories::route('/'),
            'create' => Pages\CreateRiasecCategory::route('/create'),
            'view' => Pages\ViewRiasecCategory::route('/{record}'),
            'edit' => Pages\EditRiasecCategory::route('/{record}/edit'),
        ];
    }
}
