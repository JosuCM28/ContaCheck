<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            // Quita el índice UNIQUE en phone
            $table->dropUnique(['phone']);

            // Opcional: deja un índice normal para búsquedas
            $table->index('phone');
        });
    }

    public function down(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            // Revierte el cambio: quita índice normal y restaura UNIQUE
            $table->dropIndex(['phone']);
            $table->unique('phone');
        });
    }
};