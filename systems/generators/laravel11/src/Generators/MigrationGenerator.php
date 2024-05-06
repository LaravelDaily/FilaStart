<?php

namespace Generators\Laravel11\Generators;

use App\Enums\CrudFieldTypes;
use App\Models\Crud;
use App\Models\CrudField;
use Exception;
use Generators\Filament3\Generators\Fields\RetrieveGeneratorForField;

class MigrationGenerator
{
    public function __construct(public Crud $crud)
    {
        if (! $this->crud->relationLoaded('fields')) {
            $this->crud->load(['fields', 'fields.crudFieldOptions']);
        }
    }

    public function generate(): string
    {
        $output = view('laravel11::migration', [
            'uses' => $this->generateUses(),
            'tableName' => $this->generateTableName(),
            'tableColumns' => $this->generateColumns(),
        ])->render();

        return '<?php'.PHP_EOL.PHP_EOL.$output;
    }

    public function generateManyToMany(Crud $crud, CrudField $field): string
    {
        if (! $field->crudFieldOptions) {
            throw new Exception('Field options are not loaded');
        }

        if (! $field->crudFieldOptions->crud) {
            throw new Exception('Field options crud is not loaded');
        }

        $output = view('laravel11::migration', [
            'uses' => $this->generateUses(),
            'tableName' => $this->orderManyToManyName($crud, $field->crudFieldOptions->crud),
            'tableColumns' => $this->generateManyToManyColumns($field),
        ])->render();

        return '<?php'.PHP_EOL.PHP_EOL.$output;
    }

    public function getName(): string
    {
        $output = '0000_00_00_'.str_pad((string) $this->crud->menu_order, 6, '0', STR_PAD_LEFT);
        $output .= '_create_'.str($this->crud->title)->snake()->plural()->toString();
        $output .= '_table';

        return $output;
    }

    public function getManyToManyName(int $order, Crud $first, Crud $second): string
    {
        $name = $this->orderManyToManyName($first, $second);

        $output = '0000_00_00_'.str_pad((string) $order, 6, '0', STR_PAD_LEFT);
        $output .= '_create_'.$name;
        $output .= '_table';

        return $output;
    }

    private function generateUses(): string
    {
        return ''; // TODO: Implement this if needed.
    }

    private function generateTableName(): string
    {
        return str($this->crud->title)->snake()->plural()->toString();
    }

    private function generateColumns(): string
    {
        $columns = [];

        foreach ($this->crud->fields as $field) {
            if ($field->type === CrudFieldTypes::BELONGS_TO_MANY) {
                continue;
            }

            if (in_array($field->key, ['created_at', 'updated_at', 'deleted_at'])) {
                continue;
            }

            $fieldGenerator = RetrieveGeneratorForField::for($field);
            $columns[] = $this->indentString($fieldGenerator->getMigrationLine(), 3);
        }

        $columns[] = $this->indentString(
            (new MigrationLineGenerator())
                ->setType('timestamps')
                ->toString(),
            3);

        // TODO: This should be optional/configured
        $columns[] = $this->indentString(
            (new MigrationLineGenerator())
                ->setType('softDeletes')
                ->toString(),
            3);

        return implode(PHP_EOL, $columns);
    }

    protected function indentString(string $string, int $level = 1): string
    {
        return implode(
            PHP_EOL,
            array_map(
                static fn (string $line) => ($line !== '') ? (str_repeat('    ', $level).$line) : '',
                explode(PHP_EOL, $string),
            ),
        );
    }

    private function generateManyToManyColumns(CrudField $field): string
    {
        $columns = [];

        // If we want to drop ID field from many-to-many relationships - just remove this array element
        $columns[] = $this->indentString(
            (new MigrationLineGenerator())
                ->setType('id')
                ->toString(),
            3);

        $fieldGenerator = RetrieveGeneratorForField::for($field);
        $columns[] = $this->indentString($fieldGenerator->getMigrationLine(), 3);

        return implode(PHP_EOL, $columns);
    }

    private function orderManyToManyName(Crud $first, Crud $second): string
    {
        $table_1 = str($first->title)->snake()->singular()->toString();
        $table_2 = str($second->title)->snake()->singular()->toString();

        // pivot table name should be in alphabetical order
        $pivotOrder = strcasecmp($table_1, $table_2);
        if ($pivotOrder < 0) {
            // table1 is first
            $name = $table_1.'_'.$table_2;
        } elseif ($pivotOrder > 0) {
            // table2 is first
            $name = $table_2.'_'.$table_1;
        } else {
            throw new Exception('Invalid pivot table names');
        }

        return $name;
    }
}
