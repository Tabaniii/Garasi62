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
    public function up(): void
    {
        if (!Schema::hasColumn('car', 'uuid')) {
            Schema::table('car', function (Blueprint $table) {
                $table->uuid('uuid')->nullable()->after('id');
            });
        }

        try {
            Schema::table('car', function (Blueprint $table) {
                $table->unique('uuid');
            });
        } catch (\Exception $e) {
        }

        $cars = DB::table('car')->whereNull('uuid')->orWhere('uuid', '')->get();
        foreach ($cars as $car) {
            DB::table('car')->where('id', $car->id)->update([
                'uuid' => (string) Str::uuid(),
            ]);
        }

        try {
            Schema::table('car', function (Blueprint $table) {
                $table->uuid('uuid')->nullable(false)->change();
            });
        } catch (\Exception $e) {
        }
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
