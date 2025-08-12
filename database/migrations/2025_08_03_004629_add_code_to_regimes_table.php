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
        Schema::table('regimes', function (Blueprint $table) {
            $table->string('code')->after('title')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('regimes', function (Blueprint $table) {
            $table->dropColumn('code');
        });
    }

};
