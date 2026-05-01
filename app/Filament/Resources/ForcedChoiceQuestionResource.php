<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ForcedChoiceQuestionResource\Pages;
use App\Models\ForcedChoiceQuestion;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Section;
use Filament\Actions;
use Filament\Tables;
use Filament\Tables\Table;
use UnitEnum;

class ForcedChoiceQuestionResource extends Resource
{
    protected static ?string $model = ForcedChoiceQuestion::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-arrows-right-left';

    protected static string|UnitEnum|null $navigationGroup = 'Master Data';

    protected static ?string $navigationLabel = 'Soal Forced Choice';

    protected static ?string $modelLabel = 'Soal Forced Choice';

    protected static ?string $pluralModelLabel = 'Soal Forced Choice';

    protected static ?int $navigationSort = 3;

    public static function form(Schema $form): Schema
    {
        $typeOptions = [
            'R' => 'R – Realistic',
            'I' => 'I – Investigative',
            'A' => 'A – Artistic',
            'S' => 'S – Social',
            'E' => 'E – Enterprising',
            'C' => 'C – Conventional',
        ];

        return $form->components([
            Section::make('Petunjuk Soal')
                ->schema([
                    Forms\Components\TextInput::make('prompt')
                        ->label('Teks Petunjuk')
                        ->default('Pilih aktivitas yang lebih kamu sukai')
                        ->required()
                        ->maxLength(255)
                        ->columnSpanFull(),
                ]),

            Section::make('Pilihan A')
                ->schema([
                    Forms\Components\TextInput::make('option_a_text')
                        ->label('Teks Pilihan A')
                        ->required()
                        ->maxLength(255),

                    Forms\Components\Select::make('option_a_type')
                        ->label('Tipe RIASEC Pilihan A')
                        ->options($typeOptions)
                        ->required(),
                ])->columns(2),

            Section::make('Pilihan B')
                ->schema([
                    Forms\Components\TextInput::make('option_b_text')
                        ->label('Teks Pilihan B')
                        ->required()
                        ->maxLength(255),

                    Forms\Components\Select::make('option_b_type')
                        ->label('Tipe RIASEC Pilihan B')
                        ->options($typeOptions)
                        ->required(),
                ])->columns(2),

            Section::make('Pengaturan')
                ->schema([
                    Forms\Components\TextInput::make('order')
                        ->label('Urutan')
                        ->numeric()
                        ->default(0),

                    Forms\Components\Toggle::make('is_active')
                        ->label('Aktif')
                        ->default(true),
                ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('order')
                    ->label('No')
                    ->sortable()
                    ->width(60),

                Tables\Columns\TextColumn::make('option_a_text')
                    ->label('Pilihan A')
                    ->searchable()
                    ->limit(40),

                Tables\Columns\BadgeColumn::make('option_a_type')
                    ->label('Tipe A')
                    ->colors([
                        'danger'  => 'R',
                        'warning' => 'I',
                        'success' => 'A',
                        'info'    => 'S',
                        'primary' => 'E',
                        'gray'    => 'C',
                    ]),

                Tables\Columns\TextColumn::make('option_b_text')
                    ->label('Pilihan B')
                    ->searchable()
                    ->limit(40),

                Tables\Columns\BadgeColumn::make('option_b_type')
                    ->label('Tipe B')
                    ->colors([
                        'danger'  => 'R',
                        'warning' => 'I',
                        'success' => 'A',
                        'info'    => 'S',
                        'primary' => 'E',
                        'gray'    => 'C',
                    ]),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean(),
            ])
            ->defaultSort('order')
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

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListForcedChoiceQuestions::route('/'),
            'create' => Pages\CreateForcedChoiceQuestion::route('/create'),
            'edit'   => Pages\EditForcedChoiceQuestion::route('/{record}/edit'),
        ];
    }
}
