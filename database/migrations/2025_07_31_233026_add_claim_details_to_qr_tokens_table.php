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
        Schema::table('qr_tokens', function (Blueprint $table) {
            // Add columns to store partial claim data
            $table->foreignId('product_selected_id')->nullable()->constrained('products')->after('prize');
            $table->string('name')->nullable()->after('product_selected_id');
            $table->string('mobile')->nullable()->after('name');
            $table->string('city')->nullable()->after('mobile');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('qr_tokens', function (Blueprint $table) {
            $table->dropForeign(['product_selected_id']);
            $table->dropColumn(['product_selected_id', 'name', 'mobile', 'city']);
        });
    }
};
