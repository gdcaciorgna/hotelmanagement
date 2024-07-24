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
        Schema::create('rates_price_histories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('rate_id');
            $table->decimal('price', 10, 2);
            $table->timestamps();
    
            $table->foreign('rate_id')->references('id')->on('rates')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rates_price_histories');
    }
};
