<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('company_data', function (Blueprint $table) {
            $table->id();
            $table->foreignId('regime_id')->nullable()->constrained('regimes')->onDelete('set null');
            $table->string('name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('full_name')->nullable();
            $table->string('curp')->nullable();
            $table->string('cp')->nullable();
            $table->string('rfc')->unique();
            $table->string('nombre_comercial')->nullable();
            $table->string('phone')->nullable();
            $table->string('phone2')->nullable();
            $table->string('email')->nullable();
            $table->string('street')->nullable();
            $table->string('num_ext')->nullable();
            $table->string('col')->nullable();
            $table->string('state')->nullable();
            $table->string('localities')->nullable();
            $table->string('referer')->nullable();
            $table->string('city')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('company_data');
    }
};
