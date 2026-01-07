<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('about', function (Blueprint $table) {
            $table->id();
            $table->text('tentang', 500);
            $table->string('visi', 150);
            $table->string('misi', 150);
            $table->text('nilai', 200);
            $table->text('layanan', 500);
            $table->text('harga', 500);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('about');
    }
};
