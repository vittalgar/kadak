<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('prizes', function (Blueprint $table) {
            // This will be 'inside' or 'outside'
            $table->string('display_type')->default('inside')->after('name');

            // We no longer need a single total_stock, as it will be monthly
            $table->dropColumn('total_stock');
            $table->dropColumn('remaining_stock');

            // Add columns for month-wise stock
            $table->unsignedInteger('stock_oct')->default(0);
            $table->unsignedInteger('stock_nov')->default(0);
            $table->unsignedInteger('stock_dec')->default(0);
        });
    }

    public function down(): void
    {
        Schema::table('prizes', function (Blueprint $table) {
            $table->dropColumn(['display_type', 'stock_oct', 'stock_nov', 'stock_dec']);
            // Add back the old columns if we roll back
            $table->unsignedInteger('total_stock');
            $table->unsignedInteger('remaining_stock');
        });
    }
};
