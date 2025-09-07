<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\User;
use App\Models\Category;
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Post::create([
            'title' => 'Aturan Permainan Sepak Bola',
            'slug' => 'aturan-permainan-sepak-bola',
            'content' => 'Sepak bola adalah olahraga yang dimainkan oleh dua tim, masing-masing terdiri dari sebelas pemain, dengan tujuan mencetak gol sebanyak-banyaknya ke gawang lawan. Permainan ini memiliki sejumlah aturan resmi yang ditetapkan oleh FIFA, seperti durasi permainan selama 2 x 45 menit, larangan menggunakan tangan (kecuali oleh penjaga gawang di area penalti), serta aturan offside, pelanggaran, dan tendangan bebas. Wasit berperan penting dalam mengawasi jalannya pertandingan dan memastikan setiap aturan dipatuhi untuk menjaga sportivitas serta keadilan dalam permainan.',
            'category_id' => Category::where('slug', 'pendidikan')->first()->id,
            'author_id' => User::where('username', 'user2')->first()->id,
        ]);

        Post::create([
            'title' => 'Tutorial Memasak Daging Cincang',
            'slug' => 'tutorial-memasak-daging-cincang',
            'content' => 'Berikut tutorial singkat memasak daging cincang: Panaskan sedikit minyak di wajan, lalu tumis bawang putih dan bawang merah cincang hingga harum. Masukkan daging sapi cincang, aduk hingga berubah warna dan matang merata. Tambahkan garam, merica, saus tiram, atau bumbu sesuai selera, lalu masak beberapa menit hingga bumbu meresap. Daging cincang siap disajikan sebagai isian nasi, mie, atau lauk pendamping.',
            'category_id' => Category::where('slug', 'masak')->first()->id,
            'author_id' => User::where('username', 'user')->first()->id,
        ]);
    }
}
