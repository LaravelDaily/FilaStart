<?php

use App\Enums\CrudFieldTypes;
use App\Models\CrudField;
use Generators\Filament3\Generators\Fields\RetrieveGeneratorForField;

test('image field can be displayed in form with basic data', function () {
    $field = new CrudField([
        'key' => 'name',
        'label' => 'Name',
        'type' => CrudFieldTypes::IMAGE,
        'in_create' => true,
        'in_edit' => true,
    ]);

    $generator = RetrieveGeneratorForField::for($field);
    $output = $generator->formComponent();

    expect($output)
        ->toBeString()
        ->toContain('FileUpload::make(\'name\')');
});

test('image field can be hidden on edit', function () {
    $field = new CrudField([
        'key' => 'name',
        'label' => 'Name',
        'type' => CrudFieldTypes::IMAGE,
        'in_create' => true,
        'in_edit' => false,
    ]);

    $generator = RetrieveGeneratorForField::for($field);
    $output = $generator->formComponent();

    expect($output)
        ->toBeString()
        ->toContain('->hiddenOn(\'edit\')');
});

test('image field can be hidden on create', function () {
    $field = new CrudField([
        'key' => 'name',
        'label' => 'Name',
        'type' => CrudFieldTypes::IMAGE,
        'in_create' => false,
        'in_edit' => true,
    ]);

    $generator = RetrieveGeneratorForField::for($field);
    $output = $generator->formComponent();

    expect($output)
        ->toBeString()
        ->toContain('->hiddenOn(\'create\')');
});

test('image field can be required', function () {
    $field = new CrudField([
        'key' => 'name',
        'label' => 'Name',
        'type' => CrudFieldTypes::IMAGE,
        'in_create' => true,
        'in_edit' => true,
        'validation' => 'required',
    ]);

    $generator = RetrieveGeneratorForField::for($field);
    $output = $generator->formComponent();

    expect($output)
        ->toBeString()
        ->toContain('->required(true)');
});

test('image field can have placeholder', function () {
    $field = new CrudField([
        'key' => 'name',
        'label' => 'Name',
        'type' => CrudFieldTypes::IMAGE,
        'in_create' => true,
        'in_edit' => true,
        'tooltip' => 'Upload avatar here',
    ]);

    $generator = RetrieveGeneratorForField::for($field);
    $output = $generator->formComponent();

    expect($output)
        ->toBeString()
        ->toContain('->placeholder(\'Upload avatar here\')');
});

test('image field can have various names', function ($key, $label, $expected) {
    $field = new CrudField([
        'key' => $key,
        'label' => $label,
        'type' => CrudFieldTypes::IMAGE,
        'in_create' => true,
        'in_edit' => true,
    ]);

    $generator = RetrieveGeneratorForField::for($field);
    $output = $generator->formComponent();

    expect($output)
        ->toBeString()
        ->toContain('FileUpload::make(\''.$expected.'\')');
})->with([
    [
        'first_name',
        'First Name',
        'first_name',
    ],
    [
        'randomString',
        'Random String',
        'randomstring',
    ],
    [ // We expect the key to not be mutated from the CRUD
        'some gaps',
        'Some Gaps',
        'some_gaps',
    ],
    [
        'related.dot',
        'Related Dot',
        'related.dot',
    ],
]);

test('image field cant be displayed on a table', function () {
    $field = new CrudField([
        'key' => 'name',
        'label' => 'Name',
        'type' => CrudFieldTypes::IMAGE,
        'in_list' => true,
    ]);

    $generator = RetrieveGeneratorForField::for($field);
    $output = $generator->tableColumn();

    expect($output)
        ->toBeString()
        ->toContain('ImageColumn::make(\'name\')');
});
