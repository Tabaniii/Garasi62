<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Tambah field tipe dulu
        Schema::table('car', function (Blueprint $table) {
            $table->enum('tipe', ['rent', 'buy'])->default('buy')->after('image');
        });

        // Konversi data image yang ada menjadi JSON array
        $cars = DB::table('car')->get();
        foreach ($cars as $car) {
            $imageValue = $car->image;
            // Jika image adalah string, ubah menjadi array JSON
            if (!empty($imageValue) && !is_null($imageValue)) {
                // Cek apakah sudah JSON atau masih string
                $decoded = json_decode($imageValue, true);
                if (json_last_error() !== JSON_ERROR_NONE) {
                    // Jika bukan JSON, ubah string menjadi array
                    $imageArray = [$imageValue];
                } else {
                    // Jika sudah JSON, gunakan yang ada
                    $imageArray = $decoded;
                }
                DB::table('car')
                    ->where('id', $car->id)
                    ->update(['image' => json_encode($imageArray)]);
            }
        }

        // Ubah kolom image menjadi JSON
        Schema::table('car', function (Blueprint $table) {
            $table->json('image')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('car', function (Blueprint $table) {
            // Kembalikan image ke text
            $table->text('image')->change();
            // Hapus field tipe
            $table->dropColumn('tipe');
        });
    }
};
