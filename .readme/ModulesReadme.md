A quick overview of how Modules work.

## What is a Module?

In this system, a module is a set of pre-defined CRUD details. For example, a module can contain more information for a `User` CRUD.

This module will have the following details:

- Unlimited amount of CRUDs inside
  - Each CRUD will have its own details
  - Each CRUD will have its own fields
    - Each field will have its own details
    
These modules are quickly installed using `app/Interfaces/ModuleBase.php` methods `install()` and `uninstall()`.

Each module, if needed, can override those methods.

---

## How to create a Module?

To create a module, you have a few options:

1. Start from scratch
2. Copy one of the existing modules and modify it

In both cases, the result should be the same:

1. A file in the `systems/generators/filament3/src/Modules` folder
2. A-line with unique `slug` in `systems/generators/filament3/src/Modules/ModuleManager.php.`

Now, each module should have an implementation of the `getCruds()` method. This method should return an array of CRUDs. For example, take from `BaseModule`:

```php
public function getCruds(): array
{
    return [
        (new Crud([
            'type' => CrudTypes::PARENT,
            'title' => str('User Management')->singular()->studly(),
            'visual_title' => 'User Management',
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
    ];
}
```

Everything is done via new Module instances. We don't save them to the database; we use them as DTOs.

---

## Installing / Removing a Module

Installation/Removal is handled automatically in the system. It is done via:

```php
ModuleService::getModuleClass(App\Models\Panel, 'module-slug-goes-here')
    ->install(App\Models\Panel);
```

And:

```php
ModuleService::getModuleClass(App\Models\Panel, 'module-slug-goes-here')
    ->uninstall(App\Models\Panel);
```

Both methods accept a `Panel` (the current admin panel) as a parameter and a module SLUG.

---