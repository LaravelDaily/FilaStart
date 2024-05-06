<?php

namespace Generators\Laravel11;

use Illuminate\Support\ServiceProvider;

class Laravel11ServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->loadViewsFrom(__DIR__.'/templates', 'laravel11');
    }

    public function boot(): void
    {

    }
}
