<?php

namespace App\Filament\Resources\CrudResource\RelationManagers;

use App\Enums\CrudFieldTypes;
use App\Enums\CrudFieldValidation;
use App\Enums\CrudTypes;
use App\Models\Crud;
use App\Models\Panel;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rules\Unique;

class FieldsRelationManager extends RelationManager
{
    protected static string $relationship = 'fields';

    public static function canViewForRecord(Model $ownerRecord, string $pageClass): bool
    {
        /** @var Crud $ownerRecord */
        return $ownerRecord->type === CrudTypes::CRUD;
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('type')
                    ->live()
                    ->options(CrudFieldTypes::class)
                    ->required(),
                Forms\Components\Select::make('validation')
                    ->options(CrudFieldValidation::class)
                    ->required(),
                Forms\Components\Fieldset::make('crudFieldOptions')
                    ->label('Relationship Options')
                    ->relationship('crudFieldOptions')
                    ->hidden(function (Forms\Get $get) {
                        return ! in_array($get('type'), [CrudFieldTypes::BELONGS_TO->value, CrudFieldTypes::BELONGS_TO_MANY->value]);
                    })
                    ->schema([
                        Forms\Components\Select::make('crud_id')
                            ->live()
                            ->label('Related CRUD')
                            ->options(function () {
                                /** @var Panel $panel */
                                $panel = Filament::getTenant();

                                return $panel->cruds()
                                    ->where('type', CrudTypes::CRUD->value)
                                    ->pluck('visual_title', 'id')
                                    ->toArray();
                            })
                            ->required(function (Forms\Get $get) {
                                return in_array($get('../type'), [CrudFieldTypes::BELONGS_TO->value, CrudFieldTypes::BELONGS_TO_MANY->value]);
                            })
                            ->afterStateUpdated(function (Forms\Set $set) {
                                $set('related_crud_field_id', null);
                            }),
                        Forms\Components\Select::make('related_crud_field_id')
                            ->live()
                            ->label('Related Field')
                            ->required(function (Forms\Get $get) {
                                return in_array($get('../type'), [CrudFieldTypes::BELONGS_TO->value, CrudFieldTypes::BELONGS_TO_MANY->value]);
                            })
                            ->options(function (Forms\Get $get) {
                                /** @var Panel $panel */
                                $panel = Filament::getTenant();

                                $crud = $panel->cruds()->find($get('crud_id'));

                                if (! $crud) {
                                    return [];
                                }

                                return $crud->fields()->pluck('label', 'id')->toArray();
                            })
                            ->hidden(function (Forms\Get $get) {
                                if (! in_array($get('../type'), [CrudFieldTypes::BELONGS_TO->value, CrudFieldTypes::BELONGS_TO_MANY->value])) {
                                    return true;
                                }

                                if (! $get('crud_id')) {
                                    return true;
                                }

                                return false;
                            }),
                    ]),
                Forms\Components\TextInput::make('label')
                    ->required()
                    ->unique(modifyRuleUsing: function (Forms\Get $get, Unique $rule) {
                        if ($get('type') === CrudFieldTypes::BELONGS_TO->value) {
                            $key = str($get('label')) // @phpstan-ignore-line
                                ->lower()
                                ->snake()
                                ->toString().'_id';
                        } else {
                            $key = str($get('label'))// @phpstan-ignore-line
                                ->lower()
                                ->snake()
                                ->toString();
                        }

                        /** @var Crud $crud */
                        $crud = $this->getOwnerRecord();

                        return $rule->where('crud_id', $crud->id)
                            ->where('key', $key);
                    })
                    ->maxLength(255),
                Forms\Components\TextInput::make('tooltip')
                    ->label('Placeholder/Hint')
                    ->nullable(),
                Forms\Components\Toggle::make('in_list')
                    ->default(true),
                Forms\Components\Toggle::make('in_create')
                    ->default(true),
                Forms\Components\Toggle::make('in_edit')
                    ->default(true),
                Forms\Components\TextInput::make('order')
                    ->default(function () {
                        /** @var Crud $owner */
                        $owner = $this->getOwnerRecord();

                        return $owner->fields()
                            ->whereNotIn('key', [
                                'created_at',
                                'updated_at',
                                'deleted_at',
                            ])
                            ->max('order') + 1;
                    }),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->paginated(false)
            ->recordTitleAttribute('label')
            ->reorderable('order')
            ->defaultSort('order')
            ->columns([
                Tables\Columns\TextColumn::make('key'),
                Tables\Columns\TextColumn::make('label'),
                Tables\Columns\TextColumn::make('type'),
                Tables\Columns\ToggleColumn::make('in_list'),
                Tables\Columns\ToggleColumn::make('in_create'),
                Tables\Columns\ToggleColumn::make('in_edit'),
                Tables\Columns\IconColumn::make('system'),
            ])
            ->filters([
                // ...
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->mutateFormDataUsing(function (array $data) {
                        /** @var Panel $tenant */
                        $tenant = Filament::getTenant();

                        $data['panel_id'] = $tenant->id;
                        $data['nullable'] = $data['validation'] === CrudFieldValidation::NULLABLE;

                        /** @var Crud $crud */
                        $crud = $this->getOwnerRecord();
                        $crud->fields()
                            ->whereIn('key', [
                                'created_at',
                                'updated_at',
                                'deleted_at',
                            ])
                            ->increment('order');

                        return $data;
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                // TODO: Re-enable this before going into PROD
                //                    ->hidden(fn ($record) => $record->system),
                Tables\Actions\DeleteAction::make(),
                //                    ->hidden(fn ($record) => $record->system),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
