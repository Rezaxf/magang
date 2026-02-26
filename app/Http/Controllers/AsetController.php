<?php

namespace App\Http\Controllers;

use App\Models\Aset;
use Illuminate\Http\Request;

class AsetController extends Controller
{
    /**
     * Display list of assets
     */
    public function index()
    {
        $asets = Aset::latest()->paginate(15);
        return view('aset.index', compact('asets'));
    }

    /**
     * Show create form
     */
    public function create()
    {
        return view('aset.create');
    }

    /**
     * Store new asset
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_barang'    => 'required|string|max:255',
            'kode_barang'    => 'required|string|max:255|unique:asets,kode_barang',
            'nup'            => 'nullable|integer',
            'spesifikasi'    => 'nullable|string|max:255',
            'merk_tipe'      => 'nullable|string|max:255',
            'jumlah'         => 'nullable|integer|min:0',
            'harga'          => 'required|numeric|min:0',
            'cara_perolehan' => 'nullable|string|max:255',
            'latitude'       => 'nullable|numeric',
            'longitude'      => 'nullable|numeric',
        ]);

        Aset::create($validated);

        return redirect()
            ->route('aset.index')
            ->with('success', 'Aset berhasil ditambahkan.');
    }

    /**
     * Show edit form
     */
    public function edit(Aset $aset)
    {
        return view('aset.edit', compact('aset'));
    }

    /**
     * Update asset
     */
    public function update(Request $request, Aset $aset)
    {
        $validated = $request->validate([
            'nama_barang'    => 'required|string|max:255',
            'kode_barang'    => 'required|string|max:255|unique:asets,kode_barang,' . $aset->id,
            'nup'            => 'nullable|integer',
            'spesifikasi'    => 'nullable|string|max:255',
            'merk_tipe'      => 'nullable|string|max:255',
            'jumlah'         => 'nullable|integer|min:0',
            'harga'          => 'required|numeric|min:0',
            'cara_perolehan' => 'nullable|string|max:255',
            'latitude'       => 'nullable|numeric',
            'longitude'      => 'nullable|numeric',
        ]);

        $aset->update($validated);

        return redirect()
            ->route('aset.index')
            ->with('success', 'Aset berhasil diperbarui.');
    }

    /**
     * Delete asset
     */
    public function destroy(Aset $aset)
    {
        $aset->delete();

        return redirect()
            ->route('aset.index')
            ->with('success', 'Aset berhasil dihapus.');
    }
}