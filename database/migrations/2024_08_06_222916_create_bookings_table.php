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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->timestamp('bookingDate')->nullable();
            $table->timestamp('startDate')->nullable();
            $table->timestamp('agreedEndDate')->nullable();
            $table->timestamp('actualEndDate')->nullable();
            $table->decimal('finalPrice', 10, 2)->nullable();
            $table->integer('numberOfPeople');
            $table->boolean('returnDeposit');
            $table->foreignId('rate_id')->constrained()->onDelete('cascade');
            $table->foreignId('room_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
