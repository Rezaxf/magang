<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RiwayatAset extends Model
{
    protected $table = 'riwayat_aset'; // 🔥 PENTING

    protected $fillable = [
        'aset_id',
        'aksi',
        'keterangan',
        'lokasi_lama',
        'lokasi_baru',
        'kondisi_lama',
        'kondisi_baru'
    ];

    public function aset()
    {
        return $this->belongsTo(Aset::class);
    }
}