<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('riwayat_aset', function (Blueprint $table) {
            $table->id();

            $table->foreignId('aset_id')
                  ->constrained()
                  ->cascadeOnDelete();

            $table->string('aksi'); // Tambah, Update, Hapus
            $table->text('keterangan')->nullable();

            // 🔥 Tracking perubahan
            $table->string('lokasi_lama')->nullable();
            $table->string('lokasi_baru')->nullable();

            $table->string('kondisi_lama')->nullable();
            $table->string('kondisi_baru')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('riwayat_aset');
    }
};