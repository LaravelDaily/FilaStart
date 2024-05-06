<?php

namespace Generators\Filament3\Generators\Files;

use const PHP_EOL;

use App\Models\Crud;
use Generators\Filament3\Generators\Fields\RetrieveGeneratorForField;
use Generators\Filament3\IndentsLines;
use Nette\NotImplementedException;

class FileReplacements
{
    use IndentsLines;

    private string $resourceClass;

    private string $listResourcePageClass;

    private string $createResourcePageClass;

    private string $editResourcePageClass;

    private ?string $eloquentQuery = null;

    private ?string $model = null;

    private ?string $modelClass = null;

    private ?string $pages = null;

    private ?string $relations = null;

    private ?string $resource = null;

    private ?string $tableActions = null;

    private ?string $tableBulkActions = null;

    private ?string $tableFilters = null;

    private ?string $navigationGroup = null;

    private ?string $actions = null;

    private ?string $icon = null;

    public function __construct(private readonly Crud $crudData)
    {
        if (! $this->crudData->relationLoaded('fields')) {
            $this->crudData->load([
                'fields',
                'fields.crudFieldOptions',
                'fields.crudFieldOptions.crud',
                'fields.crudFieldOptions.relatedCrudField',
            ]);
        }

        $this->formReplacements();
    }

    private function formReplacements(): void
    {
        $modelName = str($this->crudData->title)->singular();

        $model = (string) str($modelName)
            ->studly()
            ->beforeLast('Resource')
            ->trim('/')
            ->trim('\\')
            ->trim(' ')
            ->studly()
            ->replace('/', '\\');

        if (blank($model)) {
            $model = 'Resource';
        }

        $modelClass = (string) str($model)->afterLast('\\');
        $pluralModelClass = (string) str($modelClass)->pluralStudly();

        $this->resourceClass = $modelClass.'Resource';
        $this->listResourcePageClass = 'List'.$pluralModelClass;
        $this->createResourcePageClass = 'Create'.$modelClass;
        $this->editResourcePageClass = 'Edit'.$modelClass;

        $eloquentQuery = PHP_EOL.PHP_EOL.'public static function getEloquentQuery(): Builder';
        $eloquentQuery .= PHP_EOL.'{';
        $eloquentQuery .= PHP_EOL.'    return parent::getEloquentQuery()';
        $eloquentQuery .= PHP_EOL.'        ->withoutGlobalScopes([';
        $eloquentQuery .= PHP_EOL.'            SoftDeletingScope::class,';
        $eloquentQuery .= PHP_EOL.'        ]);';
        $eloquentQuery .= PHP_EOL.'}';

        $pages = '\'index\' => Pages\\'.$this->listResourcePageClass.'::route(\'/\'),';
        $pages .= PHP_EOL."'create' => Pages\\".$this->createResourcePageClass."::route('/create'),";
        $pages .= PHP_EOL."'edit' => Pages\\".$this->editResourcePageClass."::route('/{record}/edit'),";

        $relations = PHP_EOL.'public static function getRelations(): array';
        $relations .= PHP_EOL.'{';
        $relations .= PHP_EOL.'    return [';
        $relations .= PHP_EOL.'        //';
        $relations .= PHP_EOL.'    ];';
        $relations .= PHP_EOL.'}'.PHP_EOL;

        $tableActions = [];

        $tableActions[] = 'Tables\Actions\EditAction::make(),';
        $tableActions = implode(PHP_EOL, $tableActions);

        $tableBulkActions = [];
        $tableBulkActions[] = 'Tables\Actions\DeleteBulkAction::make(),';
        $tableBulkActions[] = 'Tables\Actions\ForceDeleteBulkAction::make(),';
        $tableBulkActions[] = 'Tables\Actions\RestoreBulkAction::make(),';
        $tableBulkActions = implode(PHP_EOL, $tableBulkActions);

        $editPageActions = [];
        $editPageActions[] = 'Actions\DeleteAction::make(),';
        $editPageActions[] = 'Actions\ForceDeleteAction::make(),';
        $editPageActions[] = 'Actions\RestoreAction::make(),';
        $editPageActions = implode(PHP_EOL, $editPageActions);

        if ($this->crudData->parent_id && ($this->crudData->parent?->title ?? false)) {
            $navigationGroup = $this->indentString('protected static ?string $navigationGroup = \''.$this->crudData->parent->visual_title.'\';'.PHP_EOL);
        } else {
            $navigationGroup = '';
        }

        if ($this->crudData->icon) {
            $icon = $this->indentString(PHP_EOL.PHP_EOL.'protected static ?string $navigationIcon = \''.$this->crudData->icon->value.'\';'.PHP_EOL);
        } else {
            $icon = '';
        }

        $this->eloquentQuery = $this->indentString($eloquentQuery, 1);
        $this->model = $model === 'Resource' ? 'Resource as ResourceModel' : $model;
        $this->modelClass = $model === 'Resource' ? 'ResourceModel' : $modelClass;
        $this->pages = $this->indentString($pages, 3);
        $this->relations = $this->indentString($relations);
        $this->resource = 'App\\Filament\\Resources\\'.$this->resourceClass;
        $this->tableActions = $this->indentString($tableActions, 4);
        $this->tableBulkActions = $this->indentString($tableBulkActions, 5);
        $this->tableFilters = $this->indentString('Tables\Filters\TrashedFilter::make(),', 4);
        $this->navigationGroup = $navigationGroup;
        $this->icon = $icon;

        // Edit page
        $this->actions = $this->indentString($editPageActions, 3);
    }

    /**
     * @return array<string, string|null>
     */
    public function generateNames(): array
    {
        return [
            'resourceName' => $this->resourceClass,
            'editName' => $this->editResourcePageClass,
            'createName' => $this->createResourcePageClass,
            'listName' => $this->listResourcePageClass,
        ];
    }

    public function retrieveFileGenerator(string $generator): ResourceFile|ListFile|CreateFile|EditFile|FileBase
    {
        return match ($generator) {
            'resource' => new ResourceFile(),
            'list' => new ListFile(),
            'create' => new CreateFile(),
            'edit' => new EditFile(),
            default => throw new NotImplementedException("Generator $generator not implemented"),
        };
    }

    /**
     * @return array<string, string|null>
     */
    public function getReplacementsForResource(): array
    {
        return [
            'eloquentQuery' => $this->eloquentQuery,
            'formSchema' => $this->indentString($this->getFormSchema(), 4),
            'model' => $this->model,
            'modelClass' => $this->modelClass,
            'pages' => $this->pages,
            'relations' => $this->relations,
            'resource' => $this->resource,
            'tableActions' => $this->tableActions,
            'tableBulkActions' => $this->tableBulkActions,
            'tableColumns' => $this->indentString($this->getTableColumns(), 4),
            'tableFilters' => $this->tableFilters,
            'navigationGroup' => $this->navigationGroup,
            'namespace' => 'App\\Filament\\Resources',
            'resourceClass' => $this->resourceClass,
            'icon' => $this->icon,
        ];
    }

    /**
     * @return array<string, string|null>
     */
    public function getReplacementsForCreatePage(): array
    {
        return [
            'baseResourcePage' => 'Filament\\Resources\\Pages\\CreateRecord',
            'baseResourcePageClass' => 'CreateRecord',
            'namespace' => 'App\\Filament\\Resources\\'.$this->crudData->model_class_name.'Resource\\Pages',
            'resourceClass' => $this->resourceClass,
            'resourcePageClass' => $this->createResourcePageClass,
            'resource' => 'App\\Filament\\Resources\\'.$this->resourceClass,
        ];
    }

    /**
     * @return array<string, string|null>
     */
    public function getReplacementsForEditPage(): array
    {
        return [
            'baseResourcePage' => 'Filament\\Resources\\Pages\\EditRecord',
            'baseResourcePageClass' => 'EditRecord',
            'namespace' => 'App\\Filament\\Resources\\'.$this->crudData->model_class_name.'Resource\\Pages',
            'resourceClass' => $this->resourceClass,
            'resourcePageClass' => $this->editResourcePageClass,
            'actions' => $this->actions,
            'resource' => 'App\\Filament\\Resources\\'.$this->resourceClass,
        ];
    }

    /**
     * @return array<string, string|null>
     */
    public function getReplacementsForListPage(): array
    {
        return [
            'baseResourcePage' => 'Filament\\Resources\\Pages\\ListRecords',
            'baseResourcePageClass' => 'ListRecords',
            'namespace' => 'App\\Filament\\Resources\\'.$this->crudData->model_class_name.'Resource\\Pages',
            'resourceClass' => $this->resourceClass,
            'resourcePageClass' => $this->listResourcePageClass,
            'resource' => 'App\\Filament\\Resources\\'.$this->resourceClass,
        ];
    }

    private function getFormSchema(): string
    {
        $formElements = [];

        foreach ($this->crudData->fields as $field) {
            if (! $field->in_create && ! $field->in_edit) {
                continue;
            }

            $formElements[] = RetrieveGeneratorForField::for($field)->formComponent();
        }

        return implode(','.PHP_EOL, $formElements);
    }

    private function getTableColumns(): string
    {
        $tableColumns = [];

        foreach ($this->crudData->fields as $field) {
            if (! $field->in_list) {
                continue;
            }
            $tableColumns[] = RetrieveGeneratorForField::for($field)->tableColumn();
        }

        return implode(','.PHP_EOL, $tableColumns);
    }
}
