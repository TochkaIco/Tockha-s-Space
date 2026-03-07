<?php

declare(strict_types=1);

test('registers and log in user', function () {
    visit('/register')
        ->fill('username', 'test-user')
        ->fill('email', 'testuser@example.com')
        ->fill('password', 'password123!@#')
        ->fill('password_confirmation', 'password123!@#')
        ->click('@register-button')
        ->assertPathIs('/');

    $this->assertAuthenticated();

    expect(Auth::user())->toMatchArray([
        'username' => 'test-user',
        'email' => 'testuser@example.com',
    ]);
});

test('requires a valid email to register', function () {
    visit('/register')
        ->fill('username', 'test-user')
        ->fill('email', 'testuserexample.com')
        ->fill('password', 'password123!@#')
        ->fill('password_confirmation', 'password123!@#')
        ->click('@register-button')
        ->assertPathIs('/register');

    $this->assertGuest();
});
