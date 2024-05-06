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

class CreateEditFileJob implements ShouldQueue
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

        $this->deployment->addNewMessage('Started generating Edit file for '.$this->crudData->title.' CRUD'.PHP_EOL);

        $panelService = new PanelService($this->panel);

        $generator = new FileReplacements($this->crudData);
        $names = $generator->generateNames();

        $edit = $generator->retrieveFileGenerator('edit');
        $edit->setReplacements($generator->getReplacementsForEditPage());
        $editPath = 'app/Filament/Resources/'.$names['resourceName'].'/Pages/'.$names['editName'].'.php';
        $panelService->writeFile($editPath, $edit->generate());
        $this->crudData->panelFiles()->updateOrCreate([
            'path' => $editPath,
            'panel_id' => $this->panel->id,
        ], [
            'path' => $editPath,
            'panel_id' => $this->panel->id,
        ]);

        $this->deployment->addNewMessage('Edit file for '.$this->crudData->title.' CRUD generated successfully'.PHP_EOL);
    }
}
