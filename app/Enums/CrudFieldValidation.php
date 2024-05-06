<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum CrudFieldValidation: string implements HasLabel
{
    case REQUIRED = 'required';
    case NULLABLE = 'optional';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::REQUIRED => 'Required',
            self::NULLABLE => 'Optional',
        };
    }
}
