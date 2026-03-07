<?php

declare(strict_types=1);

use App\Models\User;

test('logs in a user', function () {
    $user = User::factory()->create([
        'password' => bcrypt('password123!@#'),
    ]);

    visit('/login')
        ->fill('email', $user->email)
        ->fill('password', 'password123!@#')
        ->click('@login-button')
        ->assertPathIs('/');

    $this->assertAuthenticated();
});

test('logs out a user', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    visit('/')
        ->click('@logout-button')
        ->assertPathIs('/');

    $this->assertGuest();
});

test('requires a valid email', function () {
    $user = User::factory()->create();

    visit('/login')
        ->fill('email', 'invalid@email.com')
        ->fill('password', $user->password)
        ->click('@login-button')
        ->assertPathIs('/login');

    $this->assertGuest();
});

test('requires a valid password', function () {
    $user = User::factory()->create([
        'password' => bcrypt('password123!@#'),
    ]);

    visit('/login')
        ->fill('email', $user->email)
        ->fill('password', 'incorrect-password')
        ->click('@login-button')
        ->assertPathIs('/login');

    $this->assertGuest();
});
