<?php

namespace Generators\Filament3\Modules;

use App\Enums\CrudFieldTypes;
use App\Enums\CrudTypes;
use App\Interfaces\ModuleBase;
use App\Models\Crud;
use App\Models\CrudField;
use App\Models\CrudFieldOptions;

class AssetManagementModule extends ModuleBase
{
    public string $slug = 'asset-management';

    public function getCruds(): array
    {
        return [
            (new Crud([
                'type' => CrudTypes::PARENT,
                'title' => str('Asset Management')->singular()->studly(),
                'visual_title' => 'Asset Management',
                'icon' => 'heroicon-o-briefcase',
                'menu_order' => 2,
                'is_hidden' => false,
                'module_crud' => true,
                'module_slug' => $this->slug,
                'system' => true,
            ])),
            (new Crud([
                'parent_id' => str('Asset Management')->singular()->studly(),
                'type' => CrudTypes::CRUD,
                'title' => str('AssetCategory')->singular()->studly(),
                'visual_title' => 'Categories',
                'icon' => '',
                'menu_order' => 1,
                'is_hidden' => false,
                'module_crud' => true,
                'module_slug' => $this->slug,
                'system' => true,
            ]))
                ->setRelation('fields', [
                    $this->getIDField(),
                    new CrudField([
                        'type' => CrudFieldTypes::TEXT,
                        'key' => str('Name')->lower()
                            ->snake()
                            ->toString(),
                        'label' => 'Name',
                        'validation' => 'required',
                        'in_list' => true,
                        'in_show' => true,
                        'in_create' => true,
                        'in_edit' => true,
                        'nullable' => false,
                        'tooltip' => null,
                        'system' => true,
                        'enabled' => true,
                        'order' => 2,
                    ]),
                    $this->getCreatedAtField(3),
                    $this->getUpdatedAtField(4),
                    $this->getDeletedAtField(5),
                ]
                ),
            (new Crud([
                'parent_id' => str('Asset Management')->singular()->studly(),
                'type' => CrudTypes::CRUD,
                'title' => str('AssetLocation')->singular()->studly(),
                'visual_title' => 'Location',
                'icon' => '',
                'menu_order' => 1,
                'is_hidden' => false,
                'module_crud' => true,
                'module_slug' => $this->slug,
                'system' => true,
            ]))
                ->setRelation('fields', [
                    $this->getIDField(),
                    new CrudField([
                        'type' => CrudFieldTypes::TEXT,
                        'key' => str('Name')->lower()
                            ->snake()
                            ->toString(),
                        'label' => 'Name',
                        'validation' => 'required',
                        'in_list' => true,
                        'in_show' => true,
                        'in_create' => true,
                        'in_edit' => true,
                        'nullable' => false,
                        'tooltip' => null,
                        'system' => true,
                        'enabled' => true,
                        'order' => 2,
                    ]),
                    $this->getCreatedAtField(3),
                    $this->getUpdatedAtField(4),
                    $this->getDeletedAtField(5),
                ]
                ),
            (new Crud([
                'parent_id' => str('Asset Management')->singular()->studly(),
                'type' => CrudTypes::CRUD,
                'title' => str('AssetStatus')->singular()->studly(),
                'visual_title' => 'Statuses',
                'icon' => '',
                'menu_order' => 1,
                'is_hidden' => false,
                'module_crud' => true,
                'module_slug' => $this->slug,
                'system' => true,
            ]))
                ->setRelation('fields', [
                    $this->getIDField(),
                    new CrudField([
                        'type' => CrudFieldTypes::TEXT,
                        'key' => str('Name')->lower()
                            ->snake()
                            ->toString(),
                        'label' => 'Name',
                        'validation' => 'required',
                        'in_list' => true,
                        'in_show' => true,
                        'in_create' => true,
                        'in_edit' => true,
                        'nullable' => false,
                        'tooltip' => null,
                        'system' => true,
                        'enabled' => true,
                        'order' => 2,
                    ]),
                    $this->getCreatedAtField(3),
                    $this->getUpdatedAtField(4),
                    $this->getDeletedAtField(5),
                ]
                ),
            (new Crud([
                'parent_id' => str('Asset Management')->singular()->studly(),
                'type' => CrudTypes::CRUD,
                'title' => str('Assets')->singular()->studly(),
                'visual_title' => 'Assets',
                'icon' => '',
                'menu_order' => 1,
                'is_hidden' => false,
                'module_crud' => true,
                'module_slug' => $this->slug,
                'system' => true,
            ]))
                ->setRelation('fields', [
                    $this->getIDField(),
                    (new CrudField([
                        'type' => CrudFieldTypes::BELONGS_TO,
                        'key' => str('Category')->lower()
                            ->snake()
                            ->toString(),
                        'label' => 'Category',
                        'validation' => 'required',
                        'in_list' => true,
                        'in_show' => true,
                        'in_create' => true,
                        'in_edit' => true,
                        'nullable' => false,
                        'tooltip' => null,
                        'system' => true,
                        'enabled' => true,
                        'order' => 2,
                    ]))
                        ->setRelation('crudFieldOptions', new CrudFieldOptions([
                            'crud_id' => 'AssetCategory',
                            'related_crud_field_id' => 'name',
                        ])),
                    new CrudField([
                        'type' => CrudFieldTypes::TEXT,
                        'key' => 'serial_number',
                        'label' => 'Serial Number',
                        'validation' => 'required',
                        'in_list' => true,
                        'in_show' => true,
                        'in_create' => true,
                        'in_edit' => true,
                        'nullable' => false,
                        'tooltip' => null,
                        'system' => true,
                        'enabled' => true,
                        'order' => 2,
                    ]),
                    new CrudField([
                        'type' => CrudFieldTypes::TEXT,
                        'key' => str('Name')->lower()
                            ->snake()
                            ->toString(),
                        'label' => 'Name',
                        'validation' => 'required',
                        'in_list' => true,
                        'in_show' => true,
                        'in_create' => true,
                        'in_edit' => true,
                        'nullable' => false,
                        'tooltip' => null,
                        'system' => true,
                        'enabled' => true,
                        'order' => 2,
                    ]),
                    new CrudField([
                        'type' => CrudFieldTypes::IMAGE,
                        'key' => str('Photos')->lower()
                            ->snake()
                            ->toString(),
                        'label' => 'Photos',
                        'validation' => 'required',
                        'in_list' => true,
                        'in_show' => true,
                        'in_create' => true,
                        'in_edit' => true,
                        'nullable' => false,
                        'tooltip' => null,
                        'system' => true,
                        'enabled' => true,
                        'order' => 2,
                    ]),
                    (new CrudField([
                        'type' => CrudFieldTypes::BELONGS_TO,
                        'key' => str('Status')->lower()
                            ->snake()
                            ->toString(),
                        'label' => 'Status',
                        'validation' => 'required',
                        'in_list' => true,
                        'in_show' => true,
                        'in_create' => true,
                        'in_edit' => true,
                        'nullable' => false,
                        'tooltip' => null,
                        'system' => true,
                        'enabled' => true,
                        'order' => 2,
                    ]))
                        ->setRelation('crudFieldOptions', new CrudFieldOptions([
                            'crud_id' => 'AssetStatus',
                            'related_crud_field_id' => 'name',
                        ])),
                    (new CrudField([
                        'type' => CrudFieldTypes::BELONGS_TO,
                        'key' => str('Location')->lower()
                            ->snake()
                            ->toString(),
                        'label' => 'Location',
                        'validation' => 'required',
                        'in_list' => true,
                        'in_show' => true,
                        'in_create' => true,
                        'in_edit' => true,
                        'nullable' => false,
                        'tooltip' => null,
                        'system' => true,
                        'enabled' => true,
                        'order' => 2,
                    ]))
                        ->setRelation('crudFieldOptions', new CrudFieldOptions([
                            'crud_id' => 'AssetLocation',
                            'related_crud_field_id' => 'name',
                        ])),
                    new CrudField([
                        'type' => CrudFieldTypes::TEXTAREA,
                        'key' => 'notes',
                        'label' => 'Notes',
                        'validation' => 'required',
                        'in_list' => true,
                        'in_show' => true,
                        'in_create' => true,
                        'in_edit' => true,
                        'nullable' => false,
                        'tooltip' => null,
                        'system' => true,
                        'enabled' => true,
                        'order' => 2,
                    ]),
                    (new CrudField([
                        'type' => CrudFieldTypes::BELONGS_TO,
                        'key' => str('User')->lower()
                            ->snake()
                            ->toString(),
                        'label' => 'Assigned To',
                        'validation' => 'required',
                        'in_list' => true,
                        'in_show' => true,
                        'in_create' => true,
                        'in_edit' => true,
                        'nullable' => false,
                        'tooltip' => null,
                        'system' => true,
                        'enabled' => true,
                        'order' => 2,
                    ]))
                        ->setRelation('crudFieldOptions', new CrudFieldOptions([
                            'crud_id' => 'User',
                            'related_crud_field_id' => 'name',
                        ])),
                    $this->getCreatedAtField(3),
                    $this->getUpdatedAtField(4),
                    $this->getDeletedAtField(5),
                ]
                ),
            (new Crud([
                // TODO: This is a special model since it required observer on Assets CRUD
                'parent_id' => str('Asset Management')->singular()->studly(),
                'type' => CrudTypes::CRUD,
                'title' => str('AssetHistory')->singular()->studly(),
                'visual_title' => 'AssetHistory',
                'icon' => '',
                'menu_order' => 1,
                'is_hidden' => false,
                'module_crud' => true,
                'module_slug' => $this->slug,
                'system' => true,
            ]))
                ->setRelation('fields', [
                    $this->getIDField(),
                    (new CrudField([
                        'type' => CrudFieldTypes::BELONGS_TO,
                        'key' => str('Asset')->lower()
                            ->snake()
                            ->toString(),
                        'label' => 'Asset',
                        'validation' => 'required',
                        'in_list' => true,
                        'in_show' => true,
                        'in_create' => true,
                        'in_edit' => true,
                        'nullable' => false,
                        'tooltip' => null,
                        'system' => true,
                        'enabled' => true,
                        'order' => 2,
                    ]))
                        ->setRelation('crudFieldOptions', new CrudFieldOptions([
                            'crud_id' => 'Asset',
                            'related_crud_field_id' => 'name',
                        ])),
                    (new CrudField([
                        'type' => CrudFieldTypes::BELONGS_TO,
                        'key' => str('Status')->lower()
                            ->snake()
                            ->toString(),
                        'label' => 'Status',
                        'validation' => 'required',
                        'in_list' => true,
                        'in_show' => true,
                        'in_create' => true,
                        'in_edit' => true,
                        'nullable' => false,
                        'tooltip' => null,
                        'system' => true,
                        'enabled' => true,
                        'order' => 2,
                    ]))
                        ->setRelation('crudFieldOptions', new CrudFieldOptions([
                            'crud_id' => 'AssetStatus',
                            'related_crud_field_id' => 'name',
                        ])),
                    (new CrudField([
                        'type' => CrudFieldTypes::BELONGS_TO,
                        'key' => str('Location')->lower()
                            ->snake()
                            ->toString(),
                        'label' => 'Location',
                        'validation' => 'required',
                        'in_list' => true,
                        'in_show' => true,
                        'in_create' => true,
                        'in_edit' => true,
                        'nullable' => false,
                        'tooltip' => null,
                        'system' => true,
                        'enabled' => true,
                        'order' => 2,
                    ]))
                        ->setRelation('crudFieldOptions', new CrudFieldOptions([
                            'crud_id' => 'AssetLocation',
                            'related_crud_field_id' => 'name',
                        ])),
                    (new CrudField([
                        'type' => CrudFieldTypes::BELONGS_TO,
                        'key' => str('User')->lower()
                            ->snake()
                            ->toString(),
                        'label' => 'User',
                        'validation' => 'required',
                        'in_list' => true,
                        'in_show' => true,
                        'in_create' => true,
                        'in_edit' => true,
                        'nullable' => false,
                        'tooltip' => null,
                        'system' => true,
                        'enabled' => true,
                        'order' => 2,
                    ]))
                        ->setRelation('crudFieldOptions', new CrudFieldOptions([
                            'crud_id' => 'User',
                            'related_crud_field_id' => 'name',
                        ])),
                    $this->getCreatedAtField(3),
                    $this->getUpdatedAtField(4),
                    $this->getDeletedAtField(5),
                ]
                ),
        ];
    }
}
