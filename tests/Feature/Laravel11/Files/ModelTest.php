<?php

use App\Enums\CrudFieldTypes;
use App\Models\Crud;
use App\Models\CrudField;
use App\Models\CrudFieldOptions;
use Generators\Laravel11\Generators\ModelGenerator;

it('can create models', function ($crudName, $modelName) {
    $crud = (new Crud([
        'title' => $crudName,
    ]))
        ->setRelation('fields', collect([
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
        ]));

    $model = new ModelGenerator($crud);

    expect($model->getName())
        ->toEqual($modelName)
        ->and($model->generate())
        ->toContain('$fillable = ['.PHP_EOL.'        \'name\''.PHP_EOL.'    ];');

})->with([
    [
        'Test',
        'Test',
    ],
    [
        'Companies',
        'Company',
    ],
    [
        'Country Types',
        'CountryType',
    ],
    [
        'Country_types',
        'CountryType',
    ],
]);

it('can create models with a lot of fillable fields', function () {
    $crud = (new Crud([
        'title' => 'Test',
    ]))
        ->setRelation('fields', collect([
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
            new CrudField([
                'key' => 'email',
                'label' => 'Email',
                'type' => CrudFieldTypes::EMAIL,
                'in_create' => true,
                'in_edit' => true,
            ]),
            new CrudField([
                'key' => 'completed_at',
                'label' => 'Completed at',
                'type' => CrudFieldTypes::DATE_TIME,
                'in_create' => true,
                'in_edit' => true,
            ]),
            new CrudField([
                'key' => 'description',
                'label' => 'Description',
                'type' => CrudFieldTypes::TEXTAREA,
                'in_create' => true,
                'in_edit' => true,
            ]),
            (new CrudField([
                'key' => 'role',
                'label' => 'Role',
                'type' => CrudFieldTypes::BELONGS_TO,
                'in_create' => true,
                'in_edit' => true,
            ]))
                ->setRelation('crud', new Crud([
                    'title' => 'Roles',
                ]))
                ->setRelation('crudFieldOptions', (new CrudFieldOptions([
                    'crud_id' => 1,
                    'related_crud_field_id' => 1,
                    'relationship' => 'roles',
                ]))
                    ->setRelation('crud', new Crud([
                        'title' => 'Roles',
                    ]))
                ),
        ]));

    $model = new ModelGenerator($crud);

    expect($model->generate())
        ->toContain('\'name\'')
        ->toContain('\'role_id\'')
        ->toContain('\'email\'')
        ->toContain('\'completed_at\'')
        ->toContain('\'description\'');

});

it('can create models with belongsTo relationships', function ($title, $label, $modelName, $key, $relationship) {
    $crud = (new Crud([
        'title' => 'Test',
    ]))
        ->setRelation('fields', collect([
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
                    'title' => $title,
                ]))
                ->setRelation('crudFieldOptions', (new CrudFieldOptions([
                    'crud_id' => 1,
                    'related_crud_field_id' => 1,
                    'relationship' => $relationship,
                ]))
                    ->setRelation('crud', new Crud([
                        'title' => $title,
                    ]))
                ),
        ]));

    $model = new ModelGenerator($crud);

    expect($model->generate())
        ->toContain("public function {$relationship}()")
        ->toContain("return \$this->belongsTo({$modelName}::class);");
})->with([
    [
        'Roles',
        'Role',
        'Role',
        'role_id',
        'roles',
    ],
    [
        'Country Types',
        'Country Type',
        'CountryType',
        'country_type_id',
        'countryTypes',
    ],
]);

it('can create models with belongsToMany relationships', function ($title, $label, $modelName, $key, $relationship) {
    $crud = (new Crud([
        'title' => 'Test',
    ]))
        ->setRelation('fields', collect([
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
                    'title' => $title,
                ]))
                ->setRelation('crudFieldOptions', (new CrudFieldOptions([
                    'crud_id' => 1,
                    'related_crud_field_id' => 1,
                    'relationship' => $relationship,
                ]))
                    ->setRelation('crud', new Crud([
                        'title' => $title,
                    ]))
                ),
        ]));

    $model = new ModelGenerator($crud);

    expect($model->generate())
        ->toContain("public function {$relationship}()")
        ->toContain("return \$this->belongsToMany({$modelName}::class);");
})->with([
    [
        'Roles',
        'Role',
        'Role',
        'role_id',
        'roles',
    ],
    [
        'Country Types',
        'Country Type',
        'CountryType',
        'country_type_id',
        'countryTypes',
    ],
]);
