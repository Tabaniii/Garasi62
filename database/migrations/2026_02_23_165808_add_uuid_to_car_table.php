<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up()
    {
        // 1. Siapkan kanvas awal
        Schema::table('car', function (Blueprint $table) {
            $table->uuid('uuid')->nullable();
        });

        // 2. Cari data yang murni null saja (TANPA string kosong)
        $cars = DB::table('car')->whereNull('uuid')->get();
        foreach ($cars as $car) {
            DB::table('car')->where('id', $car->id)->update([
                'uuid' => (string) Str::uuid(),
            ]);
        }

        // 3. Kunci layernya agar tidak boleh kosong (unique)
        Schema::table('car', function (Blueprint $table) {
            $table->uuid('uuid')->nullable(false)->change();
            $table->unique('uuid');
        });
    }   

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('car', function (Blueprint $table) {
            $table->dropColumn('uuid');
        });
    }
};