<?php

namespace Generators\Filament3\Generators\Fields;

use Generators\Laravel11\Generators\MigrationLineGenerator;

class PasswordField extends BaseField
{
    protected string $formComponentClass = 'TextInput';

    protected function resolveFormComponent(): void
    {
        $this->formKey = $this->field->key;
    }

    public function tableColumn(): string
    {
        return ''; // Password field should never be displayed on a table!
    }

    protected function resolveFormOptions(): string
    {
        $options = PHP_EOL;
        $options .= '    ->password()';

        return $options.parent::resolveFormOptions();
    }

    public function getMigrationLine(): string
    {
        return (new MigrationLineGenerator())
            ->setType('string')
            ->setKey($this->field->key)
            ->toString();
    }
}
