<?php

use App\Models\User;

test('a user can see the login page', function () {
    $this->get('/login')->assertSuccessful();
});

test('a user can login', function () {
    $user = User::factory()->create([
        'password' => bcrypt('password'),
    ]);

    $response = $this->post('/login', [
        'email' => $user->email,
        'password' => 'password',
    ]);

    $this->assertAuthenticatedAs($user);
    $response->assertRedirect('/');
});

test('a user cannot login with incorrect password', function () {
    $user = User::factory()->create([
        'password' => bcrypt('password'),
    ]);

    $this->post('/login', [
        'email' => $user->email,
        'password' => 'wrong-password',
    ]);

    $this->assertGuest();
});

test('a user can logout', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->post('/logout')
        ->assertRedirect('/');

    $this->assertGuest();
});
