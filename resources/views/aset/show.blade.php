<x-app-layout>

<style>

/* PRINT MODE */
@media print {

    body * {
        visibility: hidden;
    }

    #printArea, #printArea * {
        visibility: visible;
    }

    #printArea {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
    }

    .no-print {
        display: none;
    }

}

</style>

<div class="py-12 bg-gray-100 min-h-screen">
<div class="max-w-5xl mx-auto sm:px-6 lg:px-8">

<div class="bg-white shadow-sm rounded-xl p-8">

<h2 class="text-2xl font-bold text-gray-800 mb-6">
Detail Aset
</h2>

<div class="grid grid-cols-1 md:grid-cols-2 gap-8">

<!-- DETAIL ASET -->
<div>

<div class="space-y-3 text-sm">

<p>
<span class="font-semibold">Nama Barang:</span>
{{ $aset->nama_barang }}
</p>

<p>
<span class="font-semibold">Kode Barang:</span>
{{ $aset->kode_barang }}
</p>

<p>
<span class="font-semibold">Merk/Tipe:</span>
{{ $aset->merk_tipe ?? '-' }}
</p>

<p>
<span class="font-semibold">Harga:</span>
Rp {{ number_format($aset->harga,0,',','.') }}
</p>

<p>
<span class="font-semibold">Jumlah:</span>
{{ $aset->jumlah ?? '-' }}
</p>

@if($aset->spesifikasi)
<p>
<span class="font-semibold">Spesifikasi:</span>
{{ $aset->spesifikasi }}
</p>
@endif

@if($aset->cara_perolehan)
<p>
<span class="font-semibold">Cara Perolehan:</span>
{{ $aset->cara_perolehan }}
</p>
@endif

</div>

</div>


<!-- QR CODE -->
<div class="text-center">

<h3 class="font-semibold mb-3">
QR Code Aset
</h3>

<div id="printArea" class="border rounded-xl p-6 inline-block bg-white">

<h4 class="text-sm font-bold mb-3">
LABEL ASET
</h4>

<img
src="https://api.qrserver.com/v1/create-qr-code/?size=220x220&data={{ url('/aset/'.$aset->id) }}"
class="mx-auto mb-3"
/>

<div class="text-xs space-y-1">

<p class="font-semibold">
{{ $aset->nama_barang }}
</p>

<p>
Kode: {{ $aset->kode_barang }}
</p>

@if($aset->merk_tipe)
<p>
{{ $aset->merk_tipe }}
</p>
@endif

</div>

</div>

<p class="text-xs text-gray-500 mt-3">
Scan QR untuk membuka detail aset
</p>

</div>

</div>


<!-- FOTO ASET -->
@if($aset->fotos->count())

<div class="mt-10">

<h3 class="font-semibold mb-4">
Foto Aset
</h3>

<div class="grid grid-cols-3 md:grid-cols-5 gap-4">

@foreach($aset->fotos as $foto)

<img
src="{{ asset('storage/'.$foto->path) }}"
class="rounded shadow object-cover h-28 w-full"
/>

@endforeach

</div>

</div>

@endif


<!-- BUTTON -->
<div class="mt-10 flex gap-4 no-print">

<a href="{{ route('aset.index') }}"
class="px-5 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition font-semibold text-sm shadow">
← Kembali
</a>

<button onclick="window.print()"
class="px-5 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-semibold text-sm shadow">
🖨 Cetak QR
</button>

</div>


</div>
</div>
</div>

</x-app-layout>