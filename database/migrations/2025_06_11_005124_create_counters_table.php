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
        Schema::create('counters', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade'); 
            $table->foreignId('regime_id')->nullable()->constrained('regimes')->onDelete('set null');
            $table->string('phone')->unique()->nullable();
            $table->string('name');
            $table->string('last_name');
            $table->string('full_name');
            $table->string('address')->nullable();
            $table->string('rfc')->nullable();
            $table->string('curp')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('cp')->nullable();
            $table->date('birthdate')->nullable();
            $table->string('nss')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('counters');
    }
};
