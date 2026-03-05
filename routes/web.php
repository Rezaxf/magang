<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AsetController;
use App\Models\Aset;
use App\Imports\AsetImport;
use Maatwebsite\Excel\Facades\Excel;

/*
|--------------------------------------------------------------------------
| Public Route
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect()->route('dashboard');
});


/*
|--------------------------------------------------------------------------
| Protected Routes (Admin Internal Only)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {

    /*
    |------------------------------------------
    | Dashboard
    |------------------------------------------
    */
    Route::get('/dashboard', function (Request $request) {

        $search = $request->input('search');
        $merk   = $request->input('merk');

        $query = Aset::query();

        // SEARCH
        if ($search) {
            $search = trim(strtolower($search));

            $query->where(function ($q) use ($search) {
                $q->whereRaw('LOWER(TRIM(nama_barang)) LIKE ?', ["%{$search}%"])
                  ->orWhereRaw('LOWER(TRIM(kode_barang)) LIKE ?', ["%{$search}%"])
                  ->orWhereRaw('LOWER(TRIM(merk_tipe)) LIKE ?', ["%{$search}%"]);
            });
        }

        // FILTER MERK
        if ($merk) {
            $query->whereRaw('LOWER(TRIM(merk_tipe)) = ?', [strtolower(trim($merk))]);
        }

        $asets = $query->paginate(100)->withQueryString();
        $totalHarga = (clone $query)->sum('harga');

        $listMerk = Aset::select('merk_tipe')
            ->whereNotNull('merk_tipe')
            ->distinct()
            ->orderBy('merk_tipe')
            ->pluck('merk_tipe');

        return view('dashboard', compact('asets', 'totalHarga', 'listMerk'));

    })->name('dashboard');


    /*
    |------------------------------------------
    | CRUD ASET
    |------------------------------------------
    */
    Route::resource('aset', AsetController::class);


    /*
    |------------------------------------------
    | PETA ASET (HALAMAN UTAMA MAP)
    |------------------------------------------
    */
    Route::get('/peta-aset', function () {
        return view('peta.index');
    })->name('peta.aset');


    /*
    |------------------------------------------
    | MAP DATA (JUMLAH ASET PER LOKASI)
    |------------------------------------------
    */
    Route::get('/map-data', function () {

        return Aset::selectRaw('
                latitude,
                longitude,
                COUNT(*) as jumlah_aset,
                SUM(harga) as total_nilai
            ')
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->groupBy('latitude', 'longitude')
            ->get();

    })->name('map.data');


    /*
    |------------------------------------------
    | HEATMAP DATA (KEPADATAN)
    |------------------------------------------
    */
    Route::get('/heatmap-data', function () {

        return Aset::whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->get(['latitude', 'longitude']);

    })->name('heatmap.data');


    /*
    |------------------------------------------
    | Import Aset
    |------------------------------------------
    */
    Route::post('/import-aset', function (Request $request) {

        $request->validate([
            'file' => 'required|file|mimes:csv,txt|max:2048',
        ]);

        Excel::import(new AsetImport, $request->file('file'));

        return redirect()->back()->with('success', 'Data Aset berhasil diimpor!');

    })->name('aset.import');


    /*
    |------------------------------------------
    | Laporan
    |------------------------------------------
    */
    Route::get('/laporan', function () {

        $totalAset = Aset::count();
        $totalNilai = Aset::sum('harga');

        return view('laporan.index', compact('totalAset', 'totalNilai'));

    })->name('laporan');


    /*
    |------------------------------------------
    | Profile
    |------------------------------------------
    */
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

});


require __DIR__.'/auth.php';