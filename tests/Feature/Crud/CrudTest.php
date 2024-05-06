<?php

use App\Enums\CrudTypes;
use App\Enums\PanelTypes;
use App\Filament\Resources\CrudResource\Pages\CreateCrud;
use App\Filament\Resources\CrudResource\Pages\EditCrud;
use App\Filament\Resources\CrudResource\Pages\ListCruds;
use App\Jobs\Generator\PanelCreatedJob;
use App\Models\Panel;
use App\Models\User;
use Filament\Facades\Filament;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\artisan;
use function Pest\Livewire\livewire;

it('can create a CRUD that is parent', function () {
    $user = User::factory()->create();

    actingAs($user);

    artisan('db:seed');

    $panel = Panel::create([
        'name' => 'Test Panel',
        'user_id' => auth()->id(),
        'type' => PanelTypes::FILAMENT3,
    ]);

    $panel = $panel->fresh();

    Filament::setTenant($panel);

    livewire(CreateCrud::class)
        ->fillForm([
            'title' => 'Test CRUD',
            'visual_title' => 'Test CRUD',
            'type' => CrudTypes::PARENT,
        ])
        ->call('create')
        ->assertHasNoErrors();

    $crudQuery = $panel->cruds()->where('visual_title', 'Test CRUD');

    expect($panel->cruds()->count())->toBe(1)
        ->and($crudQuery->exists())->toBeTrue()
        ->and($crudQuery->first()->type)->toBe(CrudTypes::PARENT);
});

it('can edit a CRUD that is parent', function () {
    $user = User::factory()->create();

    actingAs($user);

    artisan('db:seed');

    $panel = Panel::create([
        'name' => 'Test Panel',
        'user_id' => auth()->id(),
        'type' => PanelTypes::FILAMENT3,
    ]);

    $crud = $panel->cruds()->create([
        'title' => 'Test CRUD',
        'visual_title' => 'Test CRUD',
        'type' => CrudTypes::PARENT,
        'user_id' => $user->id,
        'menu_order' => 10,
    ]);

    $panel = $panel->fresh();

    Filament::setTenant($panel);

    livewire(EditCrud::class, [
        'record' => $crud->id,
    ])
        ->fillForm([
            'visual_title' => 'Test CRUD - Changed',
        ])
        ->call('save')
        ->assertHasNoErrors();

    $crudQuery = $panel->cruds()->where('visual_title', 'Test CRUD - Changed');

    expect($panel->cruds()->count())->toBe(1)
        ->and($crudQuery->exists())->toBeTrue()
        ->and($crudQuery->first()->type)->toBe(CrudTypes::PARENT);
});

it('can create a CRUD that is custom', function () {
    $user = User::factory()->create();

    actingAs($user);

    artisan('db:seed');

    $panel = Panel::create([
        'name' => 'Test Panel',
        'user_id' => auth()->id(),
        'type' => PanelTypes::FILAMENT3,
    ]);

    $panel = $panel->fresh();

    Filament::setTenant($panel);

    livewire(CreateCrud::class)
        ->fillForm([
            'title' => 'Custom CRUD',
            'visual_title' => 'Custom CRUD',
            'type' => CrudTypes::NON_CRUD,
        ])
        ->call('create')
        ->assertHasNoErrors();

    $crudQuery = $panel->cruds()->where('visual_title', 'Custom CRUD');

    expect($panel->cruds()->count())->toBe(1)
        ->and($crudQuery->exists())->toBeTrue()
        ->and($crudQuery->first()->type)->toBe(CrudTypes::NON_CRUD);

});

it('can edit a CRUD that is custom', function () {
    $user = User::factory()->create();

    actingAs($user);

    artisan('db:seed');

    $panel = Panel::create([
        'name' => 'Test Panel',
        'user_id' => auth()->id(),
        'type' => PanelTypes::FILAMENT3,
    ]);

    $crud = $panel->cruds()->create([
        'title' => 'Test CRUD',
        'visual_title' => 'Test CRUD',
        'type' => CrudTypes::NON_CRUD,
        'user_id' => $user->id,
        'menu_order' => 10,
    ]);

    $panel = $panel->fresh();

    Filament::setTenant($panel);

    livewire(EditCrud::class, [
        'record' => $crud->id,
    ])
        ->fillForm([
            'visual_title' => 'Test CRUD - Changed',
        ])
        ->call('save')
        ->assertHasNoErrors();

    $crudQuery = $panel->cruds()->where('visual_title', 'Test CRUD - Changed');

    expect($panel->cruds()->count())->toBe(1)
        ->and($crudQuery->exists())->toBeTrue()
        ->and($crudQuery->first()->type)->toBe(CrudTypes::NON_CRUD);
});

it('can create a CRUD that is CRUD with fields', function () {
    $user = User::factory()->create();

    actingAs($user);

    artisan('db:seed');

    $panel = Panel::create([
        'name' => 'Test Panel',
        'user_id' => auth()->id(),
        'type' => PanelTypes::FILAMENT3,
    ]);

    $panel = $panel->fresh();

    Filament::setTenant($panel);

    livewire(CreateCrud::class)
        ->fillForm([
            'title' => 'CRUD',
            'visual_title' => 'CRUD',
            'type' => CrudTypes::CRUD,
        ])
        ->call('create')
        ->assertHasNoErrors();

    $crudQuery = $panel->cruds()->where('visual_title', 'CRUD');

    $crud = $crudQuery->first();
    expect($panel->cruds()->count())->toBe(1)
        ->and($crudQuery->exists())->toBeTrue()
        ->and($crud->type)->toBe(CrudTypes::CRUD)
        ->and($crud->fields()->count())->toBe(4)
        ->and($crud->fields()->where('key', 'id')->exists())->toBeTrue()
        ->and($crud->fields()->where('key', 'created_at')->exists())->toBeTrue()
        ->and($crud->fields()->where('key', 'updated_at')->exists())->toBeTrue()
        ->and($crud->fields()->where('key', 'deleted_at')->exists())->toBeTrue();

});

it('can edit a CRUD that is CRUD with fields', function () {
    $user = User::factory()->create();

    actingAs($user);

    artisan('db:seed');

    $panel = Panel::create([
        'name' => 'Test Panel',
        'user_id' => auth()->id(),
        'type' => PanelTypes::FILAMENT3,
    ]);

    $crud = $panel->cruds()->create([
        'title' => 'Test CRUD',
        'visual_title' => 'Test CRUD',
        'type' => CrudTypes::CRUD,
        'user_id' => $user->id,
        'menu_order' => 10,
    ]);

    $panel = $panel->fresh();

    Filament::setTenant($panel);

    livewire(EditCrud::class, [
        'record' => $crud->id,
    ])
        ->fillForm([
            'visual_title' => 'Test CRUD - Changed',
        ])
        ->call('save')
        ->assertHasNoErrors();

    $crudQuery = $panel->cruds()->where('visual_title', 'Test CRUD - Changed');

    expect($panel->cruds()->count())->toBe(1)
        ->and($crudQuery->exists())->toBeTrue()
        ->and($crudQuery->first()->type)->toBe(CrudTypes::CRUD);
});

it('crud list loads correctly', function () {
    $user = User::factory()->create();

    actingAs($user);

    artisan('db:seed');

    $panel = Panel::create([
        'name' => 'Test Panel',
        'user_id' => auth()->id(),
        'type' => PanelTypes::FILAMENT3,
    ]);

    dispatch_sync(new PanelCreatedJob($panel));

    $panel = $panel->fresh();

    Filament::setTenant($panel);

    $cruds = $panel->cruds()->get();

    livewire(ListCruds::class)
        ->assertCanSeeTableRecords($cruds)
        ->assertCountTableRecords(4);
});
