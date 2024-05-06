<?php

namespace Generators\Laravel11\Jobs;

use App\Enums\CrudTypes;
use App\Models\Crud;
use App\Models\Panel;
use App\Models\PanelDeployment;
use App\Services\PanelService;
use Generators\Laravel11\Generators\MigrationGenerator;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CreateMigrationJob implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public Panel $panel,
        public Crud $crudData,
        public PanelDeployment $deployment
    ) {
    }

    public function handle(): void
    {
        if ($this->crudData->type !== CrudTypes::CRUD) {
            return;
        }

        $this->deployment->addNewMessage('Started Generating a migration for '.$this->crudData->title.' CRUD'.PHP_EOL);

        $panelService = new PanelService($this->panel);

        $migration = new MigrationGenerator($this->crudData);
        $migrationName = $migration->getName();
        $migrationPath = 'database/migrations/'.$migrationName.'.php';
        $panelService->writeFile($migrationPath, $migration->generate());

        $this->crudData->panelFiles()->updateOrCreate([
            'path' => $migrationPath,
            'panel_id' => $this->panel->id,
        ], [
            'path' => $migrationPath,
            'panel_id' => $this->panel->id,
        ]);

        $this->deployment->addNewMessage('Migration for '.$this->crudData->title.' CRUD generated successfully'.PHP_EOL);
    }
}
