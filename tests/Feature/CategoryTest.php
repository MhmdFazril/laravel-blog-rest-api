<?php

use App\Models\Category;
use Database\Seeders\UserSeeder;
use Database\Seeders\CategorySeeder;

use function PHPUnit\Framework\assertFalse;
use function PHPUnit\Framework\assertTrue;
use function PHPUnit\Framework\assertIsBool;
use function PHPUnit\Framework\assertNotEquals;
use function PHPUnit\Framework\assertNull;

use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

// =========== create category ===========
test('Create category success', function () {
    $this->seed([UserSeeder::class]);

    $this->post('/api/category', [
        'name' => 'Kehidupan Masyarakat'
    ], [
        'Authorization' => 'token'
    ])->assertStatus(201)
        ->assertJson([
            'data' => [
                'name' => 'Kehidupan Masyarakat',
                'slug' => 'kehidupan-masyarakat'
            ]
        ]);

    assertTrue(Category::firstWhere('name', 'Kehidupan Masyarakat')->exists());
});

test('Create category error validation', function () {
    $this->seed([UserSeeder::class]);

    $this->post('/api/category', [
        'name' => ''
    ], [
        'Authorization' => 'token'
    ])->assertStatus(400)
        ->assertJson([
            'errors' => [
                'name' => [
                    'The name field is required.'
                ]
            ]
        ]);
});

test('Create category exist', function () {
    $this->seed([UserSeeder::class, CategorySeeder::class]);

    $this->post('/api/category', [
        'name' => 'Sejarah'
    ], [
        'Authorization' => 'token'
    ])->assertStatus(400)
        ->assertJson([
            'errors' => [
                'name' => [
                    'The name has already been taken.'
                ]
            ]
        ]);
});



// =========== detail category ===========
test('Get detail category success', function () {
    $this->seed([UserSeeder::class, CategorySeeder::class]);

    $this->get('/api/category/kehidupan-sosial', [
        'Authorization' => 'token'
    ])->assertStatus(200)
        ->assertJson([
            'data' => [
                'name' => 'Kehidupan Sosial',
                'slug' => 'kehidupan-sosial'
            ]
        ]);
});

test('Get detail category invalid token', function () {
    $this->seed([UserSeeder::class, CategorySeeder::class]);

    $this->get('/api/category/sejarah', [
        'Authorization' => 'salah'
    ])->assertStatus(401)
        ->assertJson([
            'errors' => [
                'messages' => ['Unauthorized']
            ]
        ]);
});

test('Get detail category not found', function () {
    $this->seed([UserSeeder::class, CategorySeeder::class]);

    $this->get('/api/category/olahraga', [
        'Authorization' => 'token'
    ])->assertStatus(404)
        ->assertJson([
            'errors' => [
                'category not found'
            ]
        ]);
});


// =========== detail category ===========
test('Update category success', function () {
    $this->seed([UserSeeder::class, CategorySeeder::class]);

    $oldCategory = Category::firstWhere('slug', 'sejarah');
    $this->put(
        '/api/category/sejarah',
        [
            'name' => 'Olahraga'
        ],
        [
            'Authorization' => 'token'
        ]
    )->assertStatus(200)
        ->assertJson([
            'data' => [
                'name' => 'Olahraga',
                'slug' => 'olahraga'
            ]
        ]);

    $newCategory = Category::firstWhere('slug', 'olahraga');

    assertNotEquals($oldCategory->name, $newCategory->name);
    assertNotEquals($oldCategory->slug, $newCategory->slug);
});

test('Update category not found', function () {
    $this->seed([UserSeeder::class, CategorySeeder::class]);

    $this->put(
        '/api/category/olympic',
        [
            'name' => 'Olahraga'
        ],
        [
            'Authorization' => 'token'
        ]
    )->assertStatus(404)
        ->assertJson([
            'errors' => [
                'category not found'
            ]
        ]);
});


test('Update category error validation', function () {
    $this->seed([UserSeeder::class, CategorySeeder::class]);

    $this->put(
        '/api/category/sejarah',
        [
            'name' => 'Lorem ipsum dolor sit amet consectetur adipiscing elit eleifend, class nibh iaculis odio cras egestas eros po.'
        ],
        [
            'Authorization' => 'token'
        ]
    )->assertStatus(400)
        ->assertJson([
            'errors' => [
                'name' => [
                    'The name field must not be greater than 100 characters.'
                ]
            ]
        ]);
});

test('Update category invalid token', function () {
    $this->seed([UserSeeder::class, CategorySeeder::class]);

    $this->put(
        '/api/category/sejarah',
        [
            'name' => 'test'
        ],
        [
            'Authorization' => 'salah'
        ]
    )->assertStatus(401)
        ->assertJson([
            'errors' => [
                'messages' => [
                    'Unauthorized'
                ]
            ]
        ]);
});



// =========== delete category ===========
test('Delete category success', function () {
    $this->seed([UserSeeder::class, CategorySeeder::class]);

    $this->delete(
        '/api/category/sejarah',
        [],
        ['Authorization' => 'token']
    )->assertStatus(200)
        ->assertJson([
            'data' => true
        ]);

    $category = Category::where('slug', 'sejarah')->exists();
    assertFalse($category);
});

test('Delete category not found', function () {
    $this->seed([UserSeeder::class, CategorySeeder::class]);

    $this->delete(
        '/api/category/memasak',
        [],
        ['Authorization' => 'token']
    )->assertStatus(404)
        ->assertJson([
            'errors' => [
                'category not found'
            ]
        ]);
});

test('Delete category invalid token', function () {
    $this->seed([UserSeeder::class, CategorySeeder::class]);

    $this->delete(
        '/api/category/sejarah',
        [],
        ['Authorization' => 'salah']
    )->assertStatus(401)
        ->assertJson([
            'errors' => [
                'messages' => [
                    'Unauthorized'
                ]
            ]
        ]);
});


// =========== List category ===========
test('List category success', function () {
    $this->seed([UserSeeder::class, CategorySeeder::class]);

    $this->get(
        '/api/category',
        ['Authorization' => 'token']
    )->assertStatus(200)
        ->assertJson([
            'data' => [
                [
                    "name" => "Hiburan",
                    "slug" => "hiburan"
                ],
                [
                    "name" => "Kehidupan Sosial",
                    "slug" => "kehidupan-sosial"
                ],
                [
                    "name" => "Kesehatan",
                    "slug" => "kesehatan"
                ],
                [
                    "name" => "Pendidikan",
                    "slug" => "pendidikan"
                ],
                [
                    "name" => "Sejarah",
                    "slug" => "sejarah"
                ]
            ]
        ]);
});


test('List category empty', function () {
    $this->seed([UserSeeder::class]);

    $this->get(
        '/api/category',
        ['Authorization' => 'token']
    )->assertStatus(404)
        ->assertJson([
            'errors' => [
                'no category data is available at the moment.'
            ]
        ]);
});
