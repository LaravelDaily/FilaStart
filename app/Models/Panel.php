<?php

namespace App\Models;

use App\Enums\PanelTypes;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property PanelTypes $type
 */
class Panel extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'name',
        'type',
    ];

    /**
     * @return string[]
     */
    protected function casts(): array
    {
        return [
            'type' => PanelTypes::class,
        ];
    }

    public function scopeForUser(Builder $query, User $user): Builder
    {
        return $query->where('user_id', $user->id);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function cruds(): HasMany
    {
        return $this->hasMany(Crud::class);
    }

    public function panelFiles(): HasMany
    {
        return $this->hasMany(PanelFile::class);
    }

    public function modules(): BelongsToMany
    {
        return $this->belongsToMany(Module::class);
    }

    public function panelDeployments(): HasMany
    {
        return $this->hasMany(PanelDeployment::class);
    }
}
