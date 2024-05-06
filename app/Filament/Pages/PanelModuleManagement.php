<?php

namespace App\Filament\Pages;

use App\Models\Module;
use App\Models\Panel;
use App\Services\ModuleService;
use Filament\Facades\Filament;
use Filament\Pages\Page;

class PanelModuleManagement extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.panel-module-management';

    protected static bool $shouldRegisterNavigation = false;

    protected function getViewData(): array
    {
        return [
            'panel' => Filament::getTenant()?->load(['modules']),
            'modules' => Module::query()
                ->where('slug', '!=', 'base-module')
                ->pluck('title', 'slug'),
        ];
    }

    public function install(string $moduleSlug): void
    {
        /** @var Panel $panel */
        $panel = Filament::getTenant();
        $module = Module::where('slug', $moduleSlug)->firstOrFail();

        if (! $panel->modules->contains($module->id)) {
            $panel->modules()->attach($module->id);

            ModuleService::getModuleClass($panel, $module->slug)
                ->install($panel);
        }

        $this->dispatch('$refresh');
    }

    public function uninstall(string $moduleSlug): void
    {
        /** @var Panel $panel */
        $panel = Filament::getTenant();
        $module = Module::where('slug', $moduleSlug)->firstOrFail();

        if ($panel->modules->contains($module->id)) {
            $panel->modules()->detach($module->id);

            ModuleService::getModuleClass($panel, $module->slug)
                ->uninstall($panel);
        }

        $this->dispatch('$refresh');
    }
}
