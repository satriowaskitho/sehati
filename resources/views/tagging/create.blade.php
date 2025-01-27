<x-app-layout>
    @push('leaflet_create')
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    @endpush
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Taging Usaha') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <section class="bg-white dark:bg-gray-900">
                    <div class="py-8 lg:py-16 px-4 mx-auto max-w-screen-md">
                        <form action="{{ route('tagging.store') }}" method="POST" class="space-y-4 form">
                            @csrf

                            <div>
                                <label for="shop_name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">Lokasi Usaha</label>
                                <div id="map" class="lg:w-full max-w-full sm:w-full mx-auto h-52 rounded-xl border-2 border-orange-300 mb-5"></div>
                                <label for="shop_name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">Nama Usaha</label>
                                <input value="{{ old('shop_name') }}" type="text" id="shop_name" name="shop_name" class="@error('shop_name') is-invalid @enderror shadow-sm bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500 dark:shadow-sm-light" placeholder="" required autofocus>
                                @error('shop_name')
                                <div class="pt-2">
                                    <span class="text-red-500 text-sm">Kesalahan input, {{ $message }}</span>
                                </div>
                                @enderror
                            </div>
                            <div class="pb-4">
                                <label for="shop_address" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">Alamat</label>
                                <input value="{{ old('shop_address') }}" type="text" id="shop_address" name="shop_address" class="@error('shop_address') is-invalid @enderror block p-3 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 shadow-sm focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500 dark:shadow-sm-light" placeholder="" required>
                                @error('shop_address')
                                <div class="pt-2">
                                    <span class="text-red-500 text-sm">Kesalahan input, {{ $message }}</span>
                                </div>
                                @enderror
                            </div>
                            <div class="pb-4">
                                <label for="shop_description" class="block mb-2 text-sm font-medium text-gray-900 dark:text-gray-300">Deskripsi</label>
                                <input value="{{ old('shop_description') }}" type="text" id="shop_description" name="shop_description" class="@error('shop_description') is-invalid @enderror block p-3 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 shadow-sm focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500 dark:shadow-sm-light" placeholder="Tuliskan selengkap-lengkapnya" required>
                                <input type="hidden" id="latitude" name="latitude" value="">
                                <input type="hidden" id="longitude" name="longitude" value="">
                                @error('shop_description')
                                <div class="pt-2">
                                    <span class="text-red-500 text-sm">Kesalahan input, {{ $message }}</span>
                                </div>
                                @enderror
                            </div>
                            <div class="flex justify-between space-x-5">
                                <button type="button" onclick="window.location.href='{{ route('tagging.index') }}'" class="w-full py-3 px-5 text-sm font-medium text-center text-white rounded-lg bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:outline-none focus:ring-primary-300 dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">Kembali</button>
                                <button type="submit" class="w-full py-3 px-5 text-sm font-medium text-center text-white rounded-lg bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:outline-none focus:ring-primary-300 dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">Submit</button>
                            </div>
                        </form>
                    </div>
                </section>
            </div>
        </div>
    </div>
    @push('leaflet_create_js')
    <script>
        window.sr = ScrollReveal({
                duration: 500,
                distance: '0px',
                easing: 'ease-in-out'
            });

            sr.reveal('.form', {
                opacity: 0,
                origin: 'bottom',
                reset: false
            })
    </script>
    <script>
        // Cek apakah Geolocation API tersedia
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                function (position) {
                    // Ambil koordinat pengguna
                    const userLat = position.coords.latitude;
                    const userLng = position.coords.longitude;
    
                    // Inisialisasi peta dengan lokasi pengguna
                    const map = L.map('map').setView([userLat, userLng], 13);
    
                    // Tambahkan tile layer (peta dasar)
                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        maxZoom: 19,
                        attribution: '© OpenStreetMap contributors'
                    }).addTo(map);
    
                    // Tambahkan marker di lokasi pengguna
                    const marker = L.marker([userLat, userLng]).addTo(map)
                        .bindPopup('Anda berada di sini!').openPopup();
    
                    // Menyimpan latitude dan longitude ke input hidden
                    document.getElementById('latitude').value = userLat;
                    document.getElementById('longitude').value = userLng;
                },
                function (error) {
                    // Jika terjadi error (misalnya izin ditolak)
                    console.error('Geolocation Error:', error.message);
    
                    // Default lokasi jika tidak ada akses lokasi pengguna
                    const defaultLat = -6.1751; // Jakarta
                    const defaultLng = 106.8650;
    
                    const map = L.map('map').setView([defaultLat, defaultLng], 13);
    
                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        maxZoom: 19,
                        attribution: '© OpenStreetMap contributors'
                    }).addTo(map);
    
                    L.marker([defaultLat, defaultLng]).addTo(map)
                        .bindPopup('Lokasi default: Jakarta').openPopup();
    
                    // Menyimpan latitude dan longitude default ke input hidden
                    document.getElementById('latitude').value = defaultLat;
                    document.getElementById('longitude').value = defaultLng;
                }
            );
        } else {
            console.error('Geolocation tidak didukung oleh browser ini.');
    
            // Default lokasi jika Geolocation API tidak didukung
            const defaultLat = -6.1751; // Jakarta
            const defaultLng = 106.8650;
    
            const map = L.map('map').setView([defaultLat, defaultLng], 13);
    
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '© OpenStreetMap contributors'
            }).addTo(map);
    
            L.marker([defaultLat, defaultLng]).addTo(map)
                .bindPopup('Lokasi default: Jakarta').openPopup();
    
            // Menyimpan latitude dan longitude default ke input hidden
            document.getElementById('latitude').value = defaultLat;
            document.getElementById('longitude').value = defaultLng;
        }
    </script>
    
    @endpush
</x-app-layout>