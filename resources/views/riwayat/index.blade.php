<x-admin-layout>

<div class="py-6">
<div class="max-w-5xl mx-auto sm:px-6 lg:px-8">

<div class="bg-white shadow rounded-xl p-4 md:p-6">

<h2 class="text-lg md:text-xl font-bold text-gray-800 mb-6 flex items-center gap-2">
📜 <span>Timeline Riwayat Aset</span>
</h2>

<div class="relative border-l-2 border-gray-200 pl-5 md:pl-6">

@forelse($riwayat as $r)

<div class="mb-6 md:mb-8 relative group">

    {{-- BULATAN --}}
    <div class="absolute -left-2.5 md:-left-3 top-2 w-4 h-4 md:w-5 md:h-5 rounded-full shadow
        @if($r->aksi == 'Tambah') bg-green-500
        @elseif($r->aksi == 'Update') bg-yellow-500
        @elseif($r->aksi == 'Hapus') bg-red-500
        @else bg-blue-500
        @endif
    "></div>

    {{-- CARD --}}
    <div class="bg-gray-50 hover:bg-white p-4 rounded-lg border border-gray-100 shadow-sm transition">

        {{-- HEADER --}}
        <div class="flex justify-between items-center mb-2">

            {{-- AKSI --}}
            <span class="text-xs font-bold px-2 py-1 rounded
                @if($r->aksi == 'Tambah') bg-green-100 text-green-700
                @elseif($r->aksi == 'Update') bg-yellow-100 text-yellow-700
                @elseif($r->aksi == 'Hapus') bg-red-100 text-red-700
                @else bg-blue-100 text-blue-700
                @endif
            ">
                {{ $r->aksi }}
            </span>

            {{-- WAKTU --}}
            <span class="text-xs text-gray-400">
                {{ $r->created_at->format('d M Y • H:i') }}
            </span>

        </div>

        {{-- NAMA ASET --}}
        <div class="text-gray-800 font-semibold text-sm md:text-base">
            {{ $r->aset->nama_barang ?? 'Aset sudah dihapus' }}
        </div>

        {{-- KETERANGAN --}}
        <div class="text-sm text-gray-600 mt-1 leading-relaxed">
            {{ $r->keterangan }}
        </div>

        {{-- PERUBAHAN --}}
        <div class="mt-3 space-y-1 text-xs text-gray-500">

            @if($r->lokasi_lama || $r->lokasi_baru)
            <div>
                📍 Lokasi:
                <span class="line-through text-gray-400">
                    {{ $r->lokasi_lama ?? '-' }}
                </span>
                →
                <span class="font-semibold text-gray-700">
                    {{ $r->lokasi_baru ?? '-' }}
                </span>
            </div>
            @endif

            @if($r->kondisi_lama || $r->kondisi_baru)
            <div>
                ⚙️ Kondisi:
                <span class="line-through text-gray-400">
                    {{ $r->kondisi_lama ?? '-' }}
                </span>
                →
                <span class="font-semibold text-gray-700">
                    {{ $r->kondisi_baru ?? '-' }}
                </span>
            </div>
            @endif

        </div>

    </div>

</div>

@empty

<div class="text-center text-gray-400 py-10">
    Belum ada riwayat aset
</div>

@endforelse

</div>

</div>
</div>
</div>

</x-admin-layout>