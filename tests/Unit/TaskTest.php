<?php

use App\Models\Task;
use App\Models\User;

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

test('it can format a description using markdown', function () {
    $task = new Task(['description' => 'Hello *world*!']);

    expect($task->formattedDescription)->toEqual("<p>Hello <em>world</em>!</p>\n");
});
