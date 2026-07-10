<?php
// 1. Paksa sistem memunculkan teks eror (biar gak cuma ngasih kanvas putih kosong)
ini_set('display_errors', 1);
error_reporting(E_ALL);

// 2. Samakan titik ordinat awal ke folder public 
chdir(__DIR__ . '/../public');

// 3. Bikin struktur folder "layer" sementara di /tmp biar Vercel bebas corat-coret
$tmpDirs = [
    '/tmp/storage/framework/cache/data',
    '/tmp/storage/framework/sessions',
    '/tmp/storage/framework/views',
    '/tmp/storage/logs',
    '/tmp/bootstrap/cache',
];

foreach ($tmpDirs as $dir) {
    if (!is_dir($dir)) {
        mkdir($dir, 0777, true);
    }
}

// 4. Panggil tampilan utama
require __DIR__ . '/../public/index.php';