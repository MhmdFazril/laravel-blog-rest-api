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

        Post::create([
            'title' => 'Revolusi Pracis',
            'slug' => 'revolusi-prancis',
            'content' => 'Revolusi Prancis (1789–1799) adalah perubahan sosial dan politik besar di Prancis yang menggulingkan monarki absolut dan feodalisme. Dipicu oleh krisis ekonomi, ketimpangan sosial, dan pengaruh pemikiran Pencerahan, rakyat bangkit melawan kekuasaan Raja Louis XVI. Revolusi ini ditandai dengan peristiwa penting seperti penyerbuan Penjara Bastille, deklarasi Hak Asasi Manusia dan Warga Negara, serta eksekusi raja dan ratu. Revolusi mengakhiri kekuasaan bangsawan, membawa ide-ide kebebasan, kesetaraan, dan demokrasi, serta membuka jalan bagi munculnya Napoleon Bonaparte.',
            'category_id' => Category::where('slug', 'pendidikan')->first()->id,
            'author_id' => User::where('username', 'user2')->first()->id,
        ]);

        Post::create([
            'title' => 'Apa Itu Olahraga Padel',
            'slug' => 'apa-itu-olahraga-padel',
            'content' => 'Padel adalah olahraga raket yang menggabungkan elemen tenis dan squash, dimainkan di lapangan berdinding yang lebih kecil dari lapangan tenis. Olahraga ini dimainkan secara ganda (dua lawan dua) dan menggunakan raket padel khusus (tanpa senar) serta bola yang mirip bola tenis, tetapi dengan tekanan udara yang lebih rendah.',
            'category_id' => Category::where('slug', 'pendidikan')->first()->id,
            'author_id' => User::where('username', 'user2')->first()->id,
        ]);

        Post::create([
            'title' => 'Cuci Darah ',
            'slug' => 'cuci-darah',
            'content' => 'Cuci darah, atau dalam istilah medis disebut hemodialisis, adalah prosedur yang dilakukan untuk menggantikan fungsi ginjal yang rusak atau tidak lagi bekerja secara normal. Proses ini bertujuan untuk membersihkan darah dari zat-zat sisa metabolisme, kelebihan cairan, dan racun yang seharusnya dibuang oleh ginjal.',
            'category_id' => Category::where('slug', 'kesehatan')->first()->id,
            'author_id' => User::where('username', 'user2')->first()->id,
        ]);
    }
}
