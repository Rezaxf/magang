<x-admin-layout>

<x-slot name="header">
    <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
        Manajemen Aset Kominfo Wonogiri
    </h2>
</x-slot>

<div class="py-6">

<div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

{{-- ================= IMPORT CSV ================= --}}
<div class="bg-white shadow-sm rounded-xl p-6 mb-6">
    <h3 class="text-lg font-bold text-gray-700 mb-4">Import Data Aset</h3>

    <form action="{{ route('aset.import') }}" method="POST" enctype="multipart/form-data"
        class="flex flex-col md:flex-row items-center gap-4">
        @csrf

        <input type="file" name="file" accept=".csv" required
            class="block w-full text-sm text-gray-500
            file:mr-4 file:py-2 file:px-4
            file:rounded-full file:border-0
            file:text-sm file:font-semibold
            file:bg-blue-50 file:text-blue-700
            hover:file:bg-blue-100">

        <button type="submit"
            class="w-full md:w-auto px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition font-bold text-sm">
            Upload CSV
        </button>
    </form>

    @if(session('success'))
        <p class="mt-3 text-green-600 font-semibold text-sm">
            {{ session('success') }}
        </p>
    @endif
</div>

{{-- ================= STATISTIK ================= --}}
<div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 mb-6">

    <div class="bg-white shadow-sm rounded-xl p-4 border-l-4 border-blue-600">
        <div class="text-sm text-gray-500">Total Aset</div>
        <div class="text-2xl font-bold">{{ number_format($totalAset) }}</div>
    </div>

    <div class="bg-white shadow-sm rounded-xl p-4 border-l-4 border-green-600">
        <div class="text-sm text-gray-500">Aset Baik</div>
        <div class="text-2xl font-bold">{{ $asetBaik }}</div>
    </div>

    <div class="bg-white shadow-sm rounded-xl p-4 border-l-4 border-yellow-500">
        <div class="text-sm text-gray-500">Perbaikan</div>
        <div class="text-2xl font-bold">{{ $asetPerbaikan }}</div>
    </div>

    <div class="bg-white shadow-sm rounded-xl p-4 border-l-4 border-red-600">
        <div class="text-sm text-gray-500">Aset Rusak</div>
        <div class="text-2xl font-bold">{{ $asetRusak }}</div>
    </div>

</div>

{{-- ================= NILAI ASET ================= --}}
<div class="bg-white shadow-sm rounded-xl p-4 mb-6 border-l-4 border-green-600">
    <div class="text-sm text-gray-500">Total Nilai Aset</div>
    <div class="text-lg font-bold">
        Rp {{ number_format($totalHarga,0,',','.') }}
    </div>
</div>

{{-- ================= CHART ================= --}}
<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">

    {{-- BAR --}}
    <div class="bg-white shadow-sm rounded-xl p-4 md:col-span-3">
        <h3 class="text-lg font-bold text-gray-700 mb-4">
            Statistik Aset per Jenis Barang
        </h3>

        <div class="overflow-x-auto">
            <div style="min-width:400px; height:300px;">
                <canvas id="chartJenis"></canvas>
            </div>
        </div>
    </div>

    {{-- PIE --}}
    <div class="bg-white shadow-sm rounded-xl p-4">
        <h3 class="text-lg font-bold text-gray-700 mb-4">
            Grafik Kondisi Aset
        </h3>
        <div style="height:250px;">
            <canvas id="chartKondisi"></canvas>
        </div>
    </div>

    {{-- KECAMATAN --}}
    <div class="bg-white shadow-sm rounded-xl p-4">
        <h3 class="text-lg font-bold text-gray-700 mb-4">
            Aset per Kecamatan
        </h3>
        <div style="height:300px;">
            <canvas id="chartKecamatan"></canvas>
        </div>
    </div>

</div>

{{-- ================= MAP ================= --}}
<div class="bg-white shadow-sm rounded-xl p-4">
    <h3 class="text-lg font-bold text-gray-700 mb-4">
        Statistik Aset per Kecamatan
    </h3>
    <div id="map-kecamatan" class="w-full h-[350px] md:h-[650px]"></div>
</div>

</div>
</div>

{{-- ================= CHART JS ================= --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
const chartJenis = @json($chartJenis);

new Chart(document.getElementById('chartJenis'), {
    type: 'bar',
    data: {
        labels: Object.keys(chartJenis),
        datasets: [{
            data: Object.values(chartJenis),
            backgroundColor: [
                '#3B82F6','#10B981','#F59E0B','#EF4444',
                '#8B5CF6','#14B8A6','#F97316','#6366F1','#22C55E'
            ],
            borderRadius: 8
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { display: false } },
        scales: { y: { beginAtZero: true } }
    }
});

const kondisiChart = @json($kondisiChart);

new Chart(document.getElementById('chartKondisi'), {
    type: 'pie',
    data: {
        labels: Object.keys(kondisiChart),
        datasets: [{
            data: Object.values(kondisiChart)
        }]
    }
});

const kecamatanChart = @json($chartKecamatan);

new Chart(document.getElementById('chartKecamatan'), {
    type: 'bar',
    data: {
        labels: Object.keys(kecamatanChart),
        datasets: [{
            data: Object.values(kecamatanChart)
        }]
    }
});
</script>

{{-- ================= MAP ================= --}}
<script src="https://code.highcharts.com/maps/highmaps.js"></script>

<script>
const dataAset = @json($asetPerKecamatan);

fetch('/geojson/kecamatan.geojson')
.then(res => res.json())
.then(geojson => {

const data = dataAset.map(item => ({
    kecamatan: item.kecamatan,
    value: item.jumlah
}));

Highcharts.mapChart('map-kecamatan', {

    chart: { map: geojson },

    title: { text: 'Jumlah Aset per Kecamatan' },

    subtitle: { text: 'Kabupaten Wonogiri' },

    colorAxis: {
        min: 0,
        minColor: '#E6EFFF',
        maxColor: '#0033CC'
    },

    tooltip: {
        pointFormat: 'Jumlah aset: <b>{point.value}</b>'
    },

    series: [{
        data: data,
        name: 'Jumlah Aset',
        joinBy: ['kecamatan','kecamatan'],
        dataLabels: {
            enabled: true,
            format: '{point.properties.kecamatan}'
        }
    }]

});

});
</script>

</x-admin-layout>