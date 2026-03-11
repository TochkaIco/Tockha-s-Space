<?php

declare(strict_types=1);

namespace App\Actions;

use App\Models\Task;
use Illuminate\Support\Facades\DB;

class UpdateTask
{
    public function handle(array $attributes, Task $task): void
    {
        $data = collect($attributes)->only([
            'title', 'description', 'status', 'links',
        ])->toArray();

        if ($attributes['image'] ?? false) {
            $data['image_path'] = $attributes['image']->store('tasks', 'public');
        }

        DB::transaction(function () use ($task, $data, $attributes) {
            $task->update($data);

            $task->steps()->delete();
            $task->steps()->createMany($attributes['steps'] ?? []);
        });
    }
}
