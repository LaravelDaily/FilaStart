<?php

namespace App\Filament\Resources\CrudResource\Pages;

use App\Enums\CrudFieldTypes;
use App\Enums\CrudTypes;
use App\Filament\Resources\CrudResource;
use App\Models\Crud;
use App\Models\CrudField;
use App\Models\Panel;
use Filament\Facades\Filament;
use Filament\Resources\Pages\CreateRecord;

class CreateCrud extends CreateRecord
{
    protected static string $resource = CrudResource::class;

    public function mutateFormDataBeforeCreate(array $data): array
    {
        /** @var Panel $tenant */
        $tenant = Filament::getTenant();

        return [
            ...$data,
            'user_id' => auth()->id(),
            'panel_id' => $tenant->id,
        ];
    }

    public function afterCreate(): void
    {
        // TODO: We should move this into a service/trait/whatever as fields are duplicated in modules

        /** @var Crud $record */
        $record = $this->record;

        if ($record->type !== CrudTypes::CRUD) {
            return;
        }

        /** @var Panel $tenant */
        $tenant = Filament::getTenant();

        $panelID = $tenant->id;

        $record->fields()->create([
            ...$this->getIDField()->toArray(),
            'panel_id' => $panelID,
        ]);
        $record->fields()->create([
            ...$this->getCreatedAtField(2)->toArray(),
            'panel_id' => $panelID,
        ]);
        $record->fields()->create([
            ...$this->getUpdatedAtField(3)->toArray(),
            'panel_id' => $panelID,
        ]);
        $record->fields()->create([
            ...$this->getDeletedAtField(4)->toArray(),
            'panel_id' => $panelID,
        ]);
    }

    protected function getIDField(): CrudField
    {
        return new CrudField([
            'type' => CrudFieldTypes::ID,
            'key' => str('ID')->lower()->snake()->toString(),
            'label' => 'ID',
            'validation' => 'optional',
            'in_list' => false,
            'in_show' => false,
            'in_create' => false,
            'in_edit' => false,
            'nullable' => false,
            'tooltip' => null,
            'system' => true,
            'enabled' => true,
            'order' => 1,
        ]);
    }

    protected function getCreatedAtField(int $order): CrudField
    {
        return new CrudField([
            'type' => CrudFieldTypes::DATE_TIME,
            'key' => str('Created At')->lower()->snake()->toString(),
            'label' => 'Created At',
            'validation' => 'optional',
            'in_list' => false,
            'in_show' => false,
            'in_create' => false,
            'in_edit' => false,
            'nullable' => false,
            'tooltip' => null,
            'system' => true,
            'enabled' => true,
            'order' => $order,
        ]);
    }

    protected function getUpdatedAtField(int $order): CrudField
    {
        return new CrudField([
            'type' => CrudFieldTypes::DATE_TIME,
            'key' => str('Updated At')->lower()->snake()->toString(),
            'label' => 'Updated At',
            'validation' => 'optional',
            'in_list' => false,
            'in_show' => false,
            'in_create' => false,
            'in_edit' => false,
            'nullable' => false,
            'tooltip' => null,
            'system' => true,
            'enabled' => true,
            'order' => $order,
        ]);
    }

    protected function getDeletedAtField(int $order): CrudField
    {
        return new CrudField([
            'type' => CrudFieldTypes::DATE_TIME,
            'key' => str('Deleted At')->lower()->snake()->toString(),
            'label' => 'Deleted At',
            'validation' => 'optional',
            'in_list' => false,
            'in_show' => false,
            'in_create' => false,
            'in_edit' => false,
            'nullable' => true,
            'tooltip' => null,
            'system' => true,
            'enabled' => true,
            'order' => $order,
        ]);
    }
}
