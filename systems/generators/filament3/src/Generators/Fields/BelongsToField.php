<?php

namespace Generators\Filament3\Generators\Fields;

use Exception;
use Generators\Laravel11\Generators\MigrationLineGenerator;

class BelongsToField extends BaseField
{
    protected string $formComponentClass = 'Select';

    protected string $tableColumnClass = 'TextColumn';

    protected function resolveFormComponent(): void
    {
        $key = $this->field->key;

        if (! str($key)->endsWith('_id')) {
            $key .= '_id';
        }

        $this->formKey = $key;
    }

    protected function resolveTableColumn(): void
    {
        $crudFieldOptions = $this->field->crudFieldOptions;

        if (! $crudFieldOptions) {
            throw new Exception("Crud field options not found for field {$this->field->key}");
        }

        $this->tableKey = sprintf(
            '%s.%s',
            $crudFieldOptions->relationship,
            $crudFieldOptions->relatedCrudField->key
        );
    }

    protected function resolveFormOptions(): string
    {
        $crudFieldOptions = $this->field->crudFieldOptions;

        if (! $crudFieldOptions) {
            throw new Exception("Crud field options not found for field {$this->field->key}");
        }

        // Add relationship to form options
        $options = PHP_EOL;
        $options .= sprintf(
            '    ->relationship(\'%s\', \'%s\')',
            $crudFieldOptions->relationship,
            $crudFieldOptions->relatedCrudField->key
        );

        return $options.parent::resolveFormOptions();
    }

    public function getMigrationLine(): string
    {
        if (! $this->field->crudFieldOptions) {
            throw new Exception("Crud field options not found for field {$this->field->key}");
        }

        if (! $this->field->crudFieldOptions->crud) {
            throw new Exception("Related crud not found for field {$this->field->key}");
        }

        // TODO: Look at the implementation, it might need a better one
        $key = $this->field->key;

        if (! str($key)->endsWith('_id')) {
            $key .= '_id';
        }

        return (new MigrationLineGenerator())
            ->setType('foreignId')
            ->setKey($key)
            ->constrained($this->field->crudFieldOptions->crud->model_snake_plural_class_name)
            ->toString();
    }

    public function modelRelationships(): string
    {
        if (! $this->field->crudFieldOptions) {
            throw new Exception("Crud field options not found for field {$this->field->key}");
        }

        if (! $this->field->crudFieldOptions->crud) {
            throw new Exception("Related crud not found for field {$this->field->key}");
        }

        $template = '    public function %s()
    {
        return $this->belongsTo(%s::class);
    }';

        return sprintf(
            $template,
            $this->field->crudFieldOptions->relationship,
            $this->field->crudFieldOptions->crud->model_class_name,
        );
    }
}
