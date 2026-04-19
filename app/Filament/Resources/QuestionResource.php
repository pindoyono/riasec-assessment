<?php

namespace App\Filament\Resources;

use App\Filament\Resources\QuestionResource\Pages;
use App\Models\Question;
use App\Models\RiasecCategory;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Actions;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Schemas\Components\Section;
use UnitEnum;

class QuestionResource extends Resource
{
    protected static ?string $model = Question::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-question-mark-circle';

    protected static string|UnitEnum|null $navigationGroup = 'Master Data';

    protected static ?string $navigationLabel = 'Pertanyaan';

    protected static ?string $modelLabel = 'Pertanyaan';

    protected static ?string $pluralModelLabel = 'Pertanyaan';

    protected static ?int $navigationSort = 2;

    public static function form(Schema $form): Schema
    {
        return $form
            ->components([
                Section::make('Informasi Pertanyaan')
                    ->schema([
                        Forms\Components\Select::make('riasec_category_id')
                            ->label('Kategori RIASEC')
                            ->relationship('riasecCategory', 'name')
                            ->required()
                            ->searchable()
                            ->preload()
                            ->getOptionLabelFromRecordUsing(fn (RiasecCategory $record) => "{$record->code} - {$record->name}"),

                        Forms\Components\Textarea::make('question_text')
                            ->label('Pertanyaan (Indonesia)')
                            ->required()
                            ->rows(3)
                            ->columnSpanFull(),

                        Forms\Components\Textarea::make('question_text_en')
                            ->label('Pertanyaan (English)')
                            ->rows(3)
                            ->columnSpanFull(),

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
                Tables\Columns\TextColumn::make('riasecCategory.code')
                    ->label('Kategori')
                    ->badge()
                    ->color(fn (Question $record): string => match ($record->riasecCategory?->code) {
                        'R' => 'danger',
                        'I' => 'info',
                        'A' => 'warning',
                        'S' => 'success',
                        'E' => 'primary',
                        'C' => 'gray',
                        default => 'secondary',
                    })
                    ->sortable(),

                Tables\Columns\TextColumn::make('question_text')
                    ->label('Pertanyaan')
                    ->limit(80)
                    ->searchable()
                    ->wrap(),

                Tables\Columns\TextColumn::make('order')
                    ->label('Urutan')
                    ->sortable(),

                Tables\Columns\IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('riasec_category_id')
                    ->label('Kategori')
                    ->relationship('riasecCategory', 'name')
                    ->getOptionLabelFromRecordUsing(fn (RiasecCategory $record) => "{$record->code} - {$record->name}"),

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
            ])
            ->defaultSort('riasec_category_id');
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
            'index' => Pages\ListQuestions::route('/'),
            'create' => Pages\CreateQuestion::route('/create'),
            'edit' => Pages\EditQuestion::route('/{record}/edit'),
        ];
    }
}
