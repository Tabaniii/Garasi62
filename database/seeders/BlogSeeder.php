<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Blog;
use App\Models\User;
use Illuminate\Support\Str;

class BlogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get admin user or first user
        $user = User::where('role', 'admin')->first() 
            ?? User::where('role', 'seller')->first()
            ?? User::first();

        if (!$user) {
            $this->command->warn('Tidak ada user ditemukan. Silakan buat user terlebih dahulu.');
            return;
        }

        $blogs = [
            [
                'title' => 'Evolusi Livery Hitam-Hijau: Mobil F1 Modern dengan Aura Stealth',
                'slug' => 'evolusi-livery-hitam-hijau-mobil-f1-modern-dengan-aura-stealth',
                'image' => 'blogs/f1-black-green-2026.jpg', // User perlu upload gambar dengan nama ini
                'author' => 'Garasi62 Editorial',
                'excerpt' => 'Mobil F1 hitam-hijau ini memadukan desain stealth dengan aksen turquoise yang tajam, menonjolkan identitas modern dan agresif di lintasan.',
                'category' => 'F1 2026',
                'tags' => ['formula 1', 'mobil f1', 'livery', 'mercedes', 'mobil balap'],
                'content' => '<p>Livery hitam-hijau pada mobil F1 terbaru ini menegaskan arah desain modern yang serba minimalis namun tetap agresif. Dominasi warna hitam matte dipadukan dengan aksen hijau turquoise yang tajam di area sidepod, sayap depan, dan garis bodi utama, menciptakan kesan <em>stealth fighter</em> yang siap menyerang kapan saja.</p>

<h3>Aerodinamika yang Lebih Bersih</h3>
<p>Dari sisi bentuk, mobil ini tampak mengutamakan aliran udara yang bersih di sepanjang bodi. Garis-garis halus di bagian hidung dan sidepod membuat aliran udara mengalir mulus ke arah diffuser belakang. Desain ini tidak hanya terlihat futuristik, tetapi juga mengisyaratkan fokus tim pada efisiensi downforce di era regulasi baru.</p>

<h3>Detail Sponsor yang Elegan</h3>
<p>Menariknya, penempatan logo sponsor tidak terasa berlebihan. Warna logo dibiarkan kontras terhadap bodi hitam, namun tetap selaras dengan nuansa hijau elektrik yang menjadi identitas utama. Hasilnya adalah tampilan yang bersih, premium, dan mudah dikenali saat melaju kencang di lintasan malam.</p>

<h3>Identitas Baru, Ambisi Baru</h3>
<p>Livery ini seperti pernyataan tegas bahwa tim memasuki musim baru dengan ambisi yang lebih besar. Kombinasi warna gelap, aksen neon, dan komposisi logo yang rapi menjadikan mobil ini salah satu kandidat terkuat untuk menyabet predikat mobil dengan tampilan paling keren musim ini.</p>',
                'status' => 'published',
                'published_at' => now(),
                'comment_count' => 0,
            ],
            [
                'title' => 'Sentuhan Perak dan Oranye: Livery Futuristik di Era Baru F1',
                'slug' => 'sentuhan-perak-dan-oranye-livery-futuristik-di-era-baru-f1',
                'image' => 'blogs/f1-silver-orange-2026.jpg', // User perlu upload gambar dengan nama ini
                'author' => 'Garasi62 Editorial',
                'excerpt' => 'Perpaduan perak metalik dan oranye menyala membuat mobil F1 ini terlihat tajam, futuristik, dan sangat mudah dikenali di lintasan.',
                'category' => 'F1 2026',
                'tags' => ['formula 1', 'livery', 'audi', 'f1 2026', 'desain mobil'],
                'content' => '<p>Mobil F1 dengan livery perak-oranye ini menampilkan perpaduan yang sangat khas: elegan namun tetap agresif. Warna perak metalik memberikan kesan teknologi tinggi, sementara oranye menyala di bagian samping dan engine cover menambah nuansa dinamis yang kuat.</p>

<h3>Kontras Warna yang Kuat</h3>
<p>Pemilihan warna oranye pada area samping bodi bukan hanya estetika semata. Saat mobil melaju kencang, blok warna terang tersebut membantu mobil terlihat jelas dari berbagai sudut kamera, baik di siang maupun malam hari. Ini penting untuk aspek branding sekaligus pengalaman penonton.</p>

<h3>Silhouette yang Bersih dan Modern</h3>
<p>Dengan latar belakang hitam pekat, garis bodi mobil terlihat sangat jelas. Hidung yang rendah, sidepod yang rapat, dan area belakang yang menyempit menonjolkan fokus tim pada efisiensi aerodinamika dan stabilitas kecepatan tinggi.</p>

<h3>Siap Menjadi Ikon Baru</h3>
<p>Livery perak-oranye ini berpotensi menjadi salah satu ikon visual baru di grid F1. Kombinasi warnanya unik, mudah dikaitkan dengan identitas tim, dan memiliki karakter kuat yang membedakannya dari kompetitor lain.</p>',
                'status' => 'published',
                'published_at' => now()->subDays(1),
                'comment_count' => 0,
            ],
            [
                'title' => 'Warna-Warni Agresif: Livery Biru-Kuning Merah yang Ikonik di Grid F1',
                'slug' => 'warna-warni-agresif-livery-biru-kuning-merah-yang-ikonik-di-grid-f1',
                'image' => 'blogs/f1-blue-yellow-red-2026.jpg', // User perlu upload gambar dengan nama ini
                'author' => 'Garasi62 Editorial',
                'excerpt' => 'Dominasi biru gelap dengan aksen kuning dan merah menciptakan livery F1 yang sangat ikonik, penuh energi, dan langsung dikenali di layar TV.',
                'category' => 'F1 2026',
                'tags' => ['formula 1', 'red bull', 'livery', 'mobil balap', 'desain'],
                'content' => '<p>Livery biru-kuning merah ini mungkin salah satu tampilan paling ikonik di dunia F1 modern. Dominasi biru gelap di seluruh bodi mobil dipadukan dengan logo besar di lantai dan area sekitar mobil, menjadikan keseluruhan paket terlihat sangat agresif dan penuh energi.</p>

<h3>Logo Besar di Lantai Mobil</h3>
<p>Salah satu elemen paling mencolok adalah penggunaan logo berukuran besar di lantai mobil. Saat difoto dari sudut rendah atau saat mobil berada di pitlane, elemen ini langsung menarik perhatian dan memperkuat identitas visual tim.</p>

<h3>Detail Kuning yang Kontras</h3>
<p>Aksen kuning di bagian hidung, airbox, dan beberapa area kecil di bodi menambah kedalaman visual tanpa terasa berlebihan. Saat mobil melaju, kombinasi biru tua, merah, dan kuning menciptakan efek <em>motion blur</em> yang sangat menarik di layar.</p>

<h3>Perpaduan Desain dan Performa</h3>
<p>Di balik tampilan mencolok ini, bentuk bodinya tetap menunjukkan fokus tinggi pada performa: intake yang rapat, profil sidepod yang turun tajam, dan area belakang yang bersih. Livery ini bukan hanya soal estetika, tetapi juga cara tim menonjol di lintasan sekaligus di hati para penggemar.</p>',
                'status' => 'published',
                'published_at' => now()->subDays(2),
                'comment_count' => 0,
            ],
        ];

        foreach ($blogs as $blogData) {
            // Check if blog with same slug already exists
            $existingBlog = Blog::where('slug', $blogData['slug'])->first();
            
            if (!$existingBlog) {
                $blogData['user_id'] = $user->id;
                Blog::create($blogData);
                $this->command->info("Blog '{$blogData['title']}' berhasil ditambahkan!");
            } else {
                $this->command->warn("Blog '{$blogData['title']}' sudah ada, dilewati.");
            }
        }

        $this->command->info('Seeder blog selesai!');
        $this->command->warn('Catatan: Pastikan untuk mengupload gambar dengan nama:');
        $this->command->warn('  - blogs/f1-black-green-2026.jpg');
        $this->command->warn('  - blogs/f1-silver-orange-2026.jpg');
        $this->command->warn('  - blogs/f1-blue-yellow-red-2026.jpg');
        $this->command->warn('ke folder storage/app/public/blogs/');
    }
}

