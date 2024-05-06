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
        //
    }
}
