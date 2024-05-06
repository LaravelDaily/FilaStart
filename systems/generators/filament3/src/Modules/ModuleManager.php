<?php

namespace Generators\Filament3\Modules;

use App\Interfaces\ModuleBase;
use Nette\NotImplementedException;

class ModuleManager
{
    public static function getModule(string $moduleSlug): ModuleBase
    {
        return match ($moduleSlug) {
            'base-module' => new BaseModule(),
            'asset-management' => new AssetManagementModule(),
            'client-management' => new ClientManagementModule(),
            default => throw new NotImplementedException("Module $moduleSlug not found"),
        };
    }
}
