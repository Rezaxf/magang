<?php

namespace App\Http\Controllers;

use App\Models\Aset;

class DashboardController extends Controller
{
    public function index()
    {
        $asets = Aset::all();

        // ================= TOTAL =================
        $totalAset = $asets->count();

        // ================= KONDISI =================
        $asetBaik = $asets->where('kondisi', 'Baik')->count();
        $asetRusak = $asets->where('kondisi', 'Rusak')->count();
        $asetPerbaikan = $asets->where('kondisi', 'Perbaikan')->count();

        // ================= TOTAL NILAI =================
        $totalHarga = $asets->sum(function ($item) {
            return $item->harga * ($item->jumlah ?? 1);
        });

        // ================= CHART JENIS =================
        $chartJenis = $asets->groupBy('jenis_barang')
            ->map(function ($item) {
                return $item->count();
            });

        // ================= CHART KONDISI =================
        $kondisiChart = [
            'Baik' => $asetBaik,
            'Rusak' => $asetRusak,
            'Perbaikan' => $asetPerbaikan,
        ];

        // ================= CHART KECAMATAN =================
        $chartKecamatan = $asets->groupBy('kecamatan')
            ->map(function ($item) {
                return $item->count();
            });

        // ================= DATA MAP =================
        $asetPerKecamatan = $asets->groupBy('kecamatan')
            ->map(function ($item, $key) {
                return [
                    'kecamatan' => $key,
                    'jumlah' => $item->count(),
                ];
            })
            ->values();

        return view('dashboard', compact(
            'totalAset',
            'asetBaik',
            'asetRusak',
            'asetPerbaikan',
            'totalHarga',
            'chartJenis',
            'kondisiChart',
            'chartKecamatan',
            'asetPerKecamatan'
        ));
    }
}