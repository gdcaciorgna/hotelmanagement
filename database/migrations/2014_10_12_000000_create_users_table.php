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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('dni')->unique();
            $table->string('fullName');
            $table->date('bornDate')->nullable();
            $table->enum('userType', ['Receptionist', 'Cleaner', 'Guest']);
            $table->boolean('status')->default(true);
            $table->timestamp('disabledStartDate')->nullable();
            $table->string('disabledReason')->nullable();
            $table->string('email');
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
