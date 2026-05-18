<x-admin-layout>

<div class="py-6">
<div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

{{-- HEADER --}}
<div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4 mb-6">

    <h2 class="text-lg md:text-xl font-bold text-gray-800 flex items-center gap-2">
        📊 <span>Laporan Data Aset</span>
    </h2>

    <a href="{{ route('aset.print') }}" target="_blank"
       class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-semibold shadow">
        🖨 Print PDF
    </a>

</div>

{{-- SUMMARY --}}
<div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-6">

    <div class="bg-white p-4 rounded-xl shadow text-center">
        <p class="text-sm text-gray-500">Total Aset</p>
        <p class="text-lg font-bold text-gray-800">{{ $totalAset }}</p>
    </div>

    <div class="bg-white p-4 rounded-xl shadow text-center">
        <p class="text-sm text-gray-500">Total Nilai</p>
        <p class="text-lg font-bold text-gray-800">
            Rp {{ number_format($totalNilai, 0, ',', '.') }}
        </p>
    </div>

    <div class="bg-white p-4 rounded-xl shadow text-center">
        <p class="text-sm text-gray-500">Baik</p>
        <p class="text-lg font-bold text-green-600">{{ $kondisi['Baik'] }}</p>
    </div>

    <div class="bg-white p-4 rounded-xl shadow text-center">
        <p class="text-sm text-gray-500">Rusak</p>
        <p class="text-lg font-bold text-red-600">{{ $kondisi['Rusak'] }}</p>
    </div>

    <div class="bg-white p-4 rounded-xl shadow text-center">
        <p class="text-sm text-gray-500">Perbaikan</p>
        <p class="text-lg font-bold text-yellow-600">{{ $kondisi['Perbaikan'] }}</p>
    </div>

</div>

{{-- TABLE --}}
<div class="bg-white rounded-xl shadow overflow-x-auto">

<table class="min-w-full text-sm">

    <thead class="bg-gray-100 text-gray-600 uppercase text-xs">
        <tr>
            <th class="p-3 text-left">No</th>
            <th class="p-3 text-left">Nama</th>
            <th class="p-3 text-left">Kode</th>
            <th class="p-3 text-left">Merk</th>
            <th class="p-3 text-center">Jumlah</th>
            <th class="p-3 text-left">Harga</th>
            <th class="p-3 text-left">Kecamatan</th>
            <th class="p-3 text-left">Desa</th>
            <th class="p-3 text-center">Kondisi</th>
        </tr>
    </thead>

    <tbody class="divide-y">

        @foreach($asets as $i => $aset)
        <tr class="hover:bg-gray-50 transition">

            <td class="p-3">{{ $i+1 }}</td>

            <td class="p-3 font-medium text-gray-800">
                {{ $aset->nama_barang }}
            </td>

            <td class="p-3 text-gray-600">
                {{ $aset->kode_barang }}
            </td>

            <td class="p-3">
                {{ $aset->merk_tipe }}
            </td>

            <td class="p-3 text-center font-semibold">
                {{ $aset->jumlah }}
            </td>

            <td class="p-3 font-semibold">
                Rp {{ number_format($aset->harga, 0, ',', '.') }}
            </td>

            <td class="p-3">
                {{ $aset->kecamatan }}
            </td>

            <td class="p-3">
                {{ $aset->desa }}
            </td>

            <td class="p-3 text-center">

                @if($aset->kondisi == 'Baik')
                    <span class="px-2 py-1 text-xs font-bold bg-green-100 text-green-700 rounded">
                        Baik
                    </span>
                @elseif($aset->kondisi == 'Rusak')
                    <span class="px-2 py-1 text-xs font-bold bg-red-100 text-red-700 rounded">
                        Rusak
                    </span>
                @else
                    <span class="px-2 py-1 text-xs font-bold bg-yellow-100 text-yellow-700 rounded">
                        Perbaikan
                    </span>
                @endif

            </td>

        </tr>
        @endforeach

    </tbody>

</table>

</div>

</div>
</div>

</x-admin-layout>