<?php

namespace App\Imports;

use App\Models\Aset;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithCustomCsvSettings;

class AsetImport implements ToModel, WithHeadingRow, WithCustomCsvSettings
{
    public function getCsvSettings(): array
    {
        return [
            'delimiter' => ';'
        ];
    }

    public function model(array $row)
    {
        // ===== AMANKAN HARGA =====
        $hargaRaw = $row['harga'] ?? 0;
        $hargaRaw = preg_replace('/[^0-9.,]/', '', $hargaRaw);
        $hargaRaw = str_replace('.', '', $hargaRaw);
        $hargaFinal = str_replace(',', '.', $hargaRaw);

        // 🔥 DETEKSI SKU OTOMATIS (ANTI ERROR)
        $sku = null;
        foreach ($row as $key => $value) {
            if (str_contains(strtolower($key), 'sku')) {
                $sku = $value;
                break;
            }
        }

        return new Aset([
            'kode_barang'    => $row['kode_barang'] ?? null,
            'nama_barang'    => $row['nama_barang'] ?? null,
            'nup'            => isset($row['no_reg']) ? (int) $row['no_reg'] : 0,
            'spesifikasi'    => $row['spesifikasi'] ?? null,
            'merk_tipe'      => $row['merk_tipe'] ?? null,
            'jumlah'         => isset($row['jumlah']) ? (int) $row['jumlah'] : 0,
            'harga'          => (float) $hargaFinal,
            'cara_perolehan' => $row['cara_perolehan'] ?? null,

            // 🔥 SKU FIX
            'kode_sku'       => $sku,
        ]);
    }
}