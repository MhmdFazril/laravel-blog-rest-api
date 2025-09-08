<?php

use App\Models\Post;
use App\Models\User;
use App\Models\Category;
use Database\Seeders\PostSeeder;
use Database\Seeders\UserSeeder;
use Illuminate\Support\Facades\Log;

use Database\Seeders\CategorySeeder;

use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertFalse;
use function PHPUnit\Framework\assertNotEquals;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);


// =========== create post ===========
test('Create post success', function () {
	$this->seed([UserSeeder::class, CategorySeeder::class]);

	$this->post('/api/post', [
		'title' => 'Cara Mengolah Daging Kurban',
		'content' => 'Setelah menerima daging kurban, hal pertama yang perlu dilakukan adalah menyimpannya dengan benar. Pastikan daging dicuci bersih dan dikeringkan sebelum disimpan di dalam wadah kedap udara. Jika tidak langsung dimasak, simpan daging di freezer agar tetap segar dan tidak mudah rusak. Pemisahan antara bagian daging, jeroan, dan tulang juga akan memudahkan proses memasak nantinya.',
		'category_id' => Category::where('slug', 'masak')->first()->id
	], [
		'Authorization' => 'token'
	])->assertStatus(201)
		->assertJson([
			'data' => [
				"title" => 'Cara Mengolah Daging Kurban',
				"slug" => 'cara-mengolah-daging-kurban',
				"content" => 'Setelah menerima daging kurban, hal pertama yang perlu dilakukan adalah menyimpannya dengan benar. Pastikan daging dicuci bersih dan dikeringkan sebelum disimpan di dalam wadah kedap udara. Jika tidak langsung dimasak, simpan daging di freezer agar tetap segar dan tidak mudah rusak. Pemisahan antara bagian daging, jeroan, dan tulang juga akan memudahkan proses memasak nantinya.',
				"category_id" => Category::where('slug', 'masak')->first()->id,
				"author_id" => User::where('username', 'user2')->first()->id,
			]
		]);
});

test('Create post error validations', function () {
	$this->seed([UserSeeder::class, CategorySeeder::class]);

	$this->post('/api/post', [
		'title' => '',
		'content' => '',
		'category_id' => Category::where('slug', 'masak')->first()->id
	], [
		'Authorization' => 'token'
	])->assertStatus(400)
		->assertJson([
			'errors' => [
				'content' => [
					'The content field is required.'
				],
				'title' => [
					'The title field is required.'
				]
			]
		]);
});

test('Create post unauthorized', function () {
	$this->seed([UserSeeder::class, CategorySeeder::class]);

	$this->post('/api/post', [
		'title' => 'Cara Mengolah Daging Kurban',
		'content' => 'Setelah menerima daging kurban, hal pertama yang perlu dilakukan adalah menyimpannya dengan benar. Pastikan daging dicuci bersih dan dikeringkan sebelum disimpan di dalam wadah kedap udara. Jika tidak langsung dimasak, simpan daging di freezer agar tetap segar dan tidak mudah rusak. Pemisahan antara bagian daging, jeroan, dan tulang juga akan memudahkan proses memasak nantinya.',
		'category_id' => Category::where('slug', 'masak')->first()->id
	], [
		'Authorization' => 'salah'
	])->assertStatus(401)
		->assertJson([
			'errors' => [
				"messages" => ['Unauthorized'],
			]
		]);
});


// =========== update post ===========
test('Update post success', function () {
	$this->seed([UserSeeder::class, CategorySeeder::class, PostSeeder::class]);
	$oldPost = Post::firstWhere('slug', 'aturan-permainan-sepak-bola');

	$this->patch('/api/post/aturan-permainan-sepak-bola', [
		'title' => 'Kebugaran Jasmani',
		'content' => 'Kebugaran jasmani adalah kemampuan tubuh untuk melakukan aktivitas fisik sehari-hari dengan efisien tanpa merasa lelah berlebihan, serta masih memiliki cadangan energi untuk melakukan kegiatan lainnya. Kebugaran ini mencakup beberapa aspek seperti kekuatan otot, daya tahan, kelenturan, kecepatan, dan koordinasi tubuh. Memiliki kebugaran jasmani yang baik sangat penting untuk menjaga kesehatan, mencegah penyakit, dan meningkatkan kualitas hidup secara keseluruhan.',
		'category_id' => Category::where('slug', 'pendidikan')->first()->id
	], [
		'Authorization' => 'token'
	])->assertStatus(200)
		->assertJson([
			'data' => [
				"title" => 'Kebugaran Jasmani',
				"slug" => 'kebugaran-jasmani',
				"content" => 'Kebugaran jasmani adalah kemampuan tubuh untuk melakukan aktivitas fisik sehari-hari dengan efisien tanpa merasa lelah berlebihan, serta masih memiliki cadangan energi untuk melakukan kegiatan lainnya. Kebugaran ini mencakup beberapa aspek seperti kekuatan otot, daya tahan, kelenturan, kecepatan, dan koordinasi tubuh. Memiliki kebugaran jasmani yang baik sangat penting untuk menjaga kesehatan, mencegah penyakit, dan meningkatkan kualitas hidup secara keseluruhan.',
				"category_id" => Category::where('slug', 'pendidikan')->first()->id,
				"author_id" => User::where('username', 'user2')->first()->id,
			]
		]);

	$newPost = Post::firstWhere('slug', 'kebugaran-jasmani');
	assertNotEquals($oldPost, $newPost);
});

test('Update post error validations', function () {
	$this->seed([UserSeeder::class, CategorySeeder::class, PostSeeder::class]);

	$this->patch('/api/post/aturan-permainan-sepak-bola', [
		'title' => '',
		'content' => 'Kebugaran jasmani adalah kemampuan tubuh untuk melakukan aktivitas fisik sehari-hari dengan efisien tanpa merasa lelah berlebihan, serta masih memiliki cadangan energi untuk melakukan kegiatan lainnya. Kebugaran ini mencakup beberapa aspek seperti kekuatan otot, daya tahan, kelenturan, kecepatan, dan koordinasi tubuh. Memiliki kebugaran jasmani yang baik sangat penting untuk menjaga kesehatan, mencegah penyakit, dan meningkatkan kualitas hidup secara keseluruhan.',
		'category_id' => ''
	], [
		'Authorization' => 'token'
	])->assertStatus(400)
		->assertJson([
			'errors' => [
				'title' => [
					'The title field is required.'
				],
				'category_id' => [
					'The category id field is required.'
				]
			]
		]);
});

test('Update post unauthorized', function () {
	$this->seed([UserSeeder::class, CategorySeeder::class, PostSeeder::class]);

	$this->patch('/api/post/aturan-permainan-sepak-bola', [
		'title' => 'Kebugaran Jasmani',
		'content' => 'Kebugaran jasmani adalah kemampuan tubuh untuk melakukan aktivitas fisik sehari-hari dengan efisien tanpa merasa lelah berlebihan, serta masih memiliki cadangan energi untuk melakukan kegiatan lainnya. Kebugaran ini mencakup beberapa aspek seperti kekuatan otot, daya tahan, kelenturan, kecepatan, dan koordinasi tubuh. Memiliki kebugaran jasmani yang baik sangat penting untuk menjaga kesehatan, mencegah penyakit, dan meningkatkan kualitas hidup secara keseluruhan.',
		'category_id' => Category::where('slug', 'pendidikan')->first()->id
	], [
		'Authorization' => ''
	])->assertStatus(401)
		->assertJson([
			'errors' => [
				"messages" => ['Unauthorized'],
			]
		]);
});

test('Update post not found', function () {
	$this->seed([UserSeeder::class, CategorySeeder::class, PostSeeder::class]);
	$this->patch('/api/post/aturan-permainan-bulutangkis', [
		'title' => 'Kebugaran Jasmani',
		'content' => 'Kebugaran jasmani adalah kemampuan tubuh untuk melakukan aktivitas fisik sehari-hari dengan efisien tanpa merasa lelah berlebihan, serta masih memiliki cadangan energi untuk melakukan kegiatan lainnya. Kebugaran ini mencakup beberapa aspek seperti kekuatan otot, daya tahan, kelenturan, kecepatan, dan koordinasi tubuh. Memiliki kebugaran jasmani yang baik sangat penting untuk menjaga kesehatan, mencegah penyakit, dan meningkatkan kualitas hidup secara keseluruhan.',
		'category_id' => Category::where('slug', 'pendidikan')->first()->id
	], [
		'Authorization' => 'token'
	])->assertStatus(404)
		->assertJson([
			'errors' => [
				'post not found'
			]
		]);
});

test('Update post forbidden', function () {
	$this->seed([UserSeeder::class, CategorySeeder::class, PostSeeder::class]);
	$this->patch('/api/post/tutorial-memasak-daging-cincang', [
		'title' => 'Tongseng rica rica',
		'content' => 'Tongseng rica-rica adalah perpaduan unik antara tongseng berkuah gurih dan bumbu pedas khas rica-rica. Untuk membuatnya, tumis bawang merah, bawang putih, cabai merah, jahe, lengkuas, dan daun jeruk hingga harum. Masukkan potongan daging (bisa kambing, sapi, atau ayam), aduk hingga berubah warna. Tambahkan air secukupnya, masukkan kecap manis, garam, gula, dan sedikit kaldu bubuk. Masak hingga daging empuk dan kuah mengental. Sajikan panas dengan nasi putih dan taburan bawang goreng. Rasanya pedas, gurih, dan sangat menggugah selera!',
		'category_id' => Category::where('slug', 'masak')->first()->id
	], [
		'Authorization' => 'token'
	])->assertStatus(403)
		->assertJson([
			'errors' => [
				'Forbidden: Not the author of this post.'
			]
		]);
});


// =========== show detail post ===========
test('Show post success', function () {
	$this->seed([UserSeeder::class, CategorySeeder::class, PostSeeder::class]);

	$this->get('/api/post/aturan-permainan-sepak-bola', [
		'Authorization' => 'token'
	])->assertStatus(200)
		->assertJson([
			'data' => [
				'title' => 'Aturan Permainan Sepak Bola',
				'slug' => 'aturan-permainan-sepak-bola',
				'content' => 'Sepak bola adalah olahraga yang dimainkan oleh dua tim, masing-masing terdiri dari sebelas pemain, dengan tujuan mencetak gol sebanyak-banyaknya ke gawang lawan. Permainan ini memiliki sejumlah aturan resmi yang ditetapkan oleh FIFA, seperti durasi permainan selama 2 x 45 menit, larangan menggunakan tangan (kecuali oleh penjaga gawang di area penalti), serta aturan offside, pelanggaran, dan tendangan bebas. Wasit berperan penting dalam mengawasi jalannya pertandingan dan memastikan setiap aturan dipatuhi untuk menjaga sportivitas serta keadilan dalam permainan.',
				'category_id' => Category::where('slug', 'pendidikan')->first()->id,
				'author_id' => User::where('username', 'user2')->first()->id,
			]
		]);
});

test('Show post not found', function () {
	$this->seed([UserSeeder::class, CategorySeeder::class, PostSeeder::class]);
	$this->get('/api/post/aturan-permainan-bulutangkis', [
		'Authorization' => 'token'
	])->assertStatus(404)
		->assertJson([
			'errors' => [
				'post not found'
			]
		]);
});


// =========== delete detail post ===========
test('Delete post success', function () {
	$this->seed([UserSeeder::class, CategorySeeder::class, PostSeeder::class]);
	$this->delete('/api/post/aturan-permainan-sepak-bola', [], [
		'Authorization' => 'token'
	])->assertStatus(200)
		->assertJson([
			'data' => true
		]);

	$post = Post::where('slug', 'aturan-permainan-sepak-bola')->exists();
	assertFalse($post);
});

test('Delete post not found', function () {
	$this->seed([UserSeeder::class, CategorySeeder::class, PostSeeder::class]);
	$this->delete('/api/post/aturan-permainan-bulutangkis', [], [
		'Authorization' => 'token'
	])->assertStatus(404)
		->assertJson([
			'errors' => [
				'post not found'
			]
		]);
});



// =========== list and search detail post ===========
test('List all post', function () {
	$this->seed([UserSeeder::class, CategorySeeder::class, PostSeeder::class]);

	$response = $this->get('/api/post', [
		'Authorization' => 'token'
	])->assertStatus(200)
		->json();

	// Log::info(json_encode($response, JSON_PRETTY_PRINT));
	assertEquals(5, count($response['data']));
	assertEquals(5, $response['meta']['total']);
});

test('Search post by title', function () {
	$this->seed([UserSeeder::class, CategorySeeder::class, PostSeeder::class]);

	$response = $this->get('/api/post?title=daging', [
		'Authorization' => 'token'
	])->assertStatus(200)
		->json();

	// Log::info(json_encode($response, JSON_PRETTY_PRINT));
	assertEquals(1, count($response['data']));
	assertEquals(1, $response['meta']['total']);
});

test('Search post by author', function () {
	$this->seed([UserSeeder::class, CategorySeeder::class, PostSeeder::class]);

	$response = $this->get('/api/post?author=user2', [
		'Authorization' => 'token'
	])->assertStatus(200)
		->json();

	// Log::info(json_encode($response, JSON_PRETTY_PRINT));
	assertEquals(4, count($response['data']));
	assertEquals(4, $response['meta']['total']);
});

test('Search post by slug', function () {
	$this->seed([UserSeeder::class, CategorySeeder::class, PostSeeder::class]);

	$response = $this->get('/api/post?slug=-darah', [
		'Authorization' => 'token'
	])->assertStatus(200)
		->json();

	// Log::info(json_encode($response, JSON_PRETTY_PRINT));
	assertEquals(1, count($response['data']));
	assertEquals(1, $response['meta']['total']);
});

test('Search post by category', function () {
	$this->seed([UserSeeder::class, CategorySeeder::class, PostSeeder::class]);

	$response = $this->get('/api/post?category=pendidikan', [
		'Authorization' => 'token'
	])->assertStatus(200)
		->json();

	// Log::info(json_encode($response, JSON_PRETTY_PRINT));
	assertEquals(3, count($response['data']));
	assertEquals(3, $response['meta']['total']);
});

test('Search post by category and title', function () {
	$this->seed([UserSeeder::class, CategorySeeder::class, PostSeeder::class]);

	$response = $this->get('/api/post?category=pendidikan&title=olahraga', [
		'Authorization' => 'token'
	])->assertStatus(200)
		->json();

	// Log::info(json_encode($response, JSON_PRETTY_PRINT));
	assertEquals(1, count($response['data']));
	assertEquals(1, $response['meta']['total']);
});

test('Search post not found', function () {
	$this->seed([UserSeeder::class, CategorySeeder::class, PostSeeder::class]);

	$response = $this->get('/api/post?title=basket', [
		'Authorization' => 'token'
	])->assertStatus(200)
		->json();

	// Log::info(json_encode($response, JSON_PRETTY_PRINT));
	assertEquals(0, count($response['data']));
	assertEquals(0, $response['meta']['total']);
});

test('Search post size', function () {
	$this->seed([UserSeeder::class, CategorySeeder::class, PostSeeder::class]);

	$response = $this->get('/api/post?size=2', [
		'Authorization' => 'token'
	])->assertStatus(200)
		->json();

	Log::info(json_encode($response, JSON_PRETTY_PRINT));
	assertEquals(2, count($response['data']));
	assertEquals(5, $response['meta']['total']);
});

test('Search post size and page', function () {
	$this->seed([UserSeeder::class, CategorySeeder::class, PostSeeder::class]);

	$response = $this->get('/api/post?size=2&page=3', [
		'Authorization' => 'token'
	])->assertStatus(200)
		->json();

	Log::info(json_encode($response, JSON_PRETTY_PRINT));
	assertEquals(1, count($response['data']));
	assertEquals(5, $response['meta']['total']);
});
