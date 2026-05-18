<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AsetFoto extends Model
{
    protected $fillable = ['aset_id', 'path'];
    protected $table = 'public.aset_fotos';

    public function aset()
    {
        return $this->belongsTo(Aset::class);
    }
}