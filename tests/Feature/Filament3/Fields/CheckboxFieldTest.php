<?php

use App\Enums\CrudFieldTypes;
use App\Models\CrudField;
use Generators\Filament3\Generators\Fields\RetrieveGeneratorForField;

test('checkbox field can be displayed in form with basic data', function () {
    $field = new CrudField([
        'key' => 'name',
        'label' => 'Name',
        'type' => CrudFieldTypes::CHECKBOX,
        'in_create' => true,
        'in_edit' => true,
    ]);

    $generator = RetrieveGeneratorForField::for($field);
    $output = $generator->formComponent();

    expect($output)
        ->toBeString()
        ->toContain('Checkbox::make(\'name\')');
});

test('checkbox field can be hidden on edit', function () {
    $field = new CrudField([
        'key' => 'name',
        'label' => 'Name',
        'type' => CrudFieldTypes::CHECKBOX,
        'in_create' => true,
        'in_edit' => false,
    ]);

    $generator = RetrieveGeneratorForField::for($field);
    $output = $generator->formComponent();

    expect($output)
        ->toBeString()
        ->toContain('->hiddenOn(\'edit\')');
});

test('checkbox field can be hidden on create', function () {
    $field = new CrudField([
        'key' => 'name',
        'label' => 'Name',
        'type' => CrudFieldTypes::CHECKBOX,
        'in_create' => false,
        'in_edit' => true,
    ]);

    $generator = RetrieveGeneratorForField::for($field);
    $output = $generator->formComponent();

    expect($output)
        ->toBeString()
        ->toContain('->hiddenOn(\'create\')');
});

test('checkbox field can be required', function () {
    $field = new CrudField([
        'key' => 'name',
        'label' => 'Name',
        'type' => CrudFieldTypes::CHECKBOX,
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

test('checkbox field can have hint', function () {
    $field = new CrudField([
        'key' => 'name',
        'label' => 'Name',
        'type' => CrudFieldTypes::CHECKBOX,
        'in_create' => true,
        'in_edit' => true,
        'tooltip' => 'Enter your name',
    ]);

    $generator = RetrieveGeneratorForField::for($field);
    $output = $generator->formComponent();

    expect($output)
        ->toBeString()
        ->toContain('->hint(\'Enter your name\')');
});

test('checkbox field can have various names', function ($key, $label, $expected) {
    $field = new CrudField([
        'key' => $key,
        'label' => $label,
        'type' => CrudFieldTypes::CHECKBOX,
        'in_create' => true,
        'in_edit' => true,
    ]);

    $generator = RetrieveGeneratorForField::for($field);
    $output = $generator->formComponent();

    expect($output)
        ->toBeString()
        ->toContain('Checkbox::make(\''.$expected.'\')');
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

test('checkbox field can be displayed on a table', function () {
    $field = new CrudField([
        'key' => 'name',
        'label' => 'Name',
        'type' => CrudFieldTypes::CHECKBOX,
        'in_list' => true,
    ]);

    $generator = RetrieveGeneratorForField::for($field);
    $output = $generator->tableColumn();

    expect($output)
        ->toBeString()
        ->toContain('CheckboxColumn::make(\'name\')');
});

test('checkbox field can be displayed on a table with various names', function ($key, $label, $expected) {
    $field = new CrudField([
        'key' => $key,
        'label' => $label,
        'type' => CrudFieldTypes::CHECKBOX,
        'in_list' => true,
    ]);

    $generator = RetrieveGeneratorForField::for($field);
    $output = $generator->tableColumn();

    expect($output)
        ->toBeString()
        ->toContain('CheckboxColumn::make(\''.$expected.'\')');
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
    [
        'Random Title',
        'Related Dot',
        'random_title',
    ],
]);
