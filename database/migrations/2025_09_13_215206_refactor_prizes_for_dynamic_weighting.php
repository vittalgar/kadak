<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('prizes', function (Blueprint $table) {
            // Use a boolean for the 'show' column (1 for true, 0 for false)
            $table->boolean('show')->default(true)->after('name');
            $table->dropColumn(['display_type', 'weight']);
        });
    }

    public function down(): void
    {
        Schema::table('prizes', function (Blueprint $table) {
            $table->dropColumn('show');
            $table->string('display_type')->default('inside');
            $table->integer('weight')->default(10);
        });
    }
};
