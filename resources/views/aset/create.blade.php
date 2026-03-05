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

                <form action="{{ route('aset.store') }}"
                      method="POST"
                      enctype="multipart/form-data">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                        <div>
                            <label>Nama Barang</label>
                            <input type="text" name="nama_barang"
                                   class="w-full border rounded-lg px-3 py-2" required>
                        </div>

                        <div>
                            <label>Kode Barang</label>
                            <input type="text" name="kode_barang"
                                   class="w-full border rounded-lg px-3 py-2" required>
                        </div>

                        <div>
                            <label>NUP</label>
                            <input type="number" name="nup"
                                   class="w-full border rounded-lg px-3 py-2">
                        </div>

                        <div>
                            <label>Merk / Tipe</label>
                            <input type="text" name="merk_tipe"
                                   class="w-full border rounded-lg px-3 py-2">
                        </div>

                        <div>
                            <label>Jumlah</label>
                            <input type="number" name="jumlah"
                                   class="w-full border rounded-lg px-3 py-2">
                        </div>

                        <div>
                            <label>Harga</label>
                            <input type="number" name="harga"
                                   class="w-full border rounded-lg px-3 py-2" required>
                        </div>

                        <div class="md:col-span-2">
                            <label>Spesifikasi</label>
                            <input type="text" name="spesifikasi"
                                   class="w-full border rounded-lg px-3 py-2">
                        </div>

                        <div class="md:col-span-2">
                            <label>Cara Perolehan</label>
                            <input type="text" name="cara_perolehan"
                                   class="w-full border rounded-lg px-3 py-2">
                        </div>

                        {{-- MULTIPLE FOTO --}}
                        <div class="md:col-span-2">
                            <label>Foto Aset</label>
                            <input type="file" name="foto[]" multiple
                                   class="w-full border rounded-lg px-3 py-2">
                            <small class="text-gray-500">
                                Bisa upload lebih dari satu foto
                            </small>
                        </div>

                    </div>

                    {{-- KOORDINAT --}}
                    <div class="mt-6">

                        <button type="button"
                                id="btnLokasiSaya"
                                class="mb-3 px-4 py-2 bg-green-600 text-white rounded-lg">
                            📍 Ambil Lokasi Saya
                        </button>

                        <div class="grid grid-cols-2 gap-6">
                            <input type="text" name="latitude" id="latitude"
                                   placeholder="Latitude"
                                   class="border rounded-lg px-3 py-2">

                            <input type="text" name="longitude" id="longitude"
                                   placeholder="Longitude"
                                   class="border rounded-lg px-3 py-2">
                        </div>
                    </div>

                    <div class="mt-6">
                        <div id="map" class="w-full h-96 rounded-xl border"></div>
                    </div>

                    <div class="mt-8 flex justify-end gap-4">
                        <a href="{{ route('aset.index') }}"
                           class="px-4 py-2 bg-gray-400 text-white rounded-lg">
                           Batal
                        </a>

                        <button type="submit"
                                class="px-6 py-2 bg-blue-600 text-white rounded-lg">
                            Simpan
                        </button>
                    </div>

                </form>

            </div>
        </div>
    </div>

    {{-- LEAFLET --}}
    <link rel="stylesheet"
          href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <script>
        const map = L.map('map').setView([-7.983908,112.621391], 10);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png')
            .addTo(map);

        let marker = L.marker([-7.983908,112.621391], { draggable:true }).addTo(map);

        map.on('click', function(e){
            marker.setLatLng(e.latlng);
            latitude.value = e.latlng.lat;
            longitude.value = e.latlng.lng;
        });

        marker.on('dragend', function(){
            const pos = marker.getLatLng();
            latitude.value = pos.lat;
            longitude.value = pos.lng;
        });

        btnLokasiSaya.onclick = function(){
            navigator.geolocation.getCurrentPosition(function(pos){
                const lat = pos.coords.latitude;
                const lng = pos.coords.longitude;
                marker.setLatLng([lat,lng]);
                map.setView([lat,lng],16);
                latitude.value = lat;
                longitude.value = lng;
            });
        };
    </script>

</x-app-layout>