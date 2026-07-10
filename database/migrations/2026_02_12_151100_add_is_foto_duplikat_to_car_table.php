<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(): void
    {
        Schema::table('car', function (Blueprint $table) {
            $table->boolean('is_foto_duplikat')->default(false)->after('image')
                ->comment('Status apakah foto mobil ini terdeteksi duplikat');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('car', function (Blueprint $table) {
            $table->dropColumn('is_foto_duplikat');
        });
    }
};
