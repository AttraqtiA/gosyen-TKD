<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable('perusahaan')) {
            Schema::create('perusahaan', function (Blueprint $table) {
                $table->id();
                $table->string('nama', 150)->unique();
            });
        }

        $defaultPerusahaan = [
            'PT. GOSYEN POLINATOR INDONESIA',
            'PT. MAJU ANUGRAH JAYA UNGGUL',
            'PT. PANEN ANUGERAH NUSINDO',
            'PT DWI TUNGGAL MULIA KIMIA',
        ];

        foreach ($defaultPerusahaan as $nama) {
            DB::table('perusahaan')->updateOrInsert(
                ['nama' => $nama],
                ['nama' => $nama]
            );
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('perusahaan');
    }
};
