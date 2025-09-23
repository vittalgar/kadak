<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Update users table
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('agent_id')->nullable()->after('role_id')->constrained('agents')->onDelete('cascade');
        });

        // Update claims table
        Schema::table('claims', function (Blueprint $table) {
            $table->foreignId('agent_id')->nullable()->after('campaign_id')->constrained('agents');
        });
    }

    public function down(): void
    {
        // Rollback users table
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['agent_id']);
            $table->dropColumn('agent_id');
        });

        // Rollback claims table
        Schema::table('claims', function (Blueprint $table) {
            $table->dropForeign(['agent_id']);
            $table->dropColumn('agent_id');
        });
    }
};
