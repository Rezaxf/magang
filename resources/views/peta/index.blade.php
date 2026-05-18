<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Peta Aset
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white shadow rounded-xl p-6">

                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-bold">Visualisasi Lokasi Aset</h3>

                    <button id="toggleHeatmap"
                        class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                        🔥 Tampilkan Heatmap
                    </button>
                </div>

                <div id="map" class="w-full h-[600px] rounded-xl border"></div>

            </div>

        </div>
    </div>

    {{-- Leaflet --}}
    <link rel="stylesheet"
          href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet.heat/dist/leaflet-heat.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {

            const map = L.map('map').setView([-7.983908, 112.621391], 11);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap'
            }).addTo(map);

            let heatLayer;
            let heatVisible = false;

            /*
            |--------------------------------------------------------------------------
            | MARKER JUMLAH ASET
            |--------------------------------------------------------------------------
            */

            fetch('/map-data')
                .then(res => res.json())
                .then(data => {

                    data.forEach(item => {

                        const lat = parseFloat(item.latitude);
                        const lng = parseFloat(item.longitude);

                        const marker = L.circleMarker([lat, lng], {
                            radius: 8 + item.jumlah_aset,
                            color: '#2563eb',
                            fillColor: '#3b82f6',
                            fillOpacity: 0.7
                        }).addTo(map);

                        marker.bindPopup(`
                            <b>Jumlah Aset:</b> ${item.jumlah_aset} <br>
                            <b>Total Nilai:</b> Rp ${Number(item.total_nilai).toLocaleString()}
                        `);

                    });

                });


            /*
            |--------------------------------------------------------------------------
            | HEATMAP DATA
            |--------------------------------------------------------------------------
            */

            function loadHeatmap() {

                fetch('/heatmap-data')
                    .then(res => res.json())
                    .then(data => {

                        const heatPoints = data.map(item => [
                            parseFloat(item.latitude),
                            parseFloat(item.longitude),
                            0.5
                        ]);

                        heatLayer = L.heatLayer(heatPoints, {
                            radius: 25,
                            blur: 20,
                            maxZoom: 17,
                        });

                    });

            }

            loadHeatmap();

            /*
            |--------------------------------------------------------------------------
            | TOGGLE HEATMAP
            |--------------------------------------------------------------------------
            */

            document.getElementById('toggleHeatmap')
                .addEventListener('click', function () {

                    if (!heatLayer) return;

                    if (heatVisible) {
                        map.removeLayer(heatLayer);
                        this.innerText = "🔥 Tampilkan Heatmap";
                        heatVisible = false;
                    } else {
                        heatLayer.addTo(map);
                        this.innerText = "❌ Sembunyikan Heatmap";
                        heatVisible = true;
                    }

                });

        });
    </script>

</x-app-layout>