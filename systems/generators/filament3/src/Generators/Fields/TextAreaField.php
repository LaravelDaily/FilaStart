<?php

namespace Generators\Filament3\Generators\Fields;

use Generators\Laravel11\Generators\MigrationLineGenerator;

class TextAreaField extends BaseField
{
    protected string $formComponentClass = 'Textarea';

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
            ->setType('text')
            ->setKey($this->field->key)
            ->toString();
    }
}
