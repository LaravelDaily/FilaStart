<?php

namespace Generators\Filament3\Generators\Fields;

use Generators\Laravel11\Generators\MigrationLineGenerator;

class DateField extends BaseField
{
    protected string $formComponentClass = 'DatePicker';

    protected string $tableColumnClass = 'TextColumn';

    protected function resolveFormComponent(): void
    {
        $this->formKey = $this->field->key;
    }

    protected function resolveTableColumn(): void
    {
        $this->tableKey = $this->field->key;
    }

    protected function resolveTableOptions(): string
    {
        $options = PHP_EOL;
        // TODO: This needs format to be added
        $options .= '    ->date()';

        return $options.parent::resolveTableOptions();
    }

    public function getMigrationLine(): string
    {
        return (new MigrationLineGenerator())
            ->setType('date')
            ->setKey($this->field->key)
            ->toString();
    }
}
