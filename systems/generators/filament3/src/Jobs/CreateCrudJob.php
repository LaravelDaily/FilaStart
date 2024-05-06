<?php

namespace Generators\Filament3\Jobs;

use App\Enums\CrudTypes;
use App\Models\Crud;
use App\Models\Panel;
use App\Models\PanelDeployment;
use Generators\Laravel11\Jobs\CreateManyToManyMigrationJob;
use Generators\Laravel11\Jobs\CreateMigrationJob;
use Generators\Laravel11\Jobs\CreateModelJob;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CreateCrudJob implements ShouldQueue
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

        $this->deployment->addNewMessage('Constructed variables for '.$this->crudData->title.' CRUD'.PHP_EOL);

        // TODO: All of the params should be ID only. Fix that!!!
        $this->batch()
            ?->add([
                // Laravel Generators
                new CreateModelJob($this->panel, $this->crudData, $this->deployment),
                new CreateMigrationJob($this->panel, $this->crudData, $this->deployment),
                new CreateManyToManyMigrationJob($this->panel, $this->crudData, $this->deployment),

                // Filament Specific Generators
                new CreateResourceFileJob($this->panel, $this->crudData, $this->deployment),
                new CreateCreateFileJob($this->panel, $this->crudData, $this->deployment),
                new CreateEditFileJob($this->panel, $this->crudData, $this->deployment),
                new CreateListFileJob($this->panel, $this->crudData, $this->deployment),
            ]);
    }
}
