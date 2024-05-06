<?php

namespace Generators\Filament3\Generators\Fields;

use App\Enums\CrudFieldTypes;
use App\Models\CrudField;
use Nette\NotImplementedException;

class BaseField
{
    protected CrudField $field;

    // Form Fields
    protected string $formComponentClass;

    protected string $formKey;

    // Table Fields
    protected string $tableColumnClass;

    protected string $tableKey;

    public function __construct(CrudField $field)
    {
        $this->field = $field;
    }

    public function getMigrationLine(): string
    {
        throw new NotImplementedException('Method getMigrationLine() not implemented for '.static::class);
    }

    public function getMigrationUses(): ?string
    {
        return null;
    }

    public function formComponent(): string
    {
        $this->resolveFormComponent();

        $output = sprintf(
            "Forms\\Components\\%s::make('%s')",
            $this->formComponentClass,
            $this->field->form_key_name,
        );

        $options = $this->resolveFormOptions();

        if ($options !== '') {
            $output .= $options;
        }

        return $output;
    }

    public function tableColumn(): string
    {
        $this->resolveTableColumn();

        $output = sprintf(
            "Tables\\Columns\\%s::make('%s')",
            $this->tableColumnClass,
            $this->field->table_key_name,
        );

        $options = $this->resolveTableOptions();

        if ($options !== '') {
            $output .= $options;
        }

        return $output;
    }

    public function modelRelationships(): ?string
    {
        return null;
    }

    protected function resolveFormComponent(): void
    {
        throw new NotImplementedException('Method resolveFormComponent() not implemented for '.static::class);
    }

    protected function resolveTableColumn(): void
    {
        throw new NotImplementedException('Method resolveTableColumn() not implemented for '.static::class);
    }

    protected function resolveFormOptions(): string
    {
        $settings = [];

        if ($this->field->validation === 'required') { // TODO: Validation types should be an enum
            $settings['required'] = true;
        }

        if ($this->field->tooltip) {
            if ($this->field->type !== CrudFieldTypes::CHECKBOX) {
                $settings['placeholder'] = $this->field->tooltip;
            } else {
                $settings['hint'] = $this->field->tooltip;
            }
        }

        if (! $this->field->in_create) {
            $settings['hiddenOn'] = 'create';
        }

        if (! $this->field->in_edit) {
            $settings['hiddenOn'] = 'edit';
        }

        if ($this->field->type === CrudFieldTypes::EMAIL) {
            $settings['email'] = true;
        }

        return $this->generateOutput($settings);
    }

    protected function resolveTableOptions(): string
    {
        $settings = [];

        return $this->generateOutput($settings);
    }

    private function mapParameters(mixed $parameters): string
    {
        return collect($parameters)// @phpstan-ignore-line
            ->map(function (mixed $value, int|string $name): string|float|int {
                $value = match (true) {
                    is_bool($value) => $value ? 'true' : 'false',
                    is_null($value) => 'null',
                    is_numeric($value) => $value,
                    default => "'$value'",// @phpstan-ignore-line
                };

                if (is_numeric($name)) {
                    return $value;
                }

                return "$name: $value";
            })
            ->implode(', ');
    }

    /**
     * @param  array<string, bool|string>  $settings
     */
    private function generateOutput(array $settings): string
    {
        $output = '';

        foreach ($settings as $method => $parameters) {
            $output .= sprintf('%s    ->%s(%s)', PHP_EOL, $method, $this->mapParameters($parameters));
        }

        return $output;
    }
}
