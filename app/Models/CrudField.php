<?php

namespace App\Models;

use App\Enums\CrudFieldTypes;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property CrudFieldTypes $type
 * @property string $key
 * @property bool $in_list
 * @property bool $in_show
 * @property bool $in_create
 * @property bool $in_edit
 * @property bool $nullable
 * @property bool $system
 * @property bool $enabled
 * @property string $form_key_name
 * @property string $table_key_name
 */
class CrudField extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'panel_id',
        'crud_id',
        'type',
        'key',
        'label',
        'validation',
        'in_list',
        'in_show',
        'in_create',
        'in_edit',
        'nullable',
        'tooltip',
        'system',
        'enabled',
        'order',
    ];

    /**
     * @return string[]
     */
    protected function casts(): array
    {
        return [
            'type' => CrudFieldTypes::class,
            'in_list' => 'boolean',
            'in_show' => 'boolean',
            'in_create' => 'boolean',
            'in_edit' => 'boolean',
            'nullable' => 'boolean',
            'system' => 'boolean',
            'enabled' => 'boolean',
        ];
    }

    protected static function booted(): void
    {
        self::creating(function (CrudField $field) {
            if ($field->type === CrudFieldTypes::BELONGS_TO) {
                $field->key = str($field->label)
                    ->lower()
                    ->snake()
                    ->toString().'_id';
            } else {
                $field->key = str($field->label)
                    ->lower()
                    ->snake()
                    ->toString();
            }
        });
    }

    public function crud(): BelongsTo
    {
        return $this->belongsTo(Crud::class);
    }

    public function panel(): BelongsTo
    {
        return $this->belongsTo(Panel::class);
    }

    public function crudFieldOptions(): HasOne
    {
        return $this->hasOne(CrudFieldOptions::class);
    }

    public function panelFiles(): HasMany
    {
        return $this->hasMany(PanelFile::class);
    }

    protected function formKeyName(): Attribute
    {
        return Attribute::make(
            get: function (): string {
                if (! str($this->key)->endsWith('_id') && $this->type === CrudFieldTypes::BELONGS_TO) {
                    return str($this->key.'_id')->lower()->snake();
                }

                return str($this->key)->lower()->snake();
            },
        );
    }

    protected function tableKeyName(): Attribute
    {
        return Attribute::make(
            get: function (): string {
                $crudFieldOptions = $this->crudFieldOptions;

                if (! $crudFieldOptions) {
                    return str($this->key)->lower()->snake();
                }

                return sprintf(
                    '%s.%s',
                    $crudFieldOptions->relationship,
                    $crudFieldOptions->relatedCrudField->key
                );
            },
        );
    }
}
