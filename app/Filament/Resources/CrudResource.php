<?php

namespace App\Filament\Resources;

use App\Enums\CrudTypes;
use App\Enums\HeroIcons;
use App\Filament\Resources\CrudResource\Pages;
use App\Filament\Resources\CrudResource\RelationManagers\FieldsRelationManager;
use App\Models\Crud;
use App\Models\Panel;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\HtmlString;

class CrudResource extends Resource
{
    protected static ?string $model = Crud::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $modelLabel = 'CRUD';

    protected static ?string $pluralModelLabel = 'CRUDs';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('parent_id')
                    ->label('Parent')
                    ->options(function () {
                        /** @var Panel $tenant */
                        $tenant = Filament::getTenant();

                        return $tenant->cruds()->parent()->pluck('visual_title', 'id');
                    }),
                Forms\Components\Select::make('type')
                    ->options(CrudTypes::class)
                    ->default(CrudTypes::CRUD->value)
                    ->required(),
                Forms\Components\TextInput::make('visual_title')
                    ->required(),
                Forms\Components\Select::make('icon')
                    ->allowHtml()
                    ->searchable()
                    ->default(HeroIcons::O_RECTANGLE_STACK)
                    ->options(function (): array {
                        return collect(HeroIcons::cases())
                            ->mapWithKeys(function (HeroIcons $case) {
                                return [$case->value => "<span class='flex items-center'>
                                    ".svg($case->value, ['class' => 'h-5 w-5', 'style' => 'margin-right: 0.4rem;'])->toHtml().'
                                    <span>'.svg($case->value)->name().'</span>
                                </span>'];
                            })
                            ->toArray();
                    })
                    ->getOptionLabelUsing(function (mixed $value): string {
                        return collect(HeroIcons::cases())
                            ->filter(fn (HeroIcons $enum) => stripos($enum->value, $value->value ?? $value) !== false)
                            ->map(function (HeroIcons $case) {
                                return "<span class='flex items-center'>
                                    ".svg($case->value, ['class' => 'h-5 w-5', 'style' => 'margin-right: 0.4rem;'])->toHtml().'
                                    <span>'.svg($case->value)->name().'</span>
                                </span>';
                            })->first() ?? '';
                    })
                    ->getSearchResultsUsing(function (string $search): array {
                        return collect(HeroIcons::cases())
                            ->filter(fn (HeroIcons $enum) => stripos($enum->value, $search->value ?? $search) !== false)
                            ->mapWithKeys(function (HeroIcons $case) {
                                return [$case->value => "<span class='flex items-center'>
                                    ".svg($case->value, ['class' => 'h-5 w-5', 'style' => 'margin-right: 0.4rem;'])->toHtml().'
                                    <span>'.svg($case->value)->name().'</span>
                                </span>'];
                            })
                            ->toArray();
                    }),
                Forms\Components\TextInput::make('menu_order')
                    ->required()
                    ->default(function () {
                        /** @var Panel $tenant */
                        $tenant = Filament::getTenant();

                        return $tenant->cruds()->max('menu_order') + 1;
                    })
                    ->numeric(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->paginated(false)
            ->columns([
                Tables\Columns\TextColumn::make('parent.title')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('title')
                    ->searchable(),
                Tables\Columns\TextColumn::make('visual_title')
                    ->searchable(),
                Tables\Columns\TextColumn::make('icon')
                    ->formatStateUsing(fn (Crud $record) => new HtmlString(svg((string) $record->icon?->value, ['class' => 'h-6 w-6'])->toHtml()))
                    ->searchable(),
                Tables\Columns\TextColumn::make('menu_order')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\IconColumn::make('system')
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            FieldsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCruds::route('/'),
            'create' => Pages\CreateCrud::route('/create'),
            'edit' => Pages\EditCrud::route('/{record}/edit'),
        ];
    }
}
