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
        Schema::create('car', function (Blueprint $table) {
            $table->id();
            $table->text('image', 500);
            $table->string('tahun', 4);
            $table->string('brand', 20);
            $table->string('kilometer', 6);
            $table->string('transmisi', 6);
            $table->string('harga', 10);
            $table->string('metode', 5);
            $table->string('kapasitasmesin', 50);
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('car');
    }
};
