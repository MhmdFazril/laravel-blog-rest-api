<?php

use App\Models\User;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function PHPUnit\Framework\assertNotEquals;
use function PHPUnit\Framework\assertNotNull;
use function PHPUnit\Framework\assertNull;

uses(RefreshDatabase::class);

// =========== User register ==============
test('User register success', function () {
    $this->post('/api/users/register', [
        'name' => 'Budi',
        'username' => 'budi',
        'email' => 'budi@mail.com',
        'password' => 'budi1234'
    ])->assertStatus(201)
        ->assertJson([
            'data' => [
                'name' => 'Budi',
                'username' => 'budi',
                'email' => 'budi@mail.com',
            ]
        ]);
});

test('User register without password', function () {
    $this->post('/api/users/register', [
        'name' => 'Budi',
        'username' => 'budi',
        'email' => 'budi@mail.com',
    ])->assertStatus(400)
        ->assertJson([
            'errors' => [
                'password' => [
                    'The password field is required.'
                ]
            ]
        ]);
});

test('User register with incorrect email', function () {
    $this->post('/api/users/register', [
        'name' => 'budi',
        'username' => 'budi',
        'email' => 'budimail.com',
        'password' => 'budi1234'
    ])->assertStatus(400)
        ->assertJson([
            'errors' => [
                'email' => [
                    'The email field must be a valid email address.'
                ]
            ]
        ]);
});

test('User register email already exist', function () {
    $this->seed([UserSeeder::class]);

    $this->post('/api/users/register', [
        'name' => 'User',
        'username' => 'user',
        'email' => 'user@mail.com',
        'password' => 'user11234'
    ])->assertStatus(400)
        ->assertJson([
            "errors" => [
                'email' => [
                    'The email has already been taken.'
                ]
            ]
        ]);
});


// =========== User login ==============
test('User login success', function () {
    $this->seed([UserSeeder::class]);

    $this->post('/api/users/login', [
        'username' => 'user',
        'password' => 'user1234'
    ])->assertStatus(200)
        ->assertJson([
            'data' => [
                'name' => 'User',
                'username' => 'user',
                'email' => 'user@mail.com',
            ]
        ]);

    $user = User::firstWhere('username', 'user');
    assertNotNull($user->token);
});

test('User login failed wrong password', function () {
    $this->seed([UserSeeder::class]);

    $this->post('/api/users/login', [
        'username' => 'user',
        'password' => 'salah1234'
    ])->assertStatus(401)
        ->assertJson([
            "errors" => [
                'messages' => [
                    'username or password wrong'
                ]
            ]
        ]);
});

test('User login without username', function () {
    $this->seed([UserSeeder::class]);

    $this->post('/api/users/login', [
        'username' => '',
        'password' => 'user1234'
    ])->assertStatus(400)
        ->assertJson([
            "errors" => [
                'username' => [
                    'The username field is required.'
                ]
            ]
        ]);
});

test('User login without password', function () {
    $this->seed([UserSeeder::class]);

    $this->post('/api/users/login', [
        'username' => 'user',
        'password' => ''
    ])->assertStatus(400)
        ->assertJson([
            "errors" => [
                'password' => [
                    'The password field is required.'
                ]
            ]
        ]);
});


// =========== Get current user ==============
test('Get current user success', function () {
    $this->seed([UserSeeder::class]);

    $this->get('/api/users/current', [
        'Authorization' => 'token'
    ])->assertStatus(200)
        ->assertJson([
            "data" => [
                'name' => 'User2',
                'username' => 'user2',
                'email' => 'user2@mail.com',
            ]
        ]);
});

test('Get current user without token', function () {
    $this->seed([UserSeeder::class]);

    $this->get('/api/users/current', [
        'Authorization' => ''
    ])->assertStatus(401)
        ->assertJson([
            "errors" => [
                "messages" => [
                    "Unauthorized"
                ]
            ]
        ]);
});


// =========== Update current user ==============
test('User update success', function () {
    $this->seed([UserSeeder::class]);
    $oldUser = User::firstWhere('username', 'user2');

    $this->patch('/api/users/current', [
        'name' => 'Budi',
    ], [
        'Authorization' => 'token'
    ])->assertStatus(200)
        ->assertJson([
            'data' => [
                'name' => 'Budi',
                'username' => 'user2',
                'email' => 'user2@mail.com',
            ]
        ]);

    $newUser = User::firstWhere('username', 'user2');
    assertNotEquals($oldUser->name, $newUser->name);
});

test('User update failed invalid token', function () {
    $this->seed([UserSeeder::class]);

    $this->patch('/api/users/current', [
        'name' => 'Budi',
        'email' => 'test@mail.com',
    ], [
        'Authorization' => 'salah'
    ])->assertStatus(401)
        ->assertJson([
            'errors' => [
                'messages' => ['Unauthorized']
            ]
        ]);
});

test('User update wrong validations', function () {
    $this->seed([UserSeeder::class]);

    $this->patch('/api/users/current', [
        'name' => 'Budi',
        'email' => 'test@mail.com',
        'password' => '123'
    ], [
        'Authorization' => 'token'
    ])->assertStatus(400)
        ->assertJson([
            'errors' => [
                'password' => ['The password field must be at least 8 characters.']
            ]
        ]);
});


// =========== Update current user ==============
test('User logout success', function () {
    $this->seed([UserSeeder::class]);

    $this->delete('/api/users/logout', [], [
        'Authorization' => 'token'
    ])->assertStatus(200)
        ->assertJson([
            'data' => true
        ]);

    $user = User::firstWhere('username', 'user2');
    assertNull($user->token);
});

test('User logout failed invalid token', function () {
    $this->seed([UserSeeder::class]);

    $this->delete('/api/users/logout', [], [
        'Authorization' => 'salah'
    ])->assertStatus(401)
        ->assertJson([
            'errors' => [
                'messages' => ['Unauthorized']
            ]
        ]);
});
