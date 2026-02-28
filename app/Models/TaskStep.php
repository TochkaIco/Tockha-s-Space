<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TaskStep extends Model
{
    /** @use HasFactory<\Database\Factories\TaskStepFactory> */
    use HasFactory;

    protected $attributes = [
        'completed' => false,
    ];

    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class);
    }
}
