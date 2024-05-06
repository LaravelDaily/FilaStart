<?php

use App\Enums\PanelTypes;
use App\Filament\Pages\PanelModuleManagement;
use App\Jobs\Generator\PanelCreatedJob;
use App\Models\Module;
use App\Models\Panel;
use App\Models\User;
use Filament\Facades\Filament;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\artisan;
use function Pest\Livewire\livewire;

it('can install and uninstall modules', function () {
    $user = User::factory()->create();

    actingAs($user);

    artisan('db:seed');

    $panel = Panel::create([
        'name' => 'Test Panel',
        'user_id' => auth()->id(),
        'type' => PanelTypes::FILAMENT3,
    ]);

    dispatch(new PanelCreatedJob($panel));

    $panel = $panel->fresh(['modules']);

    expect($panel->modules->count())->toBe(1);

    Filament::setTenant($panel);

    $modules = Module::where('slug', '!=', 'base-module')->get();

    foreach ($modules as $module) {
        livewire(PanelModuleManagement::class)
            ->call('install', $module->slug)
            ->assertSuccessful();

        $panel = $panel->fresh(['modules']);

        expect($panel->modules->count())->toBe(2);

        livewire(PanelModuleManagement::class)
            ->call('uninstall', $module->slug)
            ->assertSuccessful();

        $panel = $panel->fresh(['modules']);

        expect($panel->modules->count())->toBe(1);
    }
});
