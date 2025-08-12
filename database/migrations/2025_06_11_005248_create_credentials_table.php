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
        Schema::create('credentials', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->nullable()->constrained('clients')->onDelete('set null');
            $table->string('idse')->nullable();
            $table->string('siec')->nullable();
            $table->string('sipare')->nullable();
            $table->string('useridse')->nullable();
            $table->string('usersipare')->nullable();
            $table->string('auxone')->nullable();
            $table->string('auxtwo')->nullable();
            $table->string('auxthree')->nullable();
            $table->date('iniciofiel')->nullable();
            $table->date('finfiel')->nullable();
            $table->date('iniciosello')->nullable();
            $table->date('finsello')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('credentials');
    }
};
