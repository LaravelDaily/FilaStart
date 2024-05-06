<?php

use App\Enums\CrudFieldTypes;
use App\Models\Crud;
use App\Models\CrudField;
use Generators\Filament3\Generators\Files\FileReplacements;

it('generates correct list file', function ($model) {
    $crud = (new Crud([
        'title' => $model,
    ]))
        ->setRelation('fields', [
            new CrudField([
                'key' => 'name',
                'label' => 'Name',
                'type' => CrudFieldTypes::TEXT,
                'in_create' => true,
                'in_edit' => true,
                'in_list' => true,
            ]),
        ]);

    $generator = new FileReplacements($crud);

    $resource = $generator->retrieveFileGenerator('list');

    $resource->setReplacements($generator->getReplacementsForListPage());

    $model = str($model)->studly();

    expect($resource->generate())
        ->toContain('App\\Filament\\Resources\\'.$model.'Resource\\Pages;')
        ->toContain("class List{$model}s extends ListRecord")
        ->toContain("protected static string \$resource = {$model}Resource::class;")
        ->toContain("Actions\CreateAction::make(),");
})->with([
    'Post',
    'Item',
    'Country Type',
    'Country_type',
]);
