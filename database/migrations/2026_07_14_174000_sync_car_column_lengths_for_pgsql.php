<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Widen short varchar columns that commonly overflow after MySQL → PostgreSQL moves.
     */
    public function up(): void
    {
        Schema::table('car', function (Blueprint $table) {
            $table->string('kilometer', 20)->change();
            $table->string('transmisi', 20)->change();
            $table->string('harga', 20)->change();
            $table->string('metode', 20)->change();
            $table->string('brand', 50)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('car', function (Blueprint $table) {
            $table->string('kilometer', 6)->change();
            $table->string('transmisi', 20)->change();
            $table->string('harga', 10)->change();
            $table->string('metode', 10)->change();
            $table->string('brand', 20)->change();
        });
    }
};
