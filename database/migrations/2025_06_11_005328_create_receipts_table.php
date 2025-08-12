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
        Schema::create('receipts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('counter_id')->constrained('counters')->onDelete('cascade');  
            $table->foreignId('client_id')->constrained('clients')->onDelete('cascade');  
            $table->foreignId('category_id')->constrained('categories');  
            $table->string('identificator')->unique();
            $table->datetime('payment_date');
            $table->string('pay_method');
            $table->decimal('mount', 10, 2); 
            $table->text('concept');  
            $table->string('status'); 
            $table->string('sello')->nullable(); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('receipts');
    }
};
