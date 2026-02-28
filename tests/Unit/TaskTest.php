<?php

use App\Models\Task;
use App\Models\User;
use Database\Factories\TaskStepFactory;
use Illuminate\Database\Eloquent\Collection;

test('it belongs to a user', function () {
    $task = Task::factory()->create();

    expect($task->user)->toBeInstanceOf(User::class);
});

test('it can have steps', function () {
    $task = Task::factory()->create();

    expect($task->steps)->toBeEmpty();

    $task->steps()->create([
        'description' => fake()->sentence(),
    ]);

    expect($task->fresh()->steps)->toHaveCount(1);
});
