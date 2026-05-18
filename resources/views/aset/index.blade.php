<x-admin-layout>

<x-slot name="header">
    <h2 class="text-xl font-semibold text-gray-700">
        Data Aset
    </h2>
</x-slot>

<div class="py-6">
<div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

<div class="bg-white shadow-sm rounded-xl p-6">

{{-- HEADER --}}
<div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4 mb-6">

    <h3 class="text-xl font-bold text-gray-800">
        Data Aset
    </h3>

    @if(in_array(auth()->user()->role, ['admin','staff']))
    <a href="{{ route('aset.create') }}"
       class="px-5 py-2 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition font-bold text-sm shadow text-center">
        + Tambah Aset
    </a>
    @endif

</div>

{{-- ICON FUNCTION --}}
@php
function getIcon($jenis) {
    return match($jenis) {
        'Laptop' => '💻',
        'PC' => '🖥️',
        'Server' => '🗄️',
        'Router' => '📡',
        'Switch' => '🔀',
        'Access Point' => '📶',
        'Hub' => '🔌',
        'Repeater' => '📡',
        'Printer' => '🖨️',
        'Scanner' => '📠',
        'Storage' => '💾',
        'Kabel' => '🔗',
        'Lainnya' => '📦',
        default => '📁'
    };
}
@endphp

{{-- KATEGORI --}}
<div class="flex flex-wrap gap-2 mb-6">
@foreach($jenisList as $jenis)
<a href="{{ route('aset.index', array_merge(request()->except('page'), ['jenis_barang' => $jenis])) }}"
   class="flex items-center gap-2 px-3 py-2 rounded-lg text-xs md:text-sm font-semibold shadow transition
   {{ request('jenis_barang') == $jenis 
        ? 'bg-blue-600 text-white' 
        : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">

    <span>{{ getIcon($jenis) }}</span>
    <span>{{ $jenis }}</span>

</a>
@endforeach
</div>

{{-- FILTER --}}
<form method="GET" action="{{ route('aset.index') }}" class="mb-6">
<div class="grid grid-cols-1 md:grid-cols-5 gap-3">

<input type="text" name="search" value="{{ request('search') }}"
placeholder="Cari nama, kode, SKU..."
class="border rounded-lg px-4 py-2 text-sm">

<select name="merk_tipe" class="border rounded-lg px-4 py-2 text-sm">
<option value="">Semua Merk</option>
@foreach($merks as $merk)
<option value="{{ $merk }}" {{ request('merk_tipe') == $merk ? 'selected' : '' }}>
{{ $merk }}
</option>
@endforeach
</select>

<select name="kecamatan" class="border rounded-lg px-4 py-2 text-sm">
<option value="">Semua Kecamatan</option>
@foreach(\App\Models\Aset::select('kecamatan')->distinct()->pluck('kecamatan') as $kec)
<option value="{{ $kec }}" {{ request('kecamatan') == $kec ? 'selected' : '' }}>
{{ $kec }}
</option>
@endforeach
</select>

<input type="hidden" name="jenis_barang" value="{{ request('jenis_barang') }}">

<button type="submit"
class="bg-blue-600 text-white rounded-lg px-4 py-2 text-sm">
Filter
</button>

<a href="{{ route('aset.index') }}"
class="bg-gray-500 text-white rounded-lg px-4 py-2 text-sm text-center">
Reset
</a>

</div>
</form>

{{-- SUCCESS --}}
@if(session('success'))
<div class="mb-6 p-4 bg-green-100 text-green-700 rounded-lg">
{{ session('success') }}
</div>
@endif

{{-- TABLE --}}
<div class="overflow-x-auto">
<table class="w-full text-sm border-collapse">

<thead class="bg-gray-50">
<tr class="text-left text-gray-600">
<th class="py-3 px-4">Nama</th>
<th class="py-3 px-4">Kode</th>
<th class="py-3 px-4">SKU</th>
<th class="py-3 px-4">Jenis</th>
<th class="py-3 px-4">Merk</th>
<th class="py-3 px-4">Harga</th>
<th class="py-3 px-4">Kecamatan</th>
<th class="py-3 px-4">Kondisi</th>
<th class="py-3 px-4 text-center">Foto</th>
<th class="py-3 px-4 text-center">Aksi</th>
</tr>
</thead>

<tbody class="divide-y">

@forelse ($asets as $aset)
<tr class="hover:bg-gray-50">

<td class="px-4 py-3">{{ $aset->nama_barang }}</td>
<td class="px-4 py-3">{{ $aset->kode_barang }}</td>
<td class="px-4 py-3 text-blue-600 font-bold">{{ $aset->kode_sku ?? '-' }}</td>

<td class="px-4 py-3">
<span class="px-2 py-1 bg-gray-200 rounded text-xs">
{{ $aset->jenis_barang }}
</span>
</td>

<td class="px-4 py-3">{{ $aset->merk_tipe }}</td>

<td class="px-4 py-3 font-semibold">
Rp {{ number_format($aset->harga,0,',','.') }}
</td>

<td class="px-4 py-3">{{ $aset->kecamatan }}</td>

<td class="px-4 py-3">
<span class="px-2 py-1 text-xs rounded font-bold
{{ $aset->kondisi=='Baik'?'bg-green-100 text-green-700':'' }}
{{ $aset->kondisi=='Rusak'?'bg-red-100 text-red-700':'' }}
{{ $aset->kondisi=='Perbaikan'?'bg-yellow-100 text-yellow-700':'' }}">
{{ $aset->kondisi }}
</span>
</td>

<td class="px-4 py-3 text-center">
@if($aset->fotos->count())
<img src="{{ asset('storage/'.$aset->fotos->first()->path) }}"
class="w-10 h-10 rounded object-cover mx-auto">
@endif
</td>

<td class="px-4 py-3 text-center">
<div class="flex justify-center gap-2">

<a href="{{ route('aset.show',$aset->id) }}"
class="px-2 py-1 bg-blue-600 text-white rounded text-xs">QR</a>

@if(in_array(auth()->user()->role,['admin','staff']))
<a href="{{ route('aset.edit',$aset->id) }}"
class="px-2 py-1 bg-green-600 text-white rounded text-xs">Edit</a>
@endif

@if(auth()->user()->role=='admin')
<form action="{{ route('aset.destroy',$aset->id) }}" method="POST">
@csrf @method('DELETE')
<button class="px-2 py-1 bg-red-600 text-white rounded text-xs">
Hapus
</button>
</form>
@endif

</div>
</td>

</tr>
@empty
<tr>
<td colspan="10" class="text-center py-6 text-gray-500">
Belum ada data
</td>
</tr>
@endforelse

</tbody>
</table>
</div>

<div class="mt-6">
{{ $asets->links() }}
</div>

</div>
</div>
</div>

</x-admin-layout>