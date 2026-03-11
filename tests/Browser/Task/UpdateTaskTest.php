<?php

declare(strict_types=1);

use App\Models\Task;
use App\Models\User;

it('shows the initial input state', function () {
    $this->actingAs($user = User::factory()->create());

    $task = Task::factory()->for($user)->create([
        'links' => ['https://tochkaico.org', 'https://github.com/TochkaIco'],
    ]);

    visit(route('task.show', $task))
        ->click('@edit-task-button')
        ->assertValue('@title-field', $task->title)
        ->assertValue('@description-field', $task->description)
        ->assertValue('status', $task->status->value)
        ->assertSee('https://tochkaico.org')
        ->assertSee('https://github.com/TochkaIco');
});

it('edits an existing task', function () {
    $this->actingAs($user = User::factory()->create());

    $task = Task::factory()->for($user)->create();

    visit(route('task.show', $task))
        ->click('@edit-task-button')
        ->fill('@title-field', 'Some Example Title')
        ->fill('@description-field', 'Some Example Description')
        ->click('@button-status-in_progress')
        ->fill('@new-step-field', 'Step 1')
        ->click('@add-step-button')
        ->fill('@new-step-field', 'Step 2')
        ->click('@add-step-button')
        ->fill('@new-link-field', 'https://tochkaico.org')
        ->click('@add-link-button')
        ->fill('@new-link-field', 'https://github.com/TochkaIco')
        ->click('@add-link-button')
        ->click('@submit-button')
        ->assertRoute('task.show', [$task]);

    expect($task = $user->fresh()->tasks()->first())->toMatchArray([
        'title' => 'Some Example Title',
        'description' => 'Some Example Description',
        'status' => 'in_progress',
        'links' => [$task->links[0], 'https://tochkaico.org', 'https://github.com/TochkaIco'],
    ])
        ->and($task->steps)->toHaveCount(2);

});
