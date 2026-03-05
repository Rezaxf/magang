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

            // Tambah kolom foto (nullable supaya tidak wajib)
            $table->string('foto')->nullable()->after('longitude');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('asets', function (Blueprint $table) {

            // Hapus kolom foto jika rollback
            $table->dropColumn('foto');

        });
    }
};