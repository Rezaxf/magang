<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Tambah Aset
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm rounded-xl p-6">

                @if ($errors->any())
                    <div class="mb-4 p-4 bg-red-100 text-red-700 rounded-lg">
                        <ul class="list-disc pl-5">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('aset.store') }}" method="POST">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                        <div>
                            <label class="block text-sm font-medium">Nama Barang</label>
                            <input type="text" name="nama_barang" 
                                   class="w-full mt-1 border rounded-lg px-3 py-2"
                                   required>
                        </div>

                        <div>
                            <label class="block text-sm font-medium">Kode Barang</label>
                            <input type="text" name="kode_barang" 
                                   class="w-full mt-1 border rounded-lg px-3 py-2"
                                   required>
                        </div>

                        <div>
                            <label class="block text-sm font-medium">NUP</label>
                            <input type="number" name="nup" 
                                   class="w-full mt-1 border rounded-lg px-3 py-2">
                        </div>

                        <div>
                            <label class="block text-sm font-medium">Merk / Tipe</label>
                            <input type="text" name="merk_tipe" 
                                   class="w-full mt-1 border rounded-lg px-3 py-2">
                        </div>

                        <div>
                            <label class="block text-sm font-medium">Jumlah</label>
                            <input type="number" name="jumlah" 
                                   class="w-full mt-1 border rounded-lg px-3 py-2">
                        </div>

                        <div>
                            <label class="block text-sm font-medium">Harga</label>
                            <input type="number" name="harga" 
                                   class="w-full mt-1 border rounded-lg px-3 py-2"
                                   required>
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium">Spesifikasi</label>
                            <input type="text" name="spesifikasi" 
                                   class="w-full mt-1 border rounded-lg px-3 py-2">
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium">Cara Perolehan</label>
                            <input type="text" name="cara_perolehan" 
                                   class="w-full mt-1 border rounded-lg px-3 py-2">
                        </div>

                    </div>

                    {{-- Hidden Input Koordinat --}}
                    <input type="hidden" name="latitude" id="latitude">
                    <input type="hidden" name="longitude" id="longitude">

                    {{-- MAP PICKER --}}
                    <div class="mt-8">
                        <label class="block text-sm font-medium mb-2">
                            Pilih Lokasi Aset di Peta
                        </label>

                        <div id="map" class="w-full h-96 rounded-xl border"></div>

                        <p class="text-xs text-gray-500 mt-2">
                            Klik di peta untuk menentukan lokasi aset.
                        </p>
                    </div>

                    <div class="mt-8 flex justify-end gap-4">
                        <a href="{{ route('aset.index') }}"
                           class="px-4 py-2 bg-gray-400 text-white rounded-lg">
                           Batal
                        </a>

                        <button type="submit"
                                class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                            Simpan
                        </button>
                    </div>

                </form>

            </div>
        </div>
    </div>

    {{-- LEAFLET CSS --}}
    <link rel="stylesheet"
        href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>

    {{-- LEAFLET JS --}}
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {

            const defaultLat = -7.983908;
            const defaultLng = 112.621391;

            const map = L.map('map').setView([defaultLat, defaultLng], 10);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap'
            }).addTo(map);

            let marker;

            map.on('click', function(e) {

                if (marker) {
                    map.removeLayer(marker);
                }

                marker = L.marker(e.latlng, { draggable: true }).addTo(map);

                document.getElementById('latitude').value = e.latlng.lat;
                document.getElementById('longitude').value = e.latlng.lng;

                marker.on('dragend', function(event) {
                    const position = event.target.getLatLng();
                    document.getElementById('latitude').value = position.lat;
                    document.getElementById('longitude').value = position.lng;
                });

            });

        });
    </script>

</x-app-layout>