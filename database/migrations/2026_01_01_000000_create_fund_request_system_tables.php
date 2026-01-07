<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', [
                'applicant',
                'approver_level_1',
                'approver_level_2',
                'admin'
            ])->default('applicant');
        });
       
        Schema::create('fund_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            $table->string('program_name');
            $table->string('program_type');
            $table->text('description');

            $table->decimal('amount_requested', 15, 2);
            $table->decimal('amount_approved', 15, 2)->nullable();

            $table->string('bank_name');
            $table->string('account_number');
            $table->string('account_holder');

            $table->enum('status', [
                'draft',
                'submitted',
                'revision',
                'approved_level_1',
                'approved_level_2',
                'rejected',
                'signed',
                'archived'
            ])->default('draft');

            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('archived_at')->nullable();

            $table->timestamps();
        });

        Schema::create('fund_request_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('fund_request_id')->constrained()->cascadeOnDelete();

            $table->string('document_type'); // proposal, rab, surat
            $table->string('file_path');
            $table->string('original_name');
            $table->integer('file_size');

            $table->timestamps();
        });

        Schema::create('approvals', function (Blueprint $table) {
            $table->id();

            $table->foreignId('fund_request_id')->constrained()->cascadeOnDelete();
            $table->foreignId('approver_id')->constrained('users')->cascadeOnDelete();

            $table->enum('level', [1, 2]);
            $table->enum('decision', ['approve', 'reject', 'revise']);
            $table->text('notes')->nullable();

            $table->timestamp('decided_at')->nullable();
            $table->timestamps();
        });

        Schema::create('digital_signatures', function (Blueprint $table) {
            $table->id();

            $table->foreignId('fund_request_id')->constrained()->cascadeOnDelete();
            $table->foreignId('signed_by')->constrained('users')->cascadeOnDelete();

            $table->string('signature_path');
            $table->timestamp('signed_at');

            $table->timestamps();
        });

        Schema::create('notification_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->text('message');
            $table->boolean('is_read')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notification_logs');
        Schema::dropIfExists('digital_signatures');
        Schema::dropIfExists('approvals');
        Schema::dropIfExists('fund_request_documents');
        Schema::dropIfExists('fund_requests');

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('role');
        });
    }
};
