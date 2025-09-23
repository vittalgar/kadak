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
        Schema::create('claims', function (Blueprint $table) {
            $table->id();
            $table->string('claim_id')->unique();
            $table->string('token_used')->index();
            $table->foreignId('product_selected_id')->constrained('products');
            $table->string('name');
            $table->string('mobile');
            $table->string('city');
            $table->string('state');
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->string('prize_won');
            $table->string('status')->default('Processing'); // Processing, Dispatched, Collected
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('claims');
    }
};
