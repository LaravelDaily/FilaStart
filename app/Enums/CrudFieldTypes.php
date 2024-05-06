<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum CrudFieldTypes: string implements HasLabel
{
    case ID = 'id';
    case TEXT = 'text';
    case DATE_TIME = 'dateTime';
    case BELONGS_TO_MANY = 'belongsToMany';
    case BELONGS_TO = 'belongsTo';
    case PASSWORD = 'password';
    case IMAGE = 'image';
    case TEXTAREA = 'textarea';
    case CHECKBOX = 'checkbox';
    case FLOAT = 'float';
    case EMAIL = 'email';
    case DATE = 'date';
    case MONEY = 'money';
    case FILE = 'file';

    // TODO: Add all supported filament fields here :)

    public function getLabel(): ?string
    {
        return match ($this) {
            self::ID => 'ID',
            self::TEXT => 'Text',
            self::DATE_TIME => 'Date Time',
            self::BELONGS_TO_MANY => 'Belongs To Many',
            self::BELONGS_TO => 'Belongs To',
            self::PASSWORD => 'Password',
            self::IMAGE => 'Image',
            self::TEXTAREA => 'Textarea',
            self::CHECKBOX => 'Checkbox',
            self::FLOAT => 'Float',
            self::EMAIL => 'Email',
            self::DATE => 'Date',
            self::MONEY => 'Money',
            self::FILE => 'File',
        };
    }
}
