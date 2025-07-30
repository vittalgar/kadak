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
        Schema::table('claims', function (Blueprint $table) {
            // Each claim will be linked to a specific collection point.
            $table->foreignId('retail_shop_id')->after('product_selected_id')->constrained('retail_shops');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('claims', function (Blueprint $table) {
            $table->dropForeign(['retail_shop_id']);
            $table->dropColumn('retail_shop_id');
        });
    }
};
