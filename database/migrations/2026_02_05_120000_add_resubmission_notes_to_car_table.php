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
        Schema::table('car', function (Blueprint $table) {
            $table->text('resubmission_notes')->nullable()->after('status');
            $table->timestamp('resubmitted_at')->nullable()->after('resubmission_notes');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('car', function (Blueprint $table) {
            $table->dropColumn(['resubmission_notes', 'resubmitted_at']);
        });
    }
};
