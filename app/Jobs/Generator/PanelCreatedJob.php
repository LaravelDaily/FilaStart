<?php

namespace App\Jobs\Generator;

use App\Models\Module;
use App\Models\Panel;
use App\Services\ModuleService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class PanelCreatedJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public Panel $panel)
    {
    }

    public function handle(): void
    {
        $this->panel->modules()
            ->attach(Module::where('slug', 'base-module')->firstOrFail()->id);
        ModuleService::getModuleClass($this->panel, 'base-module')
            ->install($this->panel);
    }
}
