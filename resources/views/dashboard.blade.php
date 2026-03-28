<x-app-layout>
<x-slot name="header">
<h2 class="font-semibold text-2xl text-gray-800 leading-tight">
Dashboard Manajemen Aset Kominfo
</h2>
</x-slot>

<div class="py-12 bg-gray-100 min-h-screen">
<div class="max-w-7xl mx-auto sm:px-6 lg:px-8">


{{-- ================= IMPORT CSV ================= --}}
<div class="bg-white shadow-sm rounded-xl p-6 mb-8">

<h3 class="text-lg font-bold text-gray-700 mb-4">
Import Data Aset
</h3>

<form action="{{ route('aset.import') }}"
method="POST"
enctype="multipart/form-data"
class="flex items-center gap-4">

@csrf

<input type="file"
name="file"
accept=".csv"
required
class="block w-full text-sm text-gray-500
file:mr-4 file:py-2 file:px-4
file:rounded-full file:border-0
file:text-sm file:font-semibold
file:bg-blue-50 file:text-blue-700
hover:file:bg-blue-100">

<button type="submit"
class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition font-bold text-sm">
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
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">

<div class="bg-white shadow-sm rounded-xl p-6 border-l-4 border-blue-600">
<div class="text-sm text-gray-500 uppercase font-medium">
Total Aset
</div>

<div class="text-3xl font-bold text-gray-900 mt-1">
{{ number_format($totalAset) }}
</div>
</div>


<div class="bg-white shadow-sm rounded-xl p-6 border-l-4 border-green-600">
<div class="text-sm text-gray-500 uppercase font-medium">
Aset Baik
</div>

<div class="text-3xl font-bold text-gray-900 mt-1">
{{ $asetBaik }}
</div>
</div>


<div class="bg-white shadow-sm rounded-xl p-6 border-l-4 border-yellow-500">
<div class="text-sm text-gray-500 uppercase font-medium">
Perbaikan
</div>

<div class="text-3xl font-bold text-gray-900 mt-1">
{{ $asetPerbaikan }}
</div>
</div>


<div class="bg-white shadow-sm rounded-xl p-6 border-l-4 border-red-600">
<div class="text-sm text-gray-500 uppercase font-medium">
Aset Rusak
</div>

<div class="text-3xl font-bold text-gray-900 mt-1">
{{ $asetRusak }}
</div>
</div>

</div>


{{-- ================= NILAI ASET ================= --}}
<div class="grid grid-cols-1 md:grid-cols-1 gap-6 mb-8">

<div class="bg-white shadow-sm rounded-xl p-6 border-l-4 border-green-600">

<div class="text-sm text-gray-500 uppercase font-medium">
Total Nilai Aset
</div>

<div class="text-xl font-bold text-gray-900 mt-1">
Rp {{ number_format($totalHarga,0,',','.') }}
</div>

</div>

</div>


{{-- ================= CHART ================= --}}
<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">

<div class="bg-white shadow-sm rounded-xl p-6">

<h3 class="text-lg font-bold text-gray-700 mb-4">
Grafik Kondisi Aset
</h3>

<canvas id="chartKondisi"></canvas>

</div>


<div class="bg-white shadow-sm rounded-xl p-6">

<h3 class="text-lg font-bold text-gray-700 mb-4">
Aset per Kecamatan
</h3>

<canvas id="chartKecamatan"></canvas>

</div>

</div>


{{-- ================= MAP STATISTIK KECAMATAN ================= --}}
<div class="bg-white shadow-sm rounded-xl p-6">

<h3 class="text-lg font-bold text-gray-700 mb-4">
Statistik Aset per Kecamatan
</h3>

<div id="map-kecamatan" style="height:650px;"></div>

</div>


</div>
</div>


{{-- ================= CHART JS ================= --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>

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
label: 'Jumlah Aset',
data: Object.values(kecamatanChart)
}]
}
});

</script>


{{-- ================= HIGHCHARTS MAP ================= --}}
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

chart: {
map: geojson
},

title: {
text: 'Jumlah Aset per Kecamatan'
},

subtitle: {
text: 'Kabupaten Wonogiri'
},

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
},

// ================== FITUR KLIK ==================
point: {
events: {
click: function () {

const kecamatan = this.kecamatan || this.name;

// redirect ke halaman aset
window.location.href = "/aset?kecamatan=" + encodeURIComponent(kecamatan);

}
}
}
// ===============================================

}]

});

});

</script>

</x-app-layout>