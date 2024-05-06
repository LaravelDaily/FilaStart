<?php

namespace App\Filament\Pages;

use App\Enums\PanelTypes;
use App\Jobs\Generator\PanelCreatedJob;
use App\Models\Panel;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Pages\Tenancy\RegisterTenant;

class CreatePanelPage extends RegisterTenant
{
    public static function getLabel(): string
    {
        return 'Create new Panel';
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name'),
                // ...
            ]);
    }

    protected function handleRegistration(array $data): Panel
    {
        $panel = Panel::create([
            ...$data,
            'user_id' => auth()->id(),
            'type' => PanelTypes::FILAMENT3,
        ]);

        dispatch(new PanelCreatedJob($panel));

        return $panel;
    }
}
