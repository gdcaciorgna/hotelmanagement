<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Hash;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->enum('docType', ['DNI', 'PAS']);
            $table->string('numDoc');
            $table->string('firstName');
            $table->string('lastName');
            $table->date('bornDate')->nullable();
            $table->enum('userType', ['Receptionist', 'Cleaner', 'Guest']);
            $table->boolean('status')->default(true);
            $table->timestamp('disabledStartDate')->nullable();
            $table->string('disabledReason')->nullable();
            $table->string('email');
            $table->string('phone');
            $table->string('address');
            $table->time('weekdayStartWorkHours')->nullable();
            $table->time('weekdayEndWorkHours')->nullable();
            $table->timestamp('startEmploymentDate')->nullable();
            $table->string('password')->default(Hash::make('123456'));
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
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
