<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Aset extends Model
{
    use HasFactory;

    protected $table = 'asets';

    protected $fillable = [
        'kode_barang',      // Kolom A di Excel
        'nama_barang',      // Kolom B
        'nup',              // Kolom C (No_Reg)
        'spesifikasi',      // Kolom D
        'merk_tipe',        // Kolom E
        'jumlah',           // Kolom F
        'harga',            // Kolom G
        'cara_perolehan',   // Kolom H
        'latitude',         // Koordinat Map
        'longitude',        // Koordinat Map
    ];

    protected $casts = [
        'harga'     => 'float',
        'jumlah'    => 'integer',
        'latitude'  => 'float',
        'longitude' => 'float',
    ];
}