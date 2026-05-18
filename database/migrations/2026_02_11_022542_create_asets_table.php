<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('asets', function (Blueprint $table) {
            $table->id();

            $table->string('kode_barang');
            $table->string('nama_barang');
            $table->string('nup')->nullable();
            $table->text('spesifikasi')->nullable();
            $table->string('merk_tipe')->nullable();
            $table->integer('jumlah')->default(1);
            $table->decimal('harga', 15, 2);
            $table->string('cara_perolehan')->nullable();

            // 🔥 TAMBAHAN PENTING
            $table->string('jenis_barang')->nullable();
            $table->string('kecamatan')->nullable();
            $table->string('desa')->nullable();
            $table->string('kondisi')->nullable();

            // 🔥 OPTIONAL (BIAR LENGKAP)
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->string('kode_sku')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('asets');
    }
};