<?php

use App\Models\User;

test('a user can see the registration page', function () {
    $this->get('/register')->assertSuccessful();
});

test('a user can register', function () {
    $response = $this->post('/register', [
        'username' => 'johndoe',
        'email' => 'john@example.com',
        'password' => 'password123',
        'password_confirmation' => 'password123',
    ]);

    $this->assertAuthenticated();
    $this->assertDatabaseHas('users', [
        'username' => 'johndoe',
        'email' => 'john@example.com',
    ]);
    $response->assertRedirect('/');
});

test('registration requires username, email and password', function () {
    $this->post('/register', [])
        ->assertSessionHasErrors(['username', 'email', 'password']);
});

test('username must be unique', function () {
    User::factory()->create(['username' => 'johndoe']);

    $this->post('/register', [
        'username' => 'johndoe',
        'email' => 'john@example.com',
        'password' => 'password123',
        'password_confirmation' => 'password123',
    ])->assertSessionHasErrors(['username']);
});

test('password must be at least 8 characters', function () {
    $this->post('/register', [
        'username' => 'johndoe',
        'email' => 'john@example.com',
        'password' => 'short',
        'password_confirmation' => 'short',
    ])->assertSessionHasErrors(['password']);
});

test('password must be confirmed', function () {
    $this->post('/register', [
        'username' => 'johndoe',
        'email' => 'john@example.com',
        'password' => 'password123',
        'password_confirmation' => 'different-password',
    ])->assertSessionHasErrors(['password']);
});
