<?php

namespace Generators\Filament3\Generators\Fields;

use Generators\Laravel11\Generators\MigrationLineGenerator;

class CheckboxField extends BaseField
{
    protected string $formComponentClass = 'Checkbox';

    protected string $tableColumnClass = 'CheckboxColumn';

    protected function resolveFormComponent(): void
    {
        $this->formKey = $this->field->key;
    }

    protected function resolveTableColumn(): void
    {
        $this->tableKey = $this->field->key;
    }

    public function getMigrationLine(): string
    {
        return (new MigrationLineGenerator())
            ->setType('boolean')
            ->setKey($this->field->key)
            ->setDefault(false)
            ->toString();
    }
}
