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
        Schema::table('messages', function (Blueprint $table) {
            $table->foreignId('reply_to')->nullable()->after('sender_id')->constrained('messages')->onDelete('set null');
            $table->timestamp('edited_at')->nullable()->after('updated_at');
            $table->timestamp('deleted_at')->nullable()->after('edited_at');
            $table->boolean('is_deleted_for_sender')->default(false)->after('deleted_at');
            
            $table->index(['reply_to']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('messages', function (Blueprint $table) {
            $table->dropForeign(['reply_to']);
            $table->dropColumn(['reply_to', 'edited_at', 'deleted_at', 'is_deleted_for_sender']);
        });
    }
};
