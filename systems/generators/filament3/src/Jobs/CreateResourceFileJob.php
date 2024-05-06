<?php

namespace Generators\Filament3\Jobs;

use App\Enums\CrudTypes;
use App\Models\Crud;
use App\Models\Panel;
use App\Models\PanelDeployment;
use App\Services\PanelService;
use Generators\Filament3\Generators\Files\FileReplacements;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CreateResourceFileJob implements ShouldQueue
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

        $this->deployment->addNewMessage('Started generating Resource file for '.$this->crudData->title.' CRUD'.PHP_EOL);

        $panelService = new PanelService($this->panel);

        $generator = new FileReplacements($this->crudData);
        $names = $generator->generateNames();

        $resource = $generator->retrieveFileGenerator('resource');
        $resource->setReplacements($generator->getReplacementsForResource());
        $resourcePath = 'app/Filament/Resources/'.$names['resourceName'].'.php';
        $panelService->writeFile($resourcePath, $resource->generate());
        $this->crudData->panelFiles()->updateOrCreate([
            'path' => $resourcePath,
            'panel_id' => $this->panel->id,
        ], [
            'path' => $resourcePath,
            'panel_id' => $this->panel->id,
        ]);

        $this->deployment->addNewMessage('Resource file for '.$this->crudData->title.' CRUD generated successfully'.PHP_EOL);

    }
}
