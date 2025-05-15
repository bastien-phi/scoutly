<?php

declare(strict_types=1);

use App\Models\User;

test('registration screen can be rendered', function () {
    $response = $this->get('/register');

    $response->assertStatus(200);
});

test('new users can register', function () {
    $response = $this->post('/register', [
        'name' => 'Test User',
        'username' => 'testuser',
        'email' => 'test@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $this->assertAuthenticated();
    $response->assertRedirect(route('dashboard', absolute: false));
});

test('new users cannot register using invalid username', function (string $username, string $error) {
    $response = $this->post('/register', [
        'name' => 'Test User',
        'username' => $username,
        'email' => 'test@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $response->assertRedirectBack()
        ->assertInvalid([
            'username' => $error,
        ]);
})->with([
    'username too short' => [
        'Foo',
        'The username field must be at least 4 characters.',
    ],
    'username too long' => [
        str_repeat('a', 51),
        'The username field must not be greater than 50 characters.',
    ],
    'username missing letters' => [
        '1234',
        'The username field must contain at least 2 letters.',
    ],
    'username contains invalid characters' => [
        'test user',
        'The username field may only contain letters, numbers, and underscores.',
    ],
    'username uses reserved word' => [
        'admin',
        'The username is reserved.',
    ],
]);

test('new users cannot register using existing username', function () {
    User::factory()->createOne([
        'username' => 'testuser',
    ]);

    $response = $this->post('/register', [
        'name' => 'Test User',
        'username' => 'TestUser',
        'email' => 'test@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $response->assertRedirectBack()
        ->assertInvalid([
            'username' => 'The username has already been taken.',
        ]);
});
