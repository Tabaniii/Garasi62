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
        Schema::table('users', function (Blueprint $table) {
            // Cek apakah kolom role sudah ada
            if (!Schema::hasColumn('users', 'role')) {
                // Jika belum ada, tambahkan kolom role
                $table->enum('role', ['admin', 'buyer', 'seller'])->default('buyer')->after('institution');
            } else {
                // Jika sudah ada, ubah enum role menjadi admin, buyer, seller
                DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'buyer', 'seller') DEFAULT 'buyer'");
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'role')) {
                // Kembalikan ke enum sebelumnya jika ada
                try {
                    DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'user') DEFAULT 'user'");
                } catch (\Exception $e) {
                    // Jika gagal, hapus kolom
                    $table->dropColumn('role');
                }
            }
        });
    }
};
