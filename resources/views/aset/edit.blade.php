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

                <form action="{{ route('aset.update', $aset->id) }}"
                      method="POST"
                      enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                        <div>
                            <label>Nama Barang</label>
                            <input type="text"
                                   name="nama_barang"
                                   value="{{ old('nama_barang', $aset->nama_barang) }}"
                                   class="w-full border rounded-lg px-3 py-2"
                                   required>
                        </div>

                        <div>
                            <label>Kode Barang</label>
                            <input type="text"
                                   name="kode_barang"
                                   value="{{ old('kode_barang', $aset->kode_barang) }}"
                                   class="w-full border rounded-lg px-3 py-2"
                                   required>
                        </div>

                        <div>
                            <label>NUP</label>
                            <input type="number"
                                   name="nup"
                                   value="{{ old('nup', $aset->nup) }}"
                                   class="w-full border rounded-lg px-3 py-2">
                        </div>

                        <div>
                            <label>Merk / Tipe</label>
                            <input type="text"
                                   name="merk_tipe"
                                   value="{{ old('merk_tipe', $aset->merk_tipe) }}"
                                   class="w-full border rounded-lg px-3 py-2">
                        </div>

                        <div>
                            <label>Jumlah</label>
                            <input type="number"
                                   name="jumlah"
                                   value="{{ old('jumlah', $aset->jumlah) }}"
                                   class="w-full border rounded-lg px-3 py-2">
                        </div>

                        <div>
                            <label>Harga</label>
                            <input type="number"
                                   name="harga"
                                   value="{{ old('harga', $aset->harga) }}"
                                   class="w-full border rounded-lg px-3 py-2"
                                   required>
                        </div>

                        <div class="md:col-span-2">
                            <label>Spesifikasi</label>
                            <input type="text"
                                   name="spesifikasi"
                                   value="{{ old('spesifikasi', $aset->spesifikasi) }}"
                                   class="w-full border rounded-lg px-3 py-2">
                        </div>

                        <div class="md:col-span-2">
                            <label>Cara Perolehan</label>
                            <input type="text"
                                   name="cara_perolehan"
                                   value="{{ old('cara_perolehan', $aset->cara_perolehan) }}"
                                   class="w-full border rounded-lg px-3 py-2">
                        </div>

                        {{-- FOTO LAMA --}}
                        <div class="md:col-span-2">
                            <label class="block mb-2">Foto Aset</label>

                            @if($aset->fotos->count())
                                <div class="flex gap-3 flex-wrap mb-4">
                                    @foreach($aset->fotos as $foto)
                                        <img src="{{ asset('storage/'.$foto->path) }}"
                                             class="h-24 rounded shadow">
                                    @endforeach
                                </div>
                            @else
                                <p class="text-gray-500 text-sm mb-3">
                                    Belum ada foto
                                </p>
                            @endif

                            <input type="file"
                                   name="foto[]"
                                   multiple
                                   class="w-full border rounded-lg px-3 py-2">

                            <small class="text-gray-500">
                                Upload foto baru untuk menambahkan ke galeri
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
                            <input type="text"
                                   name="latitude"
                                   id="latitude"
                                   value="{{ old('latitude', $aset->latitude) }}"
                                   placeholder="Latitude"
                                   class="border rounded-lg px-3 py-2">

                            <input type="text"
                                   name="longitude"
                                   id="longitude"
                                   value="{{ old('longitude', $aset->longitude) }}"
                                   placeholder="Longitude"
                                   class="border rounded-lg px-3 py-2">
                        </div>
                    </div>

                    {{-- MAP --}}
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
                            Update
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
        const lat = {{ $aset->latitude ?? -7.983908 }};
        const lng = {{ $aset->longitude ?? 112.621391 }};

        const map = L.map('map').setView([lat, lng], 13);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png')
            .addTo(map);

        let marker = L.marker([lat, lng], { draggable:true }).addTo(map);

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
                const newLat = pos.coords.latitude;
                const newLng = pos.coords.longitude;

                marker.setLatLng([newLat,newLng]);
                map.setView([newLat,newLng],16);

                latitude.value = newLat;
                longitude.value = newLng;
            });
        };
    </script>

</x-app-layout>