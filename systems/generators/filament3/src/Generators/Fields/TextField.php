<?php

namespace Generators\Filament3\Generators\Fields;

use Generators\Laravel11\Generators\MigrationLineGenerator;

class TextField extends BaseField
{
    protected string $formComponentClass = 'TextInput';

    protected string $tableColumnClass = 'TextColumn';

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
            ->setType('string')
            ->setKey($this->field->key)
            ->toString();
    }
}
