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
        Schema::table('car', function (Blueprint $table) {
            // Index untuk sorting berdasarkan created_at (sering digunakan)
            $table->index('created_at', 'car_created_at_index');
            
            // Index untuk filtering berdasarkan status
            $table->index('status', 'car_status_index');
            
            // Index untuk filtering berdasarkan tipe (rent/buy)
            $table->index('tipe', 'car_tipe_index');
            
            // Composite index untuk seller_id dan created_at (untuk query seller dengan sorting)
            $table->index(['seller_id', 'created_at'], 'car_seller_id_created_at_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('car', function (Blueprint $table) {
            try {
                $table->dropIndex('car_created_at_index');
            } catch (\Exception $e) {
                // Index mungkin tidak ada
            }
            
            try {
                $table->dropIndex('car_status_index');
            } catch (\Exception $e) {
                // Index mungkin tidak ada
            }
            
            try {
                $table->dropIndex('car_tipe_index');
            } catch (\Exception $e) {
                // Index mungkin tidak ada
            }
            
            try {
                $table->dropIndex('car_seller_id_created_at_index');
            } catch (\Exception $e) {
                // Index mungkin tidak ada
            }
        });
    }
};
