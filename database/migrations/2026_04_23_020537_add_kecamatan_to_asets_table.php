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
        Schema::table('asets', function (Blueprint $table) {

            // 🔥 TAMBAHKAN YANG HILANG
            if (!Schema::hasColumn('asets', 'kecamatan')) {
                $table->string('kecamatan')->nullable();
            }

            if (!Schema::hasColumn('asets', 'desa')) {
                $table->string('desa')->nullable();
            }

            if (!Schema::hasColumn('asets', 'jenis_barang')) {
                $table->string('jenis_barang')->nullable();
            }

            if (!Schema::hasColumn('asets', 'kondisi')) {
                $table->string('kondisi')->nullable();
            }

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('asets', function (Blueprint $table) {

            // rollback (optional)
            $table->dropColumn([
                'kecamatan',
                'desa',
                'jenis_barang',
                'kondisi'
            ]);

        });
    }
};