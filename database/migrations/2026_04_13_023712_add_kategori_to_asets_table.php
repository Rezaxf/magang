<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * RUN MIGRATION
     */
    public function up(): void
    {
        Schema::table('asets', function (Blueprint $table) {

            $table->string('kategori')
                  ->nullable()
                  ->after('kode_sku');

        });
    }

    /**
     * ROLLBACK MIGRATION
     */
    public function down(): void
    {
        Schema::table('asets', function (Blueprint $table) {

            $table->dropColumn('kategori');

        });
    }
};