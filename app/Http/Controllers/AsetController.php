<?php

namespace App\Http\Controllers;

use App\Models\Aset;
use App\Models\AsetFoto;
use App\Models\RiwayatAset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf; 

class AsetController extends Controller
{

    /**
     * ================= LIST ASET =================
     */
    public function index(Request $request)
    {
        $query = Aset::query();

        if ($request->search) {
            $search = trim($request->search);

            $query->where(function ($q) use ($search) {
                $q->whereRaw("nama_barang ILIKE ?", ["%{$search}%"])
                  ->orWhereRaw("kode_barang ILIKE ?", ["%{$search}%"])
                  ->orWhereRaw("merk_tipe ILIKE ?", ["%{$search}%"])
                  ->orWhereRaw("nup::text ILIKE ?", ["%{$search}%"])
                  ->orWhereRaw("COALESCE(kode_sku,'') ILIKE ?", ["%{$search}%"])
                  ->orWhereRaw("COALESCE(jenis_barang,'') ILIKE ?", ["%{$search}%"]);
            });
        }

        if ($request->merk_tipe) {
            $query->where('merk_tipe', $request->merk_tipe);
        }

        if ($request->kecamatan) {
            $query->where('kecamatan', $request->kecamatan);
        }

        if ($request->jenis_barang) {
            $query->where('jenis_barang', $request->jenis_barang);
        }

        $asets = $query->with('fotos')
            ->orderBy('id', 'desc')
            ->paginate(15)
            ->withQueryString();

        $jenisList = Aset::select('jenis_barang')
            ->whereNotNull('jenis_barang')
            ->distinct()
            ->pluck('jenis_barang')
            ->filter()
            ->unique()
            ->sort()
            ->values();

        if ($jenisList->contains('Lainnya')) {
            $jenisList = $jenisList
                ->reject(fn($j) => $j === 'Lainnya')
                ->values()
                ->push('Lainnya');
        }

        $merks = Aset::select('merk_tipe')
            ->whereNotNull('merk_tipe')
            ->distinct()
            ->orderBy('merk_tipe')
            ->pluck('merk_tipe');

        return view('aset.index', compact('asets', 'merks', 'jenisList'));
    }


    /**
     * ================= SHOW =================
     */
    public function show(Aset $aset)
    {
        $aset->load('fotos');
        return view('aset.show', compact('aset'));
    }


    /**
     * ================= SHOW BY SKU =================
     */
    public function showBySku($sku)
    {
        $aset = Aset::where('kode_sku', $sku)->firstOrFail();
        $aset->load('fotos');

        return view('aset.show', compact('aset'));
    }


    /**
     * ================= CREATE =================
     */
    public function create()
    {
        if (!in_array(Auth::user()->role, ['admin','staff'])) {
            abort(403);
        }

        $jenisList = Aset::select('jenis_barang')
            ->whereNotNull('jenis_barang')
            ->distinct()
            ->orderBy('jenis_barang')
            ->pluck('jenis_barang');

        return view('aset.create', compact('jenisList'));
    }


    /**
     * ================= STORE =================
     */
    public function store(Request $request)
    {
        if (!in_array(Auth::user()->role, ['admin','staff'])) {
            abort(403);
        }

        $validated = $request->validate([
            'nama_barang' => 'required|string|max:255',
            'kode_barang' => 'required|string|max:255',
            'harga' => 'required|numeric|min:0',
            'jumlah' => 'nullable|integer|min:0',
            'foto.*' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $aset = Aset::create($request->all());

        RiwayatAset::create([
            'aset_id' => $aset->id,
            'aksi' => 'Tambah',
            'keterangan' => 'Menambahkan aset: ' . $aset->nama_barang,
        ]);

        if ($request->hasFile('foto')) {
            foreach ($request->file('foto') as $file) {
                $path = $file->store('aset', 'public');

                AsetFoto::create([
                    'aset_id' => $aset->id,
                    'path' => $path,
                ]);
            }
        }

        return redirect()->route('aset.index', ['page' => 1])
            ->with('success', 'Aset berhasil ditambahkan.');
    }


    /**
     * ================= EDIT =================
     */
    public function edit(Aset $aset)
    {
        if (!in_array(Auth::user()->role, ['admin','staff'])) {
            abort(403);
        }

        $aset->load('fotos');

        $jenisList = Aset::select('jenis_barang')
            ->whereNotNull('jenis_barang')
            ->distinct()
            ->pluck('jenis_barang');

        return view('aset.edit', compact('aset', 'jenisList'));
    }


    /**
     * ================= UPDATE =================
     */
    public function update(Request $request, Aset $aset)
    {
        if (!in_array(Auth::user()->role, ['admin','staff'])) {
            abort(403);
        }

        $validated = $request->validate([
            'nama_barang' => 'required|string|max:255',
            'kode_barang' => 'required|string|max:255',
            'harga' => 'required|numeric|min:0',
        ]);

        $lokasiLama = $aset->kecamatan;
        $kondisiLama = $aset->kondisi;

        $aset->update($request->all());

        RiwayatAset::create([
            'aset_id' => $aset->id,
            'aksi' => 'Update',
            'keterangan' => 'Mengupdate aset',
            'lokasi_lama' => $lokasiLama,
            'lokasi_baru' => $aset->kecamatan,
            'kondisi_lama' => $kondisiLama,
            'kondisi_baru' => $aset->kondisi,
        ]);

        return redirect()->route('aset.index')
            ->with('success', 'Aset berhasil diperbarui.');
    }


    /**
     * ================= DELETE =================
     */
    public function destroy(Aset $aset)
    {
        if (Auth::user()->role != 'admin') {
            abort(403);
        }

        RiwayatAset::create([
            'aset_id' => $aset->id,
            'aksi' => 'Hapus',
            'keterangan' => 'Menghapus aset: ' . $aset->nama_barang,
        ]);

        foreach ($aset->fotos as $foto) {
            if (Storage::disk('public')->exists($foto->path)) {
                Storage::disk('public')->delete($foto->path);
            }
        }

        $aset->delete();

        return redirect()->route('aset.index')
            ->with('success', 'Aset berhasil dihapus.');
    }


    /**
     * ================= PRINT PDF =================
     */

public function printPdf()
{
    $asets = Aset::orderBy('id')->limit(300)->get(); // 🔥 BATASI DULU

    $totalAset = $asets->count();
    $totalNilai = $asets->sum('harga');

    $kondisi = [
        'Baik' => $asets->where('kondisi', 'Baik')->count(),
        'Rusak' => $asets->where('kondisi', 'Rusak')->count(),
        'Perbaikan' => $asets->where('kondisi', 'Perbaikan')->count(),
    ];

    $pdf = Pdf::loadView('aset.print', compact(
        'asets',
        'totalAset',
        'totalNilai',
        'kondisi'
    ))->setPaper('A4', 'landscape');

    return $pdf->stream('laporan-aset.pdf');
}
}