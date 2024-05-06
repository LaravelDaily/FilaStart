<?php

namespace App\Filament\Resources\CrudResource\Pages;

use App\Filament\Resources\CrudResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCruds extends ListRecords
{
    protected static string $resource = CrudResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
