<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Imports\AsetImport;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Aset;

Route::get('/', function () {
    return view('welcome');
});


Route::get('/dashboard', function () {
    $asets = \App\Models\Aset::paginate(100); 
    $totalHarga = \App\Models\Aset::sum('harga'); 
    return view('dashboard', compact('asets', 'totalHarga'));
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    
    Route::post('/import-aset', function (Request $request) {
        $request->validate([
            'file' => 'required|mimes:csv,txt',
        ]);

        Excel::import(new AsetImport, $request->file('file'));

        return redirect()->back()->with('success', 'Data Aset Kominfo Berhasil Diimpor!');
    })->name('aset.import');
});

require __DIR__.'/auth.php';
