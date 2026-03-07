<?php

use App\Models\Task;
use App\Models\TaskStep;

test('it belongs to a task', function () {
    $step = TaskStep::factory()->create();

    expect($step->task)->toBeInstanceOf(Task::class);
});

test('it is not completed by default', function () {
    $step = TaskStep::factory()->create();

    expect($step->completed)->toBeFalse();
});
