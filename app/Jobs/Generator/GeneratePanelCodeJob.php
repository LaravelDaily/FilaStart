<?php

namespace App\Jobs\Generator;

use App\Enums\PanelTypes;
use App\Models\Panel;
use App\Models\PanelDeployment;
use App\Services\PanelService;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\View;

class GeneratePanelCodeJob implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private ?Panel $panel;

    private ?PanelDeployment $deployment;

    public function __construct(public int $panelID, public int $deploymentID)
    {
        $this->panel = Panel::find($panelID);
        $this->deployment = PanelDeployment::find($deploymentID);
    }

    public function handle(): void
    {
        if (! $this->panel || ! $this->deployment) {
            return;
        }

        $this->deployment->addNewMessage('Generation Processing...'.PHP_EOL);

        $this->panel->load([
            'cruds',
        ]);

        $service = new PanelService($this->panel);

        foreach ($this->panel->panelFiles()->where('path', 'like', '%database/migrations%')->get() as $file) {
            $service->deleteFile($file);
        }

        $panelService = new PanelService($this->panel);

        $migrations = [
            // In this array, you can add the migrations you want to create for the panel.
            // These migrations will be added to ALL panels
//            '0000_00_00_000000_create_cache_table' => '<?php'.PHP_EOL.PHP_EOL.View::make('laravel11::cacheTable')->render(),
//            '0000_00_00_000000_create_sessions_table' => '<?php'.PHP_EOL.PHP_EOL.View::make('laravel11::sessionTable')->render(),
//            '0000_00_00_000000_create_jobs_table' => '<?php'.PHP_EOL.PHP_EOL.View::make('laravel11::jobsTable')->render(),
        ];

        foreach ($migrations as $migrationName => $content) {
            $migrationPath = 'database/migrations/'.$migrationName.'.php';
            $panelService->writeFile($migrationPath, $content);
            $this->panel->panelFiles()->updateOrCreate([
                'path' => $migrationPath,
                'panel_id' => $this->panel->id,
            ], [
                'path' => $migrationPath,
                'panel_id' => $this->panel->id,
            ]);
        }

        foreach ($this->panel->cruds as $crud) {
            $this->deployment->addNewMessage("Preparing $crud->title for generation...".PHP_EOL);

            switch ($this->panel->type) {
                case PanelTypes::FILAMENT3:
                    $this->batch()
                        ?->add([
                            new \Generators\Filament3\Jobs\CreateCrudJob($this->panel, $crud, $this->deployment),
                        ]);
                    break;
                default:
                    $this->deployment->addNewMessage('Panel type not supported for generation.'.PHP_EOL);
                    break;
            }
        }
    }
}
