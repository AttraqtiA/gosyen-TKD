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
        if (Schema::hasTable('posisi') && !Schema::hasColumn('posisi', 'perusahaan_id')) {
            Schema::table('posisi', function (Blueprint $table) {
                $table->foreignId('perusahaan_id')
                    ->nullable()
                    ->after('nama')
                    ->constrained('perusahaan')
                    ->nullOnDelete();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('posisi') && Schema::hasColumn('posisi', 'perusahaan_id')) {
            Schema::table('posisi', function (Blueprint $table) {
                $table->dropForeign(['perusahaan_id']);
                $table->dropColumn('perusahaan_id');
            });
        }
    }
};
