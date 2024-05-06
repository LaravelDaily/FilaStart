<?php

namespace App\Services;

use App\Enums\PanelTypes;
use App\Interfaces\ModuleBase;
use App\Models\Panel;
use Generators\Filament3\Modules\ModuleManager;

class ModuleService
{
    /**
     * @return string[]
     */
    public function listModules(): array
    {
        return [
            'base-module' => 'Panel Base',
            'asset-management' => 'Asset Management',
            'client-management' => 'Client Management',
        ];
    }

    public static function getModuleClass(Panel $panel, string $moduleSlug): ModuleBase
    {
        // TODO: Think about a way to pass panel to the module directly here, to avoid passing it into the install
        return match ($panel->type) {
            PanelTypes::FILAMENT3 => ModuleManager::getModule($moduleSlug)
        };
    }
}
