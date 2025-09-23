<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('prizes', function (Blueprint $table) {
            // This column will be 'direct' or 'category'
            $table->string('type')->default('direct')->after('name');

            // This will link a sub-prize to its parent category prize
            $table->foreignId('parent_id')->nullable()->constrained('prizes')->onDelete('cascade')->after('type');
        });
    }

    public function down(): void
    {
        Schema::table('prizes', function (Blueprint $table) {
            $table->dropForeign(['parent_id']);
            $table->dropColumn(['type', 'parent_id']);
        });
    }
};
