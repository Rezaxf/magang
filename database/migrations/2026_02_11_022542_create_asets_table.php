<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migration untuk membuat tabel asets.
     */
    public function up(): void
    {
        Schema::create('asets', function (Blueprint $table) {
            $table->id();
            $table->string('kode_barang');      // Kolom: Kode_Barang
            $table->string('nama_barang');      // Kolom: Nama_Barang
            $table->string('nup');              // Kolom: No_Reg
            $table->text('spesifikasi')->nullable(); // Kolom: Spesifikasi
            $table->string('merk_tipe')->nullable(); // Kolom: Merk_Tipe
            $table->integer('jumlah')->default(1);   // Kolom: Jumlah
            $table->decimal('harga', 15, 2);    // Kolom: Harga
            $table->string('cara_perolehan');   // Kolom: Tgl_Perolehan (Isinya APBD/Cara Perolehan)
            $table->timestamps();               // Mencatat waktu input & update
        });
    }

    /**
     * Batalkan migration (hapus tabel).
     */
    public function down(): void
    {
        Schema::dropIfExists('asets');
    }
};