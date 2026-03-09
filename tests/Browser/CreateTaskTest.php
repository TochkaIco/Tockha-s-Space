<?php

declare(strict_types=1);

use App\Models\User;

it('creates a new task', function () {
    $this->actingAs($user = User::factory()->create());
    visit('/tasks')
        ->click('@create-task-button')
        ->fill('@title-field', 'Some Example Title')
        ->fill('@description-field', 'Some Example Description')
        ->click('@button-status-in_progress')
        ->fill('@new-link-field', 'https://tochkaico.org')
        ->click('@add-link-button')
        ->fill('@new-link-field', 'https://laracasts.com')
        ->click('@add-link-button')
        ->click('Create')
        ->assertPathIs('/tasks');

    expect($user->tasks()->first())->toMatchArray([
        'title' => 'Some Example Title',
        'description' => 'Some Example Description',
        'status' => 'in_progress',
    ]);
});
