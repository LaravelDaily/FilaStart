<?php

use App\Enums\PanelTypes;
use App\Filament\Pages\CreatePanelPage;
use App\Jobs\Generator\PanelCreatedJob;
use App\Models\Panel;
use App\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\artisan;
use function Pest\Livewire\livewire;

it('can create new panel via filament', function () {
    $user = User::factory()->create();

    actingAs($user);

    artisan('db:seed');

    livewire(CreatePanelPage::class)
        ->fillForm([
            'name' => 'Test Panel',
        ])
        ->assertHasNoFormErrors()
        ->call('register');

    expect(Panel::where('name', 'Test Panel')->exists())
        ->toBeTrue();
});

it('panel created job installs base module correctly', function () {
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

    expect($panel->modules()->count())->toBe(1)
        ->and($panel->modules()->first()->slug)
        ->toBe('base-module')
        ->and($panel->cruds()->count())->toBe(4);
});
