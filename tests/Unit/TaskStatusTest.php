<?php

use App\TaskStatus;

test('it has correct labels', function () {
    expect(TaskStatus::PENDING->label())->toBe('Pending');
    expect(TaskStatus::IN_PROGRESS->label())->toBe('In Progress');
    expect(TaskStatus::COMPLETED->label())->toBe('Completed');
});

test('it can be instantiated from values', function () {
    expect(TaskStatus::from('pending'))->toBe(TaskStatus::PENDING);
    expect(TaskStatus::from('in_progress'))->toBe(TaskStatus::IN_PROGRESS);
    expect(TaskStatus::from('completed'))->toBe(TaskStatus::COMPLETED);
});
