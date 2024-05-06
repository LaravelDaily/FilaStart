<?php

namespace Generators\Filament3\Modules;

use App\Enums\CrudFieldTypes;
use App\Enums\CrudTypes;
use App\Interfaces\ModuleBase;
use App\Models\Crud;
use App\Models\CrudField;
use App\Models\CrudFieldOptions;

class BaseModule extends ModuleBase
{
    public string $slug = 'base-module';

    /**
     * @return Crud[]
     */
    public function getCruds(): array
    {
        return [
            (new Crud([
                'type' => CrudTypes::PARENT,
                'title' => str('User Management')->singular()->studly(),
                'visual_title' => 'Users',
                'icon' => 'heroicon-o-users',
                'menu_order' => 1,
                'is_hidden' => false,
                'module_crud' => true,
                'system' => true,
            ])),
            (new Crud([
                'parent_id' => str('User Management')->singular()->studly(),
                'type' => CrudTypes::CRUD,
                'title' => str('Permissions')->singular()->studly(),
                'visual_title' => 'Permissions',
                'icon' => '',
                'menu_order' => 1,
                'is_hidden' => false,
                'module_crud' => true,
                'system' => true,
            ]))
                ->setRelation('fields', [
                    $this->getIDField(),
                    new CrudField([
                        'type' => CrudFieldTypes::TEXT,
                        'key' => str('Title')->lower()
                            ->snake()
                            ->toString(),
                        'label' => 'Title',
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
                'parent_id' => str('User Management')->singular()->studly(),
                'type' => CrudTypes::CRUD,
                'title' => str('Roles')->singular()->studly(),
                'visual_title' => 'Roles',
                'icon' => '',
                'menu_order' => 2,
                'is_hidden' => false,
                'module_crud' => true,
                'system' => true,
            ]))
                ->setRelation('fields', [
                    $this->getIDField(),
                    new CrudField([
                        'type' => CrudFieldTypes::TEXT,
                        'key' => str('Title')->lower()
                            ->snake()
                            ->toString(),
                        'label' => 'Title',
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
                        'type' => CrudFieldTypes::BELONGS_TO_MANY,
                        'key' => str('Permissions')->lower()
                            ->snake()
                            ->toString(),
                        'label' => 'Permissions',
                        'validation' => 'required',
                        'in_list' => true,
                        'in_show' => true,
                        'in_create' => true,
                        'in_edit' => true,
                        'nullable' => false,
                        'tooltip' => null,
                        'system' => true,
                        'enabled' => true,
                        'order' => 3,
                    ]))
                        ->setRelation('crudFieldOptions', new CrudFieldOptions([
                            'crud_id' => 'Permission',
                            'related_crud_field_id' => 'title',
                            'relationship' => 'permissions',
                        ])
                        ),
                    $this->getCreatedAtField(4),
                    $this->getUpdatedAtField(5),
                    $this->getDeletedAtField(6),
                ]
                ),
            (new Crud([
                'parent_id' => str('User Management')->singular()->studly(),
                'type' => CrudTypes::CRUD,
                'title' => str('Users')->singular()->studly(),
                'visual_title' => 'Users',
                'icon' => '',
                'menu_order' => 3,
                'is_hidden' => false,
                'module_crud' => true,
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
                    new CrudField([
                        'type' => CrudFieldTypes::TEXT,
                        'key' => str('Email')->lower()
                            ->snake()
                            ->toString(),
                        'label' => 'Email',
                        'validation' => 'required',
                        'in_list' => true,
                        'in_show' => true,
                        'in_create' => true,
                        'in_edit' => true,
                        'nullable' => false,
                        'tooltip' => null,
                        'system' => true,
                        'enabled' => true,
                        'order' => 3,
                    ]),
                    new CrudField([
                        'type' => CrudFieldTypes::DATE_TIME,
                        'key' => str('Email Verified At')->lower()
                            ->snake()
                            ->toString(),
                        'label' => 'Email Verified At',
                        'validation' => 'optional',
                        'in_list' => false,
                        'in_show' => false,
                        'in_create' => false,
                        'in_edit' => false,
                        'nullable' => true,
                        'tooltip' => null,
                        'system' => true,
                        'enabled' => true,
                        'order' => 4,
                    ]),
                    new CrudField([
                        'type' => CrudFieldTypes::PASSWORD,
                        'key' => str('Password')->lower()
                            ->snake()
                            ->toString(),
                        'label' => 'Password',
                        'validation' => 'required',
                        'in_list' => false,
                        'in_show' => false,
                        'in_create' => true,
                        'in_edit' => false,
                        'nullable' => false,
                        'tooltip' => null,
                        'system' => true,
                        'enabled' => true,
                        'order' => 5,
                    ]),
                    new CrudField([
                        'type' => CrudFieldTypes::TEXT,
                        'key' => str('Remember Token')->lower()
                            ->snake()
                            ->toString(),
                        'label' => 'Remember Token',
                        'validation' => 'optional',
                        'in_list' => false,
                        'in_show' => false,
                        'in_create' => false,
                        'in_edit' => false,
                        'nullable' => true,
                        'tooltip' => null,
                        'system' => true,
                        'enabled' => true,
                        'order' => 6,
                    ]),
                    (new CrudField([
                        'type' => CrudFieldTypes::BELONGS_TO_MANY,
                        'key' => str('Roles')->lower()
                            ->snake()
                            ->toString(),
                        'label' => 'Roles',
                        'validation' => 'required',
                        'in_list' => true,
                        'in_show' => true,
                        'in_create' => true,
                        'in_edit' => true,
                        'nullable' => false,
                        'tooltip' => null,
                        'system' => true,
                        'enabled' => true,
                        'order' => 7,
                    ]))
                        ->setRelation('crudFieldOptions', new CrudFieldOptions([
                            'crud_id' => 'Role',
                            'related_crud_field_id' => 'title',
                            'relationship' => 'roles',
                        ])
                        ),

                    $this->getCreatedAtField(8),
                    $this->getUpdatedAtField(9),
                    $this->getDeletedAtField(10),
                ]
                ),
        ];
    }
}
