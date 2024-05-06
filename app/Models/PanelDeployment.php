<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class PanelDeployment extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'panel_id',
        'deployment_id',
        'status',
        'file_path',
        'deployment_log',
    ];

    public function panel(): BelongsTo
    {
        return $this->belongsTo(Panel::class);
    }

    public function addNewMessage(string $message): void
    {
        $log = $this->fresh();

        if (! $log) {
            return;
        }

        $log->deployment_log .= $message;
        $log->save();
    }
}
