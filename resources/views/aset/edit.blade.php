<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Edit Aset
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

                <form action="{{ route('aset.update', $aset->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                        <div>
                            <label>Nama Barang</label>
                            <input type="text" name="nama_barang"
                                   value="{{ old('nama_barang', $aset->nama_barang) }}"
                                   class="w-full border rounded px-3 py-2" required>
                        </div>

                        <div>
                            <label>Kode Barang</label>
                            <input type="text" name="kode_barang"
                                   value="{{ old('kode_barang', $aset->kode_barang) }}"
                                   class="w-full border rounded px-3 py-2" required>
                        </div>

                        <div>
                            <label>NUP</label>
                            <input type="number" name="nup"
                                   value="{{ old('nup', $aset->nup) }}"
                                   class="w-full border rounded px-3 py-2">
                        </div>

                        <div>
                            <label>Merk / Tipe</label>
                            <input type="text" name="merk_tipe"
                                   value="{{ old('merk_tipe', $aset->merk_tipe) }}"
                                   class="w-full border rounded px-3 py-2">
                        </div>

                        <div>
                            <label>Jumlah</label>
                            <input type="number" name="jumlah"
                                   value="{{ old('jumlah', $aset->jumlah) }}"
                                   class="w-full border rounded px-3 py-2">
                        </div>

                        <div>
                            <label>Harga</label>
                            <input type="number" name="harga"
                                   value="{{ old('harga', $aset->harga) }}"
                                   class="w-full border rounded px-3 py-2" required>
                        </div>

                        <div class="md:col-span-2">
                            <label>Spesifikasi</label>
                            <input type="text" name="spesifikasi"
                                   value="{{ old('spesifikasi', $aset->spesifikasi) }}"
                                   class="w-full border rounded px-3 py-2">
                        </div>

                        <div class="md:col-span-2">
                            <label>Cara Perolehan</label>
                            <input type="text" name="cara_perolehan"
                                   value="{{ old('cara_perolehan', $aset->cara_perolehan) }}"
                                   class="w-full border rounded px-3 py-2">
                        </div>

                    </div>

                    {{-- Hidden Koordinat --}}
                    <input type="hidden" name="latitude" id="latitude" value="{{ $aset->latitude }}">
                    <input type="hidden" name="longitude" id="longitude" value="{{ $aset->longitude }}">

                    {{-- MAP --}}
                    <div class="mt-8">
                        <label>Pilih Lokasi di Peta</label>
                        <div id="map" style="height:400px;"></div>
                    </div>

                    <div class="mt-6 flex justify-end gap-4">
                        <a href="{{ route('aset.index') }}"
                           class="px-4 py-2 bg-gray-400 text-white rounded">
                            Batal
                        </a>
                        <button type="submit"
                                class="px-6 py-2 bg-blue-600 text-white rounded">
                            Update
                        </button>
                    </div>

                </form>

            </div>
        </div>
    </div>

    {{-- Leaflet CSS --}}
    <link rel="stylesheet"
        href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>

    {{-- Leaflet JS --}}
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {

            let lat = {{ $aset->latitude ?? -7.98 }};
            let lng = {{ $aset->longitude ?? 112.62 }};

            const map = L.map('map').setView([lat, lng], 12);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap'
            }).addTo(map);

            let marker = L.marker([lat, lng], { draggable: true }).addTo(map);

            // Kalau marker digeser
            marker.on('dragend', function(event) {
                const position = event.target.getLatLng();
                document.getElementById('latitude').value = position.lat;
                document.getElementById('longitude').value = position.lng;
            });

            // Kalau klik peta
            map.on('click', function(e) {
                marker.setLatLng(e.latlng);
                document.getElementById('latitude').value = e.latlng.lat;
                document.getElementById('longitude').value = e.latlng.lng;
            });

        });
    </script>

</x-app-layout> 