<?php

declare(strict_types=1);

namespace App\Actions;

use App\Models\User;
use Illuminate\Container\Attributes\CurrentUser;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CreateTask
{
    public function __construct(#[CurrentUser] protected User $user)
    {
        //
    }

    public function handle(array $attributes): void
    {
        $data = collect($attributes)->only([
            'title', 'description', 'status', 'links',
        ])->toArray();

        if ($attributes['image'] ?? false) {
            $data['image_path'] = $attributes['image']->store('tasks', 'public');
        }

        DB::transaction(function () use ($data, $attributes) {
            $task = $this->user->tasks()->create($data);

            $steps = collect($attributes['steps'] ?? [])->map(fn ($step) => ['description' => $step]);

            $task->steps()->createMany($steps);
        });
    }
}
