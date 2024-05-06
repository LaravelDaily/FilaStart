<?php

namespace Generators\Laravel11\Generators;

use App\Enums\CrudFieldTypes;
use App\Models\Crud;
use App\Models\CrudField;
use Generators\Filament3\Generators\Fields\RetrieveGeneratorForField;

class ModelGenerator
{
    /**
     * @var string[]
     */
    private array $uses = [];

    public function __construct(public Crud $crud)
    {
        if (! $this->crud->relationLoaded('fields')) {
            $this->crud->load(['fields', 'fields.crudFieldOptions', 'fields.crudFieldOptions.crud']);
        }
    }

    public function generate(): string
    {
        $output = view('laravel11::model', [
            'extends' => $this->getModelExtension(),
            'traits' => $this->getModelTraits(),
            'casts' => $this->getModelCasts(),
            'modelName' => $this->getName(),
            'fillable' => $this->generateFillable(),
            'relationships' => $this->generateRelationships(),
            'methods' => $this->generateCustomMethods(),
            'uses' => $this->getModelUses(),
        ])->render();

        return '<?php'.PHP_EOL.PHP_EOL.$output;
    }

    private function getModelUses(): string
    {
        $uses = [];

        if ($this->crud->module_slug === 'base-module' && $this->crud->title === 'User') {
            $uses[] = 'use Filament\Models\Contracts\FilamentUser;';
            $uses[] = 'use Illuminate\Auth\MustVerifyEmail;';
            $uses[] = 'use Illuminate\Database\Eloquent\Factories\HasFactory;';
            $uses[] = 'use Illuminate\Foundation\Auth\User as Authenticatable;';
            $uses[] = 'use Illuminate\Notifications\Notifiable;';
        }

        $uses += $this->uses;

        return implode(PHP_EOL, $uses);
    }

    public function getName(): string
    {
        return $this->crud->model_class_name;
    }

    private function getModelExtension(): string
    {
        if ($this->crud->module_slug === 'base-module' && $this->crud->title === 'User') {
            return 'Authenticatable implements FilamentUser';
        }

        return 'Model';
    }

    private function getModelTraits(): string
    {
        if ($this->crud->module_slug === 'base-module' && $this->crud->title === 'User') {
            return PHP_EOL.'    use HasFactory, MustVerifyEmail, Notifiable;';
        }

        return '';
    }

    private function generateFillable(): string
    {
        return $this->crud->fields
            ->filter(function (CrudField $field) {
                return ! in_array($field->key, [
                    'id',
                    'created_at',
                    'updated_at',
                    'deleted_at',
                ]);
            })
            ->map(function (CrudField $field) {
                if (($field->type === CrudFieldTypes::BELONGS_TO) && ! str($field->key)->contains('_id')) {
                    return "'{$field->key}_id'";
                }

                return "'$field->key'";
            })->implode(', '.PHP_EOL.'        ');
    }

    private function generateRelationships(): string
    {
        $relationships = [];

        $fields = $this->crud->fields->filter(function (CrudField $field) {
            return in_array($field->type, [CrudFieldTypes::BELONGS_TO, CrudFieldTypes::BELONGS_TO_MANY], true);
        });

        foreach ($fields as $field) {
            $generator = RetrieveGeneratorForField::for($field);
            $fieldRelationships = $generator->modelRelationships();

            if ($fieldRelationships) {
                $relationships[] = $fieldRelationships;
            }
        }

        return implode(PHP_EOL.PHP_EOL, $relationships).PHP_EOL;
    }

    private function getModelCasts(): string
    {
        // TODO: Fields should decide themselves if they add anything to the casts
        return '';
    }

    private function generateCustomMethods(): string
    {
        $methods = [];
        if ($this->crud->module_slug === 'base-module' && $this->crud->title === 'User') {
            $methods[] = '    public function canAccessPanel(\Filament\Panel $panel): bool
    {
        return true;
    }';
        }

        return implode(PHP_EOL.PHP_EOL, $methods);
    }
}
