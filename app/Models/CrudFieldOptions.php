<?php

namespace App\Models;

use App\Enums\CrudFieldTypes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property string|null $relationship
 * @property-read CrudField $relatedCrudField
 */
class CrudFieldOptions extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'crud_field_id',
        'crud_id',
        'related_crud_field_id',
        'relationship',
    ];

    protected static function booted(): void
    {
        self::creating(function (CrudFieldOptions $fieldOptions) {
            if ($fieldOptions->crudField) {
                if ($fieldOptions->crudField->type === CrudFieldTypes::BELONGS_TO_MANY) {
                    $fieldOptions->relationship = str($fieldOptions->crud?->title)->snake()->plural()->toString();
                } else {
                    $fieldOptions->relationship = str($fieldOptions->crud?->title)->snake()->singular()->toString();
                }
            }
        });
    }

    public function crudField(): BelongsTo
    {
        return $this->belongsTo(CrudField::class);
    }

    public function relatedCrudField(): BelongsTo
    {
        return $this->belongsTo(CrudField::class, 'related_crud_field_id');
    }

    public function crud(): BelongsTo
    {
        return $this->belongsTo(Crud::class);
    }
}
