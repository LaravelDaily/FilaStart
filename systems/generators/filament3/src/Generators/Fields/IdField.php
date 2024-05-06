<?php

namespace Generators\Filament3\Generators\Fields;

use Generators\Laravel11\Generators\MigrationLineGenerator;

class IdField extends BaseField
{
    protected string $tableColumnClass = 'TextColumn';

    protected function resolveTableColumn(): void
    {
        $this->tableKey = $this->field->key;
    }

    public function formComponent(): string
    {
        return ''; // We don't want to have ID field on forms. We might change this
    }

    public function getMigrationLine(): string
    {
        return (new MigrationLineGenerator())
            ->setType('id')
            ->toString();
    }
}
