<?php

use App\Models\Task;
use App\Models\User;

test('it can have many tasks', function () {
    $user = User::factory()->create();

    expect($user->tasks)->toBeEmpty();

    $user->tasks()->create(Task::factory()->make()->toArray());

    expect($user->fresh()->tasks)->toHaveCount(1);
});
