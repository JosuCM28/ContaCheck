<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('receipts', function (Blueprint $table) {
            // Se agregan después de category_id
            $table->foreignId('usocfdi_id')
                ->nullable()
                ->after('category_id')
                ->constrained('usocfdis')   // referencia a tabla usocfdis
                ->nullOnDelete();

            $table->foreignId('regime_id')
                ->nullable()
                ->after('usocfdi_id')
                ->constrained('regimes')    // referencia a tabla regimes
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('receipts', function (Blueprint $table) {
            $table->dropForeign(['usocfdi_id']);
            $table->dropColumn('usocfdi_id');

            $table->dropForeign(['regime_id']);
            $table->dropColumn('regime_id');
        });
    }
};
