<?php

use App\Enums\CrudFieldTypes;
use App\Models\Crud;
use App\Models\CrudField;
use App\Models\CrudFieldOptions;
use Generators\Laravel11\Generators\MigrationGenerator;

it('can create migrations', function () {
    $crud = (new Crud([
        'title' => 'Test',
    ]))
        ->setRelation('fields', [
            new CrudField([
                'key' => 'id',
                'label' => 'ID',
                'type' => CrudFieldTypes::ID,
                'in_create' => true,
                'in_edit' => true,
            ]),
            new CrudField([
                'key' => 'name',
                'label' => 'Name',
                'type' => CrudFieldTypes::TEXT,
                'in_create' => true,
                'in_edit' => true,
            ]),
        ]);

    $migration = new MigrationGenerator($crud);

    expect($migration->generate())
        ->toContain('Schema::create(\'tests\'')
        ->toContain('$table->id();')
        ->toContain('$table->string(\'name\');')
        ->toContain('$table->timestamps();')
        ->toContain('$table->softDeletes();');
});

it('can generate belongs to migration', function ($title, $label, $modelName, $key, $relationship, $tableName) {
    $crud = (new Crud([
        'title' => 'User',
    ]))
        ->setRelation('fields', [
            new CrudField([
                'key' => 'id',
                'label' => 'ID',
                'type' => CrudFieldTypes::ID,
                'in_create' => true,
                'in_edit' => true,
            ]),
            (new CrudField([
                'key' => $key,
                'label' => $label,
                'type' => CrudFieldTypes::BELONGS_TO,
                'in_create' => true,
                'in_edit' => true,
            ]))
                ->setRelation('crud', new Crud([
                    'title' => 'User',
                ]))
                ->setRelation('crudFieldOptions', (new CrudFieldOptions([
                    'crud_id' => 1,
                    'related_crud_field_id' => 1,
                    'relationship' => $relationship,
                ]))
                    ->setRelation('relatedCrudField',
                        new CrudField([
                            'id' => 1,
                            'key' => $key,
                            'label' => $label,
                            'type' => CrudFieldTypes::TEXT,
                            'in_create' => true,
                            'in_edit' => true,
                        ]))
                    ->setRelation('crud', (new Crud([
                        'title' => $title,
                    ]))
                        ->setRelation('fields', [
                            new CrudField([
                                'id' => 1,
                                'key' => 'name',
                                'label' => 'Name',
                                'type' => CrudFieldTypes::TEXT,
                                'in_create' => true,
                                'in_edit' => true,
                            ]),
                        ]))
                ),
        ]);

    $migration = new MigrationGenerator($crud);

    expect($migration->generate())
        ->toBeString()
        ->toContain("\$table->foreignId('{$key}')->constrained('{$tableName}')");
})->with([
    [
        'Roles',
        'Role',
        'Role',
        'role_id',
        'roles',
        'roles',
    ],
    [
        'Country Types',
        'Country Type',
        'CountryType',
        'country_type_id',
        'countryTypes',
        'country_types',
    ],
]);

it('can create pivot migrations', function ($title, $label, $modelName, $key, $relationship, $tableName) {
    $crud = (new Crud([
        'title' => 'User',
    ]))
        ->setRelation('fields', [
            new CrudField([
                'key' => 'id',
                'label' => 'ID',
                'type' => CrudFieldTypes::ID,
                'in_create' => true,
                'in_edit' => true,
            ]),
            (new CrudField([
                'key' => $key,
                'label' => $label,
                'type' => CrudFieldTypes::BELONGS_TO_MANY,
                'in_create' => true,
                'in_edit' => true,
            ]))
                ->setRelation('crud', new Crud([
                    'title' => 'User',
                ]))
                ->setRelation('crudFieldOptions', (new CrudFieldOptions([
                    'crud_id' => 1,
                    'related_crud_field_id' => 1,
                    'relationship' => $relationship,
                ]))
                    ->setRelation('relatedCrudField',
                        new CrudField([
                            'id' => 1,
                            'key' => $key,
                            'label' => $label,
                            'type' => CrudFieldTypes::TEXT,
                            'in_create' => true,
                            'in_edit' => true,
                        ]))
                    ->setRelation('crud', (new Crud([
                        'title' => $modelName,
                    ]))
                        ->setRelation('fields', [
                            new CrudField([
                                'id' => 1,
                                'key' => $key,
                                'label' => $label,
                                'type' => CrudFieldTypes::TEXT,
                                'in_create' => true,
                                'in_edit' => true,
                            ]),
                        ]))
                ),
        ]);

    $migration = new MigrationGenerator($crud);

    expect($migration->generateManyToMany($crud, $crud->fields[1]))
        ->toContain('Schema::create(\''.str($tableName)->singular().'_user\'')
        ->toContain('$table->id();')
        ->toContain("\$table->foreignId('user_id')->constrained('users');")
        ->toContain("\$table->foreignId('{$key}')->constrained('{$tableName}');");
})->with([
    [
        'Roles',
        'Role',
        'Role',
        'role_id',
        'roles',
        'roles',
    ],
    [
        'Country Types',
        'Country Type',
        'CountryType',
        'country_type_id',
        'countryTypes',
        'country_types',
    ],
]);
