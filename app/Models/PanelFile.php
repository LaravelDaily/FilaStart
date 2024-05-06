<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class PanelFile extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'panel_id',
        'crud_id',
        'crud_field_id',
        'path',
        'content',
    ];

    public function panel(): BelongsTo
    {
        return $this->belongsTo(Panel::class);
    }

    public function crud(): BelongsTo
    {
        return $this->belongsTo(Crud::class);
    }

    public function crudField(): BelongsTo
    {
        return $this->belongsTo(CrudField::class);
    }
}
