<?php

use App\Models\Task;
use App\Models\User;

test('must be signed in to view a task', function () {
    $task = Task::factory()->create();

    $this->get(route('task.show', $task))
        ->assertRedirectToRoute('login');
});

it('disallows accessing a task you did not create', function () {
    $task = Task::factory()->create();

    $this->actingAs($user = User::factory()->create());

    $this->get(route('task.show', $task))
        ->assertForbidden();
});

it('disallows deleting a task you did not create', function () {
    $task = Task::factory()->create();

    $this->actingAs($user = User::factory()->create());

    $this->get(route('task.destroy', $task))
        ->assertForbidden();
});
