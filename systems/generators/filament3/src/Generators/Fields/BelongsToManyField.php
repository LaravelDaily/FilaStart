<?php

namespace Generators\Filament3\Generators\Fields;

use Exception;
use Generators\Laravel11\Generators\MigrationLineGenerator;

class BelongsToManyField extends BaseField
{
    protected string $formComponentClass = 'Select';

    protected string $tableColumnClass = 'TextColumn';

    protected function resolveFormComponent(): void
    {
        $this->formKey = $this->field->key;
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

        // Add multiple
        $options .= PHP_EOL;
        $options .= '    ->multiple()';

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

        $currentCrudKey = str($this->field->crud?->title)->snake()->singular()->toString().'_id';
        $currentCrudTable = str($this->field->crud?->title)->snake()->plural()->toString();

        $relatedCrudKey = str($this->field->crudFieldOptions->crud->title)->snake()->singular()->toString().'_id';
        $relatedCrudTable = str($this->field->crudFieldOptions->crud->title)->snake()->plural()->toString();

        return (new MigrationLineGenerator())
            ->setType('foreignId')
            ->setKey($currentCrudKey)
            ->constrained($currentCrudTable)
            ->toString().
            PHP_EOL.
            (new MigrationLineGenerator())
                ->setType('foreignId')
                ->setKey($relatedCrudKey)
                ->constrained($relatedCrudTable)
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
        return $this->belongsToMany(%s::class);
    }';

        return sprintf(
            $template,
            $this->field->crudFieldOptions->relationship,
            $this->field->crudFieldOptions->crud->model_class_name,
        );
    }
}
