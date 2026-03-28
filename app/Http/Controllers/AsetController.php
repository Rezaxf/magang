<?php

namespace App\Http\Controllers;

use App\Models\Aset;
use App\Models\AsetFoto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AsetController extends Controller
{

    /**
     * DASHBOARD (STATISTIK + MAP)
     */
    public function dashboard()
{
    $totalAset = Aset::count();

    $totalHarga = Aset::sum('harga');

    // DATA MARKER MAP (leaflet / marker aset)
    $asetsMap = Aset::select(
        'nama_barang',
        'merk_tipe',
        'harga',
        'latitude',
        'longitude'
    )
    ->whereNotNull('latitude')
    ->whereNotNull('longitude')
    ->get();

    // 🔹 STATISTIK ASET PER KECAMATAN (UNTUK HIGHCHARTS MAP)
    $asetPerKecamatan = Aset::selectRaw('
        kecamatan,
        COUNT(*) as jumlah
    ')
    ->whereNotNull('kecamatan')
    ->groupBy('kecamatan')
    ->get();

    // 🔹 TAMBAHAN STATISTIK
    $asetBaik = Aset::where('kondisi', 'Baik')->count();
    $asetRusak = Aset::where('kondisi', 'Rusak')->count();
    $asetPerbaikan = Aset::where('kondisi', 'Perbaikan')->count();

    $kondisiChart = [
        'Baik' => $asetBaik,
        'Rusak' => $asetRusak,
        'Perbaikan' => $asetPerbaikan
    ];

    $chartKecamatan = Aset::selectRaw('kecamatan, COUNT(*) as jumlah')
        ->whereNotNull('kecamatan')
        ->groupBy('kecamatan')
        ->pluck('jumlah', 'kecamatan');

    //  TAMBAHAN UNTUK SIDEBAR (WAJIB)
    $allAsets = Aset::select(
        'id', 
        'nama_barang',
        'kecamatan',
        'kondisi',
        'harga'
    )->get();

    return view('dashboard', compact(
        'totalAset',
        'totalHarga',
        'asetsMap',
        'asetPerKecamatan',
        'asetBaik',
        'asetRusak',
        'asetPerbaikan',
        'kondisiChart',
        'chartKecamatan',
        'allAsets' // 🔥 WAJIB ADA
    ));
}


    /**
     * Display list of assets
     */
    public function index(Request $request)
    {
        $query = Aset::query();

        // 🔍 SEARCH
        if ($request->search) {

            $search = trim($request->search);

            $query->where(function ($q) use ($search) {
                $q->whereRaw("nama_barang ILIKE ?", ["%{$search}%"])
                  ->orWhereRaw("kode_barang ILIKE ?", ["%{$search}%"])
                  ->orWhereRaw("merk_tipe ILIKE ?", ["%{$search}%"]);
            });

        } else {
            $query->latest();
        }

        // 🔹 FILTER MERK
        if ($request->merk_tipe) {
            $query->where('merk_tipe', $request->merk_tipe);
        }

        // 🆕 FILTER KECAMATAN (INI YANG DITAMBAHKAN)
        if ($request->kecamatan) {
            $query->where('kecamatan', $request->kecamatan);
        }

        // Load foto supaya cepat
        $asets = $query->with('fotos')
                       ->paginate(15)
                       ->withQueryString();

        $merks = Aset::select('merk_tipe')
            ->whereNotNull('merk_tipe')
            ->distinct()
            ->orderBy('merk_tipe')
            ->pluck('merk_tipe');

        return view('aset.index', compact('asets', 'merks'));
    }


    /**
     * Show create form
     */
    public function create()
    {
        return view('aset.create');
    }


    /**
     * Store new asset (MULTIPLE FOTO)
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_barang'    => 'required|string|max:255',
            'kode_barang'    => 'required|string|max:255',
            'nup'            => 'nullable|integer',
            'spesifikasi'    => 'nullable|string|max:255',
            'merk_tipe'      => 'nullable|string|max:255',
            'jumlah'         => 'nullable|integer|min:0',
            'harga'          => 'required|numeric|min:0',
            'cara_perolehan' => 'nullable|string|max:255',

            'kecamatan'      => 'nullable|string|max:255',
            'desa'           => 'nullable|string|max:255',
            'kondisi'        => 'nullable|string|max:50',

            'latitude'       => 'nullable|numeric',
            'longitude'      => 'nullable|numeric',

            'foto.*' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $aset = Aset::create($validated);

        if ($request->hasFile('foto')) {
            foreach ($request->file('foto') as $file) {

                $path = $file->store('aset', 'public');

                AsetFoto::create([
                    'aset_id' => $aset->id,
                    'path'    => $path,
                ]);
            }
        }

        return redirect()
            ->route('aset.index')
            ->with('success', 'Aset berhasil ditambahkan.');
    }


    /**
     * Show detail asset + QR
     */
    public function show(Aset $aset)
    {
        $aset->load('fotos');

        return view('aset.show', compact('aset'));
    }


    /**
     * Show edit form
     */
    public function edit(Aset $aset)
    {
        $aset->load('fotos');

        return view('aset.edit', compact('aset'));
    }


    /**
     * Update asset
     */
    public function update(Request $request, Aset $aset)
    {
        $newKode = trim($request->kode_barang);
        $oldKode = trim($aset->kode_barang);

        $request->validate([
            'nama_barang'    => 'required|string|max:255',
            'nup'            => 'nullable|integer',
            'spesifikasi'    => 'nullable|string|max:255',
            'merk_tipe'      => 'nullable|string|max:255',
            'jumlah'         => 'nullable|integer|min:0',
            'harga'          => 'required|numeric|min:0',
            'cara_perolehan' => 'nullable|string|max:255',

            'kecamatan'      => 'nullable|string|max:255',
            'desa'           => 'nullable|string|max:255',
            'kondisi'        => 'nullable|string|max:50',

            'latitude'       => 'nullable|numeric',
            'longitude'      => 'nullable|numeric',

            'foto.*'         => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($newKode !== $oldKode) {

            $exists = Aset::where('kode_barang', $newKode)->exists();

            if ($exists) {
                return back()
                    ->withErrors(['kode_barang' => 'Kode barang sudah digunakan.'])
                    ->withInput();
            }
        }

        $aset->update([
            'nama_barang'    => $request->nama_barang,
            'kode_barang'    => $newKode,
            'nup'            => $request->nup,
            'spesifikasi'    => $request->spesifikasi,
            'merk_tipe'      => $request->merk_tipe,
            'jumlah'         => $request->jumlah,
            'harga'          => $request->harga,
            'cara_perolehan' => $request->cara_perolehan,

            'kecamatan'      => $request->kecamatan,
            'desa'           => $request->desa,
            'kondisi'        => $request->kondisi,

            'latitude'       => $request->latitude,
            'longitude'      => $request->longitude,
        ]);

        if ($request->hasFile('foto')) {
            foreach ($request->file('foto') as $file) {

                $path = $file->store('aset', 'public');

                AsetFoto::create([
                    'aset_id' => $aset->id,
                    'path'    => $path,
                ]);
            }
        }

        return redirect()
            ->route('aset.index')
            ->with('success', 'Aset berhasil diperbarui.');
    }


    /**
     * Delete asset + foto
     */
    public function destroy(Aset $aset)
    {
        foreach ($aset->fotos as $foto) {
            if (Storage::disk('public')->exists($foto->path)) {
                Storage::disk('public')->delete($foto->path);
            }
        }

        $aset->delete();

        return redirect()
            ->route('aset.index')
            ->with('success', 'Aset berhasil dihapus.');
    }
}