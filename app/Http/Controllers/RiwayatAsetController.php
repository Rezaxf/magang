<?php

namespace App\Http\Controllers;

use App\Models\RiwayatAset;

class RiwayatAsetController extends Controller
{
    public function index()
    {
        // 🔥 ambil semua riwayat + relasi aset
        $riwayat = RiwayatAset::with('aset')
            ->latest()
            ->get();

        return view('riwayat.index', compact('riwayat'));
    }
}