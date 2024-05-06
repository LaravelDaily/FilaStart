<?php

namespace App\Interfaces;

use App\Enums\CrudFieldTypes;
use App\Enums\CrudTypes;
use App\Models\Crud;
use App\Models\CrudField;
use App\Models\Panel;
use App\Services\PanelService;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Nette\NotImplementedException;

class ModuleBase
{
    public string $slug = '';

    public function install(Panel $panel): void
    {
        if (! $this->slug) {
            throw new NotImplementedException('Slug is not defined');
        }

        $lastOrder = ($panel->cruds()->max('menu_order') ?? 0) + 1;

        foreach ($this->getCruds() as $crudData) {

            if ($crudData->type !== CrudTypes::PARENT && $crudData->parent_id !== null) {
                $parent = $panel->cruds()->where('title', $crudData->parent_id)->first();
            }

            $crud = $panel->cruds()->create([
                ...$crudData->toArray(),
                'menu_order' => $lastOrder,
                'module_crud' => true,
                'module_slug' => $this->slug,
                'user_id' => $panel->user_id,
                'parent_id' => $parent->id ?? null,
            ]);

            $lastOrder++;

            foreach ($crudData->fields as $fieldData) {

                $field = $crud->fields()->create([
                    ...$fieldData->toArray(),
                    'panel_id' => $panel->id,
                ]);

                if ($fieldData->crudFieldOptions) {
                    /** @var Crud|null $relatedCRUD */
                    $relatedCRUD = Crud::where('title', $fieldData->crudFieldOptions->crud_id)
                        ->where('panel_id', $panel->id)
                        ->when($fieldData->crudFieldOptions->crud_id !== 'User', function (Builder $query) {
                            return $query->where('module_slug', $this->slug);
                        })
                        ->first();

                    if (! $relatedCRUD) {
                        throw new Exception('Related CRUD not found');
                    }

                    /** @var CrudField|null $relatedField */
                    $relatedField = $relatedCRUD->fields()->where('key', $fieldData->crudFieldOptions->related_crud_field_id)->first();
                    if (! $relatedField) {
                        throw new Exception('Related field not found');
                    }

                    $field->crudFieldOptions()->create([
                        ...$fieldData->crudFieldOptions->toArray(),
                        'crud_id' => $relatedCRUD->id,
                        'related_crud_field_id' => $relatedField->id,
                    ]);
                }

            }
        }

    }

    public function uninstall(Panel $panel): void
    {
        if (! $this->slug) {
            throw new NotImplementedException('Slug is not defined');
        }

        $cruds = $panel->cruds()
            ->where('module_slug', $this->slug)
            ->with('panelFiles')
            ->get();

        $panelService = new PanelService($panel);

        foreach ($cruds as $crud) {

            foreach ($crud->panelFiles as $panelFile) {
                $panelService->deleteFile($panelFile);
                $panelFile->delete();
            }

            foreach ($crud->fields as $field) {
                $field->crudFieldOptions()->delete();
                $field->delete();
            }

            $crud->delete();
        }
    }

    /**
     * @return Crud[]
     */
    public function getCruds(): array
    {
        throw new NotImplementedException('Method getCruds is not implemented');
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
