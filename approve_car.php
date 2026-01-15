<?php

require_once 'vendor/autoload.php';
require_once 'bootstrap/app.php';

try {
    $car = \App\Models\car::first();
    if ($car) {
        $car->update(['status' => 'approved']);
        echo "Mobil berhasil diapprove\n";
        echo "ID: {$car->id}\n";
        echo "Brand: {$car->brand}\n";
        echo "Status: {$car->status}\n";
    } else {
        echo "Tidak ada mobil ditemukan\n";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
