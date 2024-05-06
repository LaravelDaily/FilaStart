<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum CrudTypes: string implements HasLabel
{
    case CRUD = 'crud';
    case PARENT = 'parent';
    case NON_CRUD = 'non-crud';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::CRUD => 'CRUD',
            self::PARENT => 'Parent',
            self::NON_CRUD => 'Non-CRUD',
        };
    }
}
