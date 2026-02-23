<?php

namespace App\Imports;

use App\Models\Aset;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithCustomCsvSettings;

class AsetImport implements ToModel, WithStartRow, WithCustomCsvSettings
{
    /**
     * Mulai baca dari baris ke-2 supaya judul kolom tidak ikut masuk
     */
    public function startRow(): int
    {
        return 2;
    }

    /**
     * Pengaturan agar Laravel bisa membaca CSV versi Indonesia (Pemisah Titik Koma)
     */
    public function getCsvSettings(): array
    {
        return [
            'delimiter' => ';' 
        ];
    }

    public function model(array $row)
    {
        // PERBAIKAN HARGA:
        // Kita hilangkan karakter yang bukan angka (seperti titik ribuan jika ada)
        // Dan kita ganti koma desimal menjadi titik agar bisa masuk ke Database
        $hargaRaw = str_replace('.', '', $row[6]); // Hilangkan titik ribuan (misal: 6.000 -> 6000)
        $hargaFinal = str_replace(',', '.', $hargaRaw); // Ganti koma jadi titik (misal: 6000,47 -> 6000.47)

        return new Aset([
            'kode_barang'    => $row[0] ?? null,
            'nama_barang'    => $row[1] ?? null,
            'nup'            => $row[2] ?? 0,
            'spesifikasi'    => $row[3] ?? null,
            'merk_tipe'      => $row[4] ?? null,
            'jumlah'         => $row[5] ?? 0,
            'harga'          => (float) $hargaFinal, // Paksa jadi format angka desimal
            'cara_perolehan' => $row[7] ?? null,
        ]);
    }
}