<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        // 1. Bersihkan kanvas: Buang dulu layer kolom image yang lama
        Schema::table('car', function (Blueprint $table) {
            $table->dropColumn('image');
        });

        // 2. Bikin layer baru: Tambahkan kolom tipe (enum) dan image baru (json)
        Schema::table('car', function (Blueprint $table) {
            $table->enum('tipe', ['rent', 'buy'])->default('buy');
            $table->json('image')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // 1. Hapus layer baru kalau butuh di-undo (Ctrl+Z)
        Schema::table('car', function (Blueprint $table) {
            $table->dropColumn('tipe');
            $table->dropColumn('image');
        });

        // 2. Balikin kanvas image ke format awal
        Schema::table('car', function (Blueprint $table) {
            $table->text('image')->nullable();
        });
    }
};