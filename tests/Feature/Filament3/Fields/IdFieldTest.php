<?php

use App\Enums\CrudFieldTypes;
use App\Models\CrudField;
use Generators\Filament3\Generators\Fields\RetrieveGeneratorForField;

test('id field cant be displayed in form', function () {
    $field = new CrudField([
        'key' => 'id',
        'label' => 'ID',
        'type' => CrudFieldTypes::ID,
        'in_create' => true,
        'in_edit' => true,
    ]);

    $generator = RetrieveGeneratorForField::for($field);
    $output = $generator->formComponent();

    expect($output)
        ->toBeString()
        ->toEqual('');
});

test('id field can be required', function () {
    $field = new CrudField([
        'key' => 'id',
        'label' => 'ID',
        'type' => CrudFieldTypes::ID,
        'in_create' => true,
        'in_edit' => true,
        'validation' => 'required',
    ]);

    $generator = RetrieveGeneratorForField::for($field);
    $output = $generator->formComponent();

    expect($output)
        ->toBeString()
        ->toEqual('');
});

test('id field can be displayed on a table', function () {
    $field = new CrudField([
        'key' => 'id',
        'label' => 'ID',
        'type' => CrudFieldTypes::ID,
        'in_list' => true,
    ]);

    $generator = RetrieveGeneratorForField::for($field);
    $output = $generator->tableColumn();

    expect($output)
        ->toBeString()
        ->toContain('TextColumn::make(\'id\')');
});
