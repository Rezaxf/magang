<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Aset extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_barang',
        'jenis_barang',
        'kode_barang',
        'nup',
        'spesifikasi',
        'merk_tipe',
        'jumlah',
        'harga',
        'cara_perolehan',
        'kecamatan',
        'desa',
        'kondisi',
        'latitude',
        'longitude',
        'kode_sku',
        'kategori',
        ''
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATIONSHIP
    |--------------------------------------------------------------------------
    */

    // Satu aset punya banyak foto
    public function fotos()
    {
        return $this->hasMany(AsetFoto::class);
    }
    public function riwayat()
{
    return $this->hasMany(RiwayatAset::class);
}
}