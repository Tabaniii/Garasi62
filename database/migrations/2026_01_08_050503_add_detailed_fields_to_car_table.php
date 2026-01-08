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
            $table->string('stock', 50)->nullable()->after('kapasitasmesin');
            $table->string('vin', 50)->nullable()->after('stock');
            $table->string('msrp', 15)->nullable()->after('vin');
            $table->string('dealer_discounts', 15)->nullable()->after('msrp');
            $table->text('description')->nullable()->after('dealer_discounts');
            $table->json('interior_features')->nullable()->after('description');
            $table->json('safety_features')->nullable()->after('interior_features');
            $table->json('extra_features')->nullable()->after('safety_features');
            $table->text('technical_specs')->nullable()->after('extra_features');
            $table->string('location', 255)->nullable()->after('technical_specs');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('car', function (Blueprint $table) {
            $table->dropColumn([
                'stock',
                'vin',
                'msrp',
                'dealer_discounts',
                'description',
                'interior_features',
                'safety_features',
                'extra_features',
                'technical_specs',
                'location'
            ]);
        });
    }
};
