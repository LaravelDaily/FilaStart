<?php

namespace Generators\Laravel11\Jobs;

use App\Enums\CrudFieldTypes;
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

class CreateManyToManyMigrationJob implements ShouldQueue
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

        // Check if there should be many to many migration created

        $manyToMayFields = $this->crudData->fields()->whereIn('type', [
            CrudFieldTypes::BELONGS_TO_MANY,
        ]);

        if ($manyToMayFields->count() === 0) {
            return;
        }

        $manyToMayFields = $manyToMayFields->with(['crudFieldOptions', 'crudFieldOptions.crud', 'crudFieldOptions.relatedCrudField'])->get();

        $this->deployment->addNewMessage('Started Generating a many to many migration for '.$this->crudData->title.' CRUD'.PHP_EOL);

        foreach ($manyToMayFields as $field) {
            if (! $field->crudFieldOptions) {
                // TODO: Add logging that something is wrong with the field
                continue;
            }

            if (! $field->crudFieldOptions->crud) {
                // TODO: Add logging that something is wrong with the field
                continue;
            }

            $panelService = new PanelService($this->panel);
            $migration = new MigrationGenerator($this->crudData);

            $migrationName = $migration->getManyToManyName($this->panel->cruds()->max('menu_order') + 10, $this->crudData, $field->crudFieldOptions->crud);
            $migrationPath = 'database/migrations/'.$migrationName.'.php';
            $panelService->writeFile($migrationPath, $migration->generateManyToMany($this->crudData, $field));

            $this->crudData->panelFiles()->updateOrCreate([
                'path' => $migrationPath,
                'panel_id' => $this->panel->id,
            ], [
                'path' => $migrationPath,
                'panel_id' => $this->panel->id,
            ]);

        }

        $this->deployment->addNewMessage('Migration for '.$this->crudData->title.' CRUD generated successfully'.PHP_EOL);
    }
}
