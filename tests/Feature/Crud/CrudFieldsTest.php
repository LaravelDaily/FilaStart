<?php

use App\Enums\CrudFieldTypes;
use App\Enums\CrudFieldValidation;
use App\Enums\CrudTypes;
use App\Enums\PanelTypes;
use App\Filament\Resources\CrudResource\Pages\EditCrud;
use App\Filament\Resources\CrudResource\RelationManagers\FieldsRelationManager;
use App\Jobs\Generator\PanelCreatedJob;
use App\Models\Panel;
use App\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\artisan;
use function Pest\Livewire\livewire;

it('can create CRUD fields', function () {
    $user = User::factory()->create();

    actingAs($user);

    artisan('db:seed');

    $panel = Panel::create([
        'name' => 'Test Panel',
        'user_id' => auth()->id(),
        'type' => PanelTypes::FILAMENT3,
    ]);

    dispatch(new PanelCreatedJob($panel));

    $crud = $panel->cruds()->create([
        'title' => 'Test CRUD',
        'visual_title' => 'Test CRUD',
        'type' => CrudTypes::CRUD,
        'user_id' => $user->id,
        'menu_order' => 10,
    ]);

    livewire(FieldsRelationManager::class, [
        'ownerRecord' => $crud,
        'pageClass' => EditCrud::class,
    ])
        ->assertTableActionExists('create')
        ->callTableAction(
            'create',
            $crud,
            [
                'type' => CrudFieldTypes::TEXT,
                'validation' => CrudFieldValidation::REQUIRED,
                'label' => 'Test Field',
                'tooltip' => 'Test Tooltip',
                'in_create' => true,
                'in_edit' => true,
                'in_list' => true,
            ]
        )
        ->callMountedTableAction()
        ->assertHasNoTableActionErrors()
        ->assertSuccessful();

    $crud = $crud->fresh();

    $field = $crud->fields()->where('label', 'Test Field')->first();

    expect($field)->not->toBeNull();
})->skip('This test is incomplete due to the lack of documentation');

it('can edit CRUD fields', function () {
    $user = User::factory()->create();

    actingAs($user);

    artisan('db:seed');

    $panel = Panel::create([
        'name' => 'Test Panel',
        'user_id' => auth()->id(),
        'type' => PanelTypes::FILAMENT3,
    ]);

    dispatch(new PanelCreatedJob($panel));

    $panel = $panel->fresh();
})->skip('This test is incomplete due to the lack of documentation');

it('can delete CRUD fields', function () {
    $user = User::factory()->create();

    actingAs($user);

    artisan('db:seed');

    $panel = Panel::create([
        'name' => 'Test Panel',
        'user_id' => auth()->id(),
        'type' => PanelTypes::FILAMENT3,
    ]);

    dispatch(new PanelCreatedJob($panel));

    $panel = $panel->fresh();
})->skip('This test is incomplete due to the lack of documentation');

it('can reorder CRUD fields', function () {
    $user = User::factory()->create();

    actingAs($user);

    artisan('db:seed');

    $panel = Panel::create([
        'name' => 'Test Panel',
        'user_id' => auth()->id(),
        'type' => PanelTypes::FILAMENT3,
    ]);

    dispatch(new PanelCreatedJob($panel));

    $panel = $panel->fresh();
})->skip('This test is incomplete due to the lack of documentation');

it('can create various CRUD field types', function () {
    // TODO: This needs data-provider to work
    $user = User::factory()->create();

    actingAs($user);

    artisan('db:seed');

    $panel = Panel::create([
        'name' => 'Test Panel',
        'user_id' => auth()->id(),
        'type' => PanelTypes::FILAMENT3,
    ]);

    dispatch(new PanelCreatedJob($panel));

    $panel = $panel->fresh();
})->skip('This test is incomplete due to the lack of documentation');
