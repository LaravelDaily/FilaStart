<?php

namespace App\Models;

use App\Enums\CrudTypes;
use App\Enums\HeroIcons;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property CrudTypes $type
 * @property bool $is_hidden
 * @property bool $system
 * @property bool $module_crud
 * @property HeroIcons|null $icon
 * @property string $model_class_name
 * @property string $model_snake_plural_class_name
 */
class Crud extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'panel_id',
        'user_id',
        'parent_id',
        'type',
        'title',
        'visual_title',
        'icon',
        'menu_order',
        'is_hidden',
        'module_crud',
        'module_slug',
        'module_order',
        'system',
    ];

    /**
     * @return string[]
     */
    protected function casts(): array
    {
        return [
            'is_hidden' => 'boolean',
            'system' => 'boolean',
            'module_crud' => 'boolean',
            'type' => CrudTypes::class,
            'icon' => HeroIcons::class,
        ];
    }

    protected static function booted(): void
    {
        self::creating(static function (Crud $crud) {
            if (! $crud->title) {
                $crud->title = str($crud->visual_title)
                    ->camel()
                    ->singular()
                    ->ucfirst()
                    ->toString();
            }
        });
    }

    protected function icon(): Attribute
    {
        return Attribute::make(
            set: fn (HeroIcons|string|null $value) => ! $value ? HeroIcons::O_RECTANGLE_STACK : $value,
        );
    }

    public function scopeParent(Builder $query): Builder
    {
        return $query->where('type', CrudTypes::PARENT);
    }

    public function panel(): BelongsTo
    {
        return $this->belongsTo(Panel::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Crud::class);
    }

    public function fields(): HasMany
    {
        return $this->hasMany(CrudField::class);
    }

    public function panelFiles(): HasMany
    {
        return $this->hasMany(PanelFile::class);
    }

    protected function modelClassName(): Attribute
    {
        return Attribute::make(
            get: fn () => str($this->title)->singular()->studly()->toString(),
        );
    }

    protected function modelSnakePluralClassName(): Attribute
    {
        return Attribute::make(
            get: fn () => str($this->title)->plural()->snake()->toString(),
        );
    }
}
