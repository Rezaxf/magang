<?php

namespace App\Imports;

use App\Models\Aset;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithCustomCsvSettings;

class AsetImport implements ToModel, WithStartRow, WithCustomCsvSettings
{
    /**
     * Mulai baca dari baris ke-2 supaya header tidak ikut masuk
     */
    public function startRow(): int
    {
        return 2;
    }

    /**
     * Setting CSV delimiter (karena format Indonesia pakai ;)
     */
    public function getCsvSettings(): array
    {
        return [
            'delimiter' => ';'
        ];
    }

    public function model(array $row)
    {
        // ===== AMANKAN HARGA =====
        $hargaRaw = isset($row[6]) ? $row[6] : 0;

        // Hilangkan karakter selain angka, titik, koma
        $hargaRaw = preg_replace('/[^0-9.,]/', '', $hargaRaw);

        // Hilangkan titik ribuan
        $hargaRaw = str_replace('.', '', $hargaRaw);

        // Ganti koma jadi titik desimal
        $hargaFinal = str_replace(',', '.', $hargaRaw);

        return new Aset([
            'kode_barang'    => isset($row[0]) ? trim($row[0]) : null,
            'nama_barang'    => isset($row[1]) ? trim($row[1]) : null,
            'nup'            => isset($row[2]) ? (int) trim($row[2]) : 0,
            'spesifikasi'    => isset($row[3]) ? trim($row[3]) : null,
            'merk_tipe'      => isset($row[4]) ? trim($row[4]) : null,
            'jumlah'         => isset($row[5]) ? (int) trim($row[5]) : 0,
            'harga'          => (float) $hargaFinal,
            'cara_perolehan' => isset($row[7]) ? trim($row[7]) : null,
        ]);
    }
}