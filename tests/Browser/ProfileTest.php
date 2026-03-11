<?php

use App\Models\User;
use App\Notifications\EmailChanged;

it('requires authentication to edit a profile', function () {
    $this->get(route('profile.edit'))
        ->assertRedirectToRoute('login');
});

it('edits a profile', function () {
    $this->actingAs($user = User::factory()->create());

    visit(route('profile.edit'))
        ->assertValue('username', $user->username)
        ->fill('username', 'new_user')
        ->assertValue('email', $user->email)
        ->fill('email', 'new@example.com')
        ->click('Save')
        ->assertSee('Profile Updated!');

    expect($user->fresh())->toMatchArray([
        'username' => 'new_user',
        'email' => 'new@example.com',
    ]);
});

it('notifies the original email if updated', function () {
    $this->actingAs($user = User::factory()->create());

    Notification::fake();

    $originalEmail = $user->email;

    visit(route('profile.edit'))
        ->assertValue('username', $user->username)
        ->assertValue('email', $user->email)
        ->fill('email', 'new@example.com')
        ->click('Save')
        ->assertSee('Profile Updated!');

    Notification::assertSentOnDemand(EmailChanged::class, function (EmailChanged $notification, $routes, $notifiable) use ($originalEmail): bool {
        return $notifiable->routes['mail'] === $originalEmail;
    });
});
