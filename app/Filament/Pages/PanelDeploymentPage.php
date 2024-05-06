<?php

namespace App\Filament\Pages;

use App\Jobs\Generator\GeneratePanelCodeJob;
use App\Models\Panel;
use App\Models\PanelDeployment;
use App\Services\PanelService;
use Filament\Facades\Filament;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Bus;
use Ramsey\Uuid\Uuid;

class PanelDeploymentPage extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationLabel = 'Generate & Download Code';

    protected static ?string $title = 'Panel Generation & Download';

    protected static string $view = 'filament.pages.panel-deployment-page';

    protected static ?int $navigationSort = 2;

    public ?PanelDeployment $deployment;

    public function mount(): void
    {
        /** @var Panel $panel */
        $panel = Filament::getTenant();

        $this->deployment = $panel->panelDeployments()->latest()->first();
    }

    public function startGeneration(): void
    {
        // TODO: Clean this mess up and make the UI better.
        // UI REALLY SUCKS ATM

        /** @var Panel $panel */
        $panel = Filament::getTenant();

        $this->deployment = $panel->panelDeployments()->create([
            'status' => 'pending',
            'deployment_id' => Uuid::uuid4(),
        ]);

        $this->deployment->addNewMessage('Generation started at ' . now()->toDateTimeString() . PHP_EOL);

        Bus::batch([
            new GeneratePanelCodeJob($panel->id, $this->deployment->id),
        ])
            ->name($this->deployment->deployment_id)
            ->catch(function () {
                $this->deployment?->addNewMessage('Generation has failed...' . PHP_EOL);

                $this->deployment?->update([
                    'status' => 'failed',
                ]);
            })
            ->then(function () use ($panel) {
                $service = new PanelService($panel);
                $filePath = $service->zipFiles();

                $this->deployment?->update([
                    'status' => 'success',
                    'file_path' => $filePath,
                ]);

                $this->deployment?->addNewMessage('Generation completed at ' . now()->toDateTimeString() . PHP_EOL);
            })
            ->dispatch();

        $this->deployment = $this->deployment->fresh();

        $this->dispatch('$refresh');
    }
}
