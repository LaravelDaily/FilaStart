<?php

namespace Generators\Laravel11\Jobs;

use App\Enums\CrudTypes;
use App\Models\Crud;
use App\Models\Panel;
use App\Models\PanelDeployment;
use App\Services\PanelService;
use Generators\Laravel11\Generators\ModelGenerator;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CreateModelJob implements ShouldQueue
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

        $this->deployment->addNewMessage('Started Generating a model for '.$this->crudData->title.' CRUD'.PHP_EOL);

        $panelService = new PanelService($this->panel);

        $model = new ModelGenerator($this->crudData);
        $modelName = $model->getName();
        $modelPath = 'app/Models/'.$modelName.'.php';
        $panelService->writeFile($modelPath, $model->generate());

        $this->crudData->panelFiles()->updateOrCreate([
            'path' => $modelPath,
            'panel_id' => $this->panel->id,
        ], [
            'path' => $modelPath,
            'panel_id' => $this->panel->id,
        ]);

        $this->deployment->addNewMessage('Model for '.$this->crudData->title.' CRUD generated successfully'.PHP_EOL);
    }
}
