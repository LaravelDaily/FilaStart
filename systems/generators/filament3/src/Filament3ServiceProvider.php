<?php

namespace Generators\Filament3;

use Illuminate\Support\ServiceProvider;

class Filament3ServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->loadViewsFrom(__DIR__.'/templates', 'filament3');
    }

    public function boot(): void
    {

    }
}
