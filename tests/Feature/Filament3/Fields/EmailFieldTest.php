<?php

use App\Enums\CrudFieldTypes;
use App\Models\CrudField;
use Generators\Filament3\Generators\Fields\RetrieveGeneratorForField;

test('email field can be displayed in form with basic data', function () {
    $field = new CrudField([
        'key' => 'name',
        'label' => 'Name',
        'type' => CrudFieldTypes::EMAIL,
        'in_create' => true,
        'in_edit' => true,
    ]);

    $generator = RetrieveGeneratorForField::for($field);
    $output = $generator->formComponent();

    expect($output)
        ->toBeString()
        ->toContain('TextInput::make(\'name\')')
        ->toContain('->email(true)');
});

test('email field can be hidden on edit', function () {
    $field = new CrudField([
        'key' => 'name',
        'label' => 'Name',
        'type' => CrudFieldTypes::EMAIL,
        'in_create' => true,
        'in_edit' => false,
    ]);

    $generator = RetrieveGeneratorForField::for($field);
    $output = $generator->formComponent();

    expect($output)
        ->toBeString()
        ->toContain('->hiddenOn(\'edit\')')
        ->toContain('->email(true)');
});

test('email field can be hidden on create', function () {
    $field = new CrudField([
        'key' => 'name',
        'label' => 'Name',
        'type' => CrudFieldTypes::EMAIL,
        'in_create' => false,
        'in_edit' => true,
    ]);

    $generator = RetrieveGeneratorForField::for($field);
    $output = $generator->formComponent();

    expect($output)
        ->toBeString()
        ->toContain('->hiddenOn(\'create\')')
        ->toContain('->email(true)');
});

test('email field can be required', function () {
    $field = new CrudField([
        'key' => 'name',
        'label' => 'Name',
        'type' => CrudFieldTypes::EMAIL,
        'in_create' => true,
        'in_edit' => true,
        'validation' => 'required',
    ]);

    $generator = RetrieveGeneratorForField::for($field);
    $output = $generator->formComponent();

    expect($output)
        ->toBeString()
        ->toContain('->required(true)')
        ->toContain('->email(true)');
});

test('email field can have placeholder', function () {
    $field = new CrudField([
        'key' => 'name',
        'label' => 'Name',
        'type' => CrudFieldTypes::EMAIL,
        'in_create' => true,
        'in_edit' => true,
        'tooltip' => 'Enter your email',
    ]);

    $generator = RetrieveGeneratorForField::for($field);
    $output = $generator->formComponent();

    expect($output)
        ->toBeString()
        ->toContain('->placeholder(\'Enter your email\')')
        ->toContain('->email(true)');
});

test('email field can have various names', function ($key, $label, $expected) {
    $field = new CrudField([
        'key' => $key,
        'label' => $label,
        'type' => CrudFieldTypes::EMAIL,
        'in_create' => true,
        'in_edit' => true,
    ]);

    $generator = RetrieveGeneratorForField::for($field);
    $output = $generator->formComponent();

    expect($output)
        ->toBeString()
        ->toContain('TextInput::make(\''.$expected.'\')')
        ->toContain('->email(true)');
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

test('email field can be displayed on a table', function () {
    $field = new CrudField([
        'key' => 'name',
        'label' => 'Name',
        'type' => CrudFieldTypes::EMAIL,
        'in_list' => true,
    ]);

    $generator = RetrieveGeneratorForField::for($field);
    $output = $generator->tableColumn();

    expect($output)
        ->toBeString()
        ->toContain('TextColumn::make(\'name\')');
});

test('email field can be displayed on a table with various names', function ($key, $label, $expected) {
    $field = new CrudField([
        'key' => $key,
        'label' => $label,
        'type' => CrudFieldTypes::EMAIL,
        'in_list' => true,
    ]);

    $generator = RetrieveGeneratorForField::for($field);
    $output = $generator->tableColumn();

    expect($output)
        ->toBeString()
        ->toContain('TextColumn::make(\''.$expected.'\')');
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
