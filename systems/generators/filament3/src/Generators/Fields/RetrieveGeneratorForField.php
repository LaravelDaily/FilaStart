<?php

namespace Generators\Filament3\Generators\Fields;

use App\Enums\CrudFieldTypes;
use App\Models\CrudField;

class RetrieveGeneratorForField
{
    public static function for(CrudField $field): BaseField
    {
        return match ($field->type) {
            CrudFieldTypes::ID => new IdField($field),
            CrudFieldTypes::TEXT => new TextField($field),
            CrudFieldTypes::DATE_TIME => new DateTimeField($field),
            CrudFieldTypes::BELONGS_TO_MANY => new BelongsToManyField($field),
            CrudFieldTypes::PASSWORD => new PasswordField($field),
            CrudFieldTypes::BELONGS_TO => new BelongsToField($field),
            CrudFieldTypes::IMAGE => new ImageField($field),
            CrudFieldTypes::TEXTAREA => new TextAreaField($field),
            CrudFieldTypes::CHECKBOX => new CheckboxField($field),
            CrudFieldTypes::FLOAT => new FloatField($field),
            CrudFieldTypes::EMAIL => new EmailField($field),
            CrudFieldTypes::DATE => new DateField($field),
            CrudFieldTypes::MONEY => new MoneyField($field),
            CrudFieldTypes::FILE => new FileField($field),
        };
    }
}
