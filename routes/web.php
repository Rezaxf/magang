<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AsetController;
use App\Http\Controllers\RiwayatAsetController;
use App\Http\Controllers\DashboardController; // 🔥 TAMBAHAN
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
    | Dashboard (SUDAH DIPINDAH)
    |------------------------------------------
    */
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');


    /*
    |------------------------------------------
    | CRUD ASET (TETAP)
    |------------------------------------------
    */
    Route::resource('aset', AsetController::class);


    /*
    |------------------------------------------
    | SHOW BY SKU (QR SCAN)
    |------------------------------------------
    */
    Route::get('/aset/sku/{sku}', [AsetController::class, 'showBySku'])
        ->name('aset.show.sku');


    /*
    |------------------------------------------
    | RIWAYAT ASET
    |------------------------------------------
    */
    Route::get('/riwayat-aset', [RiwayatAsetController::class, 'index'])
        ->name('riwayat.index');


    /*
    |------------------------------------------
    | PRINT PDF
    |------------------------------------------
    */
    Route::get('/aset-print', [AsetController::class, 'printPdf'])
        ->name('aset.print');


    /*
    |------------------------------------------
    | LAPORAN (TETAP)
    |------------------------------------------
    */
    Route::get('/laporan', function () {

        $asets = Aset::all();

        $totalAset = $asets->count();

        $totalNilai = $asets->sum(function ($item) {
            return $item->harga * ($item->jumlah ?? 1);
        });

        $kondisi = [
            'Baik' => $asets->where('kondisi', 'Baik')->count(),
            'Rusak' => $asets->where('kondisi', 'Rusak')->count(),
            'Perbaikan' => $asets->where('kondisi', 'Perbaikan')->count(),
        ];

        return view('laporan.index', compact(
            'asets',
            'totalAset',
            'totalNilai',
            'kondisi'
        ));

    })->name('laporan');


    /*
    |------------------------------------------
    | API ASET PER KECAMATAN
    |------------------------------------------
    */
    Route::get('/aset-by-kecamatan/{kecamatan}', function ($kecamatan) {

        return Aset::where('kecamatan', $kecamatan)
            ->select('id', 'nama_barang', 'kondisi', 'harga')
            ->get();

    })->name('aset.by.kecamatan');


    /*
    |------------------------------------------
    | PETA ASET
    |------------------------------------------
    */
    Route::get('/peta-aset', function () {
        return view('peta.index');
    })->name('peta.aset');


    /*
    |------------------------------------------
    | MAP DATA
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
    | HEATMAP DATA
    |------------------------------------------
    */
    Route::get('/heatmap-data', function () {

        return Aset::whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->get(['latitude', 'longitude']);

    })->name('heatmap.data');


    /*
    |------------------------------------------
    | IMPORT ASET
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
    | PROFILE
    |------------------------------------------
    */
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

});

require __DIR__.'/auth.php';