<?php

use App\Enums\CrudFieldTypes;
use App\Models\Crud;
use App\Models\CrudField;
use Generators\Filament3\Generators\Files\FileReplacements;

it('generates correct resource file', function ($model, $field) {
    $crud = (new Crud([
        'title' => $model,
    ]))
        ->setRelation('fields', [
            new CrudField([
                'key' => $field,
                'label' => str($field)->ucfirst(),
                'type' => CrudFieldTypes::TEXT,
                'in_create' => true,
                'in_edit' => true,
                'in_list' => true,
            ]),
        ]);

    $generator = new FileReplacements($crud);

    $resource = $generator->retrieveFileGenerator('resource');

    $resource->setReplacements($generator->getReplacementsForResource());

    $model = str($model)->studly();

    expect($resource->generate())
        ->toContain('namespace App\\Filament\\Resources;')
        ->toContain("use App\\Models\\{$model};")
        ->toContain("class {$model}Resource extends Resource")
        ->toContain("protected static ?string \$model = {$model}::class;")
        ->toContain("Forms\Components\TextInput::make('{$field}')")
        ->toContain("TextInput::make('{$field}')")
        ->not->toContain('protected static ?string $navigationIcon');
})->with([
    [
        'Post',
        'title',
    ],
    [
        'Item',
        'name',
    ],
    [
        'Country Type',
        'name',
    ],
    [
        'Country_type',
        'name',
    ],
]);

it('can assign icon to the resource', function ($model, $field) {
    $crud = (new Crud([
        'title' => $model,
        'icon' => \App\Enums\HeroIcons::O_USERS,
    ]))
        ->setRelation('fields', [
            new CrudField([
                'key' => $field,
                'label' => str($field)->ucfirst(),
                'type' => CrudFieldTypes::TEXT,
                'in_create' => true,
                'in_edit' => true,
                'in_list' => true,
            ]),
        ]);

    $generator = new FileReplacements($crud);

    $resource = $generator->retrieveFileGenerator('resource');

    $resource->setReplacements($generator->getReplacementsForResource());

    expect($resource->generate())
        ->toContain('protected static ?string $navigationIcon = \''.\App\Enums\HeroIcons::O_USERS->value.'\'');
})->with([
    [
        'Post',
        'title',
    ],
    [
        'Item',
        'name',
    ],
    [
        'Country Type',
        'name',
    ],
    [
        'Country_type',
        'name',
    ],
]);
