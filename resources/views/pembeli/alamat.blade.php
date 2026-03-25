@extends('layouts.dashboard')
@section('title', 'Alamat Pengiriman')
@section('page-title', 'Alamat Pengiriman')
@section('sidebar') @include('components.sidebar-pembeli') @endsection

@section('content')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

<div class="p-6">
    <div class="grid grid-cols-2 gap-6">

        {{-- Kolom Kiri: Form Tambah Alamat --}}
        <div class="bg-white border border-gray-200 rounded-xl p-5">
            <h3 class="font-bold text-gray-800 text-base mb-5 pb-3 border-b border-gray-100 flex items-center gap-2">
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="16"/><line x1="8" y1="12" x2="16" y2="12"/>
                </svg>
                Tambah Alamat Baru
            </h3>

            <form method="POST" action="{{ route('pembeli.alamat.store') }}" class="space-y-3">
                @csrf
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1.5">Label Alamat <span class="text-red-500">*</span></label>
                        <select name="label" class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm bg-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @foreach(['Rumah','Kantor','Kost','Lainnya'] as $label)
                            <option value="{{ $label }}">{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1.5">Nama Penerima <span class="text-red-500">*</span></label>
                        <input type="text" name="nama_penerima" required
                            class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1.5">No. HP Penerima <span class="text-red-500">*</span></label>
                    <input type="text" name="no_hp" required placeholder="08xxxxxxxxxx"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1.5">Lokasi</label>
                    <button type="button" onclick="bukaModal()"
                        class="w-full flex items-center justify-center gap-2 border-2 border-dashed border-blue-400 text-blue-600 font-semibold rounded-lg px-3 py-2.5 text-sm hover:bg-blue-50 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        <span id="tombol-maps-label">Pilih Lokasi dari Maps</span>
                    </button>
                    <input type="hidden" name="latitude" id="input-lat">
                    <input type="hidden" name="longitude" id="input-lng">
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1.5">Alamat Lengkap <span class="text-red-500">*</span></label>
                    <textarea name="alamat_lengkap" id="input-alamat" rows="2" required
                        class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm resize-none focus:outline-none focus:ring-2 focus:ring-blue-500 transition"
                        placeholder="Nama jalan, nomor rumah, RT/RW..."></textarea>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1.5">Kelurahan</label>
                        <input type="text" name="kelurahan" id="input-kelurahan"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1.5">Kecamatan <span class="text-red-500">*</span></label>
                        <select name="kecamatan" id="input-kecamatan" required
                            class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm bg-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Pilih kecamatan</option>
                            @foreach(config('ranahmart.kecamatan_padang') as $kec)
                            <option value="{{ $kec }}">{{ $kec }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 mb-1.5">Kode Pos</label>
                    <input type="text" name="kode_pos" id="input-kodepos" maxlength="10"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 transition"
                        placeholder="25xxx">
                </div>

                <button type="submit"
                    class="w-full bg-blue-600 text-white font-bold py-2.5 rounded-lg hover:bg-blue-700 active:scale-95 transition-all text-sm">
                    Simpan Alamat
                </button>
            </form>
        </div>

        {{-- Kolom Kanan: Daftar Alamat --}}
        <div>
            <h3 class="font-bold text-gray-800 text-base mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/>
                </svg>
                Alamat Tersimpan
                @if(isset($alamats) && $alamats->count())
                <span class="ml-auto text-xs text-gray-400 font-normal">{{ $alamats->count() }} alamat</span>
                @endif
            </h3>

            @if(isset($alamats) && $alamats->count())

            @php $perPage = 4; $totalAlamat = $alamats->count(); $totalHalaman = ceil($totalAlamat / $perPage); @endphp

            {{-- Slider Container --}}
            <div class="relative">
                <div id="slider-wrapper" class="space-y-3">
                    @foreach($alamats as $i => $alamat)
                    <div class="alamat-item {{ $i >= $perPage ? 'hidden' : '' }} bg-white border-2 rounded-xl p-4 transition-all
                        {{ $alamat->is_utama ? 'border-blue-500 bg-blue-50/30' : 'border-gray-200' }}">
                        <div class="flex items-start gap-3">
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2 mb-1">
                                    <span class="text-sm font-bold text-gray-800">{{ $alamat->label }}</span>
                                    @if($alamat->is_utama)
                                    <span class="text-xs bg-blue-100 text-blue-700 font-bold px-2 py-0.5 rounded-full">⭐ Utama</span>
                                    @endif
                                </div>
                                <div class="text-sm font-semibold text-gray-700">{{ $alamat->nama_penerima }}</div>
                                <div class="text-xs text-gray-500 mt-0.5">{{ $alamat->no_hp }}</div>
                                <div class="text-xs text-gray-600 mt-1.5 leading-relaxed">
                                    {{ $alamat->alamat_lengkap }},
                                    @if($alamat->kelurahan) {{ $alamat->kelurahan }}, @endif
                                    {{ $alamat->kecamatan }}, {{ $alamat->kota }}, {{ $alamat->provinsi }}
                                    {{ $alamat->kode_pos }}
                                </div>
                            </div>
                        </div>
                        <div class="flex gap-2 mt-3 pt-3 border-t border-gray-100">
                            @if(!$alamat->is_utama)
                            <form method="POST" action="{{ route('pembeli.alamat.utama', $alamat->id) }}">
                                @csrf @method('PATCH')
                                <button class="text-xs text-blue-600 font-semibold hover:underline">Jadikan Utama</button>
                            </form>
                            <span class="text-gray-300">|</span>
                            @endif
                            <a href="{{ route('pembeli.alamat.edit', $alamat->id) }}"
                                class="text-xs text-gray-500 font-semibold hover:text-gray-700">Edit</a>
                            @if(!$alamat->is_utama)
                            <span class="text-gray-300">|</span>
                            <form method="POST" action="{{ route('pembeli.alamat.destroy', $alamat->id) }}"
                                onsubmit="return confirm('Hapus alamat ini?')">
                                @csrf @method('DELETE')
                                <button class="text-xs text-red-400 font-semibold hover:text-red-600">Hapus</button>
                            </form>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>

                {{-- Pagination hanya muncul jika lebih dari 4 --}}
                @if($totalHalaman > 1)
                <div class="flex items-center justify-center gap-2 mt-5">
                    <button onclick="gantiHalaman(currentPage - 1)"
                        id="btn-prev"
                        class="w-8 h-8 rounded-lg border border-gray-300 flex items-center justify-center text-gray-500 hover:bg-gray-50 disabled:opacity-30 transition"
                        disabled>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <polyline points="15 18 9 12 15 6"/>
                        </svg>
                    </button>

                    <div id="page-numbers" class="flex gap-1">
                        @for($p = 1; $p <= $totalHalaman; $p++)
                        <button onclick="gantiHalaman({{ $p }})"
                            class="page-btn w-8 h-8 rounded-lg text-xs font-bold transition
                            {{ $p === 1 ? 'bg-blue-600 text-white' : 'border border-gray-300 text-gray-600 hover:bg-gray-50' }}">
                            {{ $p }}
                        </button>
                        @endfor
                    </div>

                    <button onclick="gantiHalaman(currentPage + 1)"
                        id="btn-next"
                        class="w-8 h-8 rounded-lg border border-gray-300 flex items-center justify-center text-gray-500 hover:bg-gray-50 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <polyline points="9 18 15 12 9 6"/>
                        </svg>
                    </button>
                </div>
                @endif
            </div>

            @else
            <div class="bg-white border-2 border-dashed border-gray-200 rounded-xl p-10 text-center text-gray-400">
                <div class="text-4xl mb-3">📍</div>
                <p class="text-sm font-semibold text-gray-500">Belum ada alamat tersimpan</p>
                <p class="text-xs mt-1">Tambahkan alamat pengiriman kamu</p>
            </div>
            @endif
        </div>

    </div>
</div>

{{-- Modal Maps --}}
<div id="modal-maps" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-black/60">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl mx-4 overflow-hidden">
        <div class="flex items-center justify-between px-5 py-4 border-b border-gray-200">
            <div>
                <h3 class="font-bold text-gray-800">Pilih Lokasi</h3>
                <p class="text-xs text-gray-500 mt-0.5">Klik pada peta atau seret pin untuk memilih lokasi</p>
            </div>
            <button onclick="tutupModal()" class="text-gray-400 hover:text-gray-600 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        <div class="px-5 pt-3">
            <button type="button" onclick="gunakanLokasiSaya()"
                class="flex items-center gap-2 text-xs font-semibold text-blue-600 hover:text-blue-800 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8c-2.21 0-4 1.79-4 4s1.79 4 4 4 4-1.79 4-4-1.79-4-4-4zm8.94 3A8.994 8.994 0 0013 3.06V1h-2v2.06A8.994 8.994 0 003.06 11H1v2h2.06A8.994 8.994 0 0011 20.94V23h2v-2.06A8.994 8.994 0 0020.94 13H23v-2h-2.06z"/>
                </svg>
                Gunakan Lokasi Saya
            </button>
        </div>
        <div id="peta" class="w-full h-80 mt-3"></div>
        <div class="px-5 py-4 border-t border-gray-100">
            <p class="text-xs text-gray-500 mb-1">Alamat terpilih:</p>
            <p id="hasil-alamat" class="text-sm text-gray-700 font-medium min-h-[20px]">—</p>
        </div>
        <div class="flex gap-3 px-5 pb-5">
            <button type="button" onclick="tutupModal()"
                class="flex-1 border border-gray-300 text-gray-600 font-semibold py-2 rounded-lg text-sm hover:bg-gray-50 transition">
                Batal
            </button>
            <button type="button" onclick="konfirmasiLokasi()"
                class="flex-1 bg-blue-600 text-white font-bold py-2 rounded-lg text-sm hover:bg-blue-700 transition">
                Gunakan Lokasi Ini
            </button>
        </div>
    </div>
</div>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    // ── Pagination Slider ──
    const perPage = 4;
    let currentPage = 1;
    const items = document.querySelectorAll('.alamat-item');
    const totalItems = items.length;
    const totalPages = Math.ceil(totalItems / perPage);

    function gantiHalaman(page) {
        if (page < 1 || page > totalPages) return;
        currentPage = page;

        // Tampil / sembunyikan item
        items.forEach((el, i) => {
            const start = (currentPage - 1) * perPage;
            const end   = start + perPage;
            el.classList.toggle('hidden', i < start || i >= end);
        });

        // Update tombol prev/next
        const btnPrev = document.getElementById('btn-prev');
        const btnNext = document.getElementById('btn-next');
        if (btnPrev) btnPrev.disabled = currentPage === 1;
        if (btnNext) btnNext.disabled = currentPage === totalPages;

        // Update highlight nomor halaman
        document.querySelectorAll('.page-btn').forEach((btn, i) => {
            const active = i + 1 === currentPage;
            btn.className = `page-btn w-8 h-8 rounded-lg text-xs font-bold transition ${
                active ? 'bg-blue-600 text-white' : 'border border-gray-300 text-gray-600 hover:bg-gray-50'
            }`;
        });
    }

    // ── Maps ──
    let peta = null, marker = null, lokasiTerpilih = null;

    function bukaModal() {
        document.getElementById('modal-maps').classList.remove('hidden');
        if (!peta) {
            peta = L.map('peta').setView([-0.9471, 100.4172], 13);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors'
            }).addTo(peta);
            peta.on('click', function(e) { aturMarker(e.latlng.lat, e.latlng.lng); });
        }
        setTimeout(() => peta.invalidateSize(), 100);
    }

    function tutupModal() { document.getElementById('modal-maps').classList.add('hidden'); }

    function aturMarker(lat, lng) {
        if (marker) { marker.setLatLng([lat, lng]); }
        else {
            marker = L.marker([lat, lng], { draggable: true }).addTo(peta);
            marker.on('dragend', function(e) {
                const pos = e.target.getLatLng();
                reverseGeocode(pos.lat, pos.lng);
            });
        }
        peta.setView([lat, lng], 16);
        reverseGeocode(lat, lng);
    }

    function reverseGeocode(lat, lng) {
        document.getElementById('hasil-alamat').textContent = 'Memuat alamat...';
        fetch(`https://nominatim.openstreetmap.org/reverse?lat=${lat}&lng=${lng}&format=json&addressdetails=1`)
            .then(r => r.json())
            .then(data => {
                const addr = data.address || {};
                lokasiTerpilih = {
                    lat, lng,
                    display: data.display_name || '—',
                    road: addr.road || addr.pedestrian || addr.suburb || '',
                    kelurahan: addr.village || addr.suburb || addr.neighbourhood || '',
                    kecamatan: addr.suburb || addr.city_district || '',
                    kode_pos: addr.postcode || '',
                };
                document.getElementById('hasil-alamat').textContent = data.display_name || '—';
            })
            .catch(() => { document.getElementById('hasil-alamat').textContent = 'Gagal memuat alamat.'; });
    }

    function gunakanLokasiSaya() {
        if (!navigator.geolocation) { alert('Browser kamu tidak mendukung geolocation.'); return; }
        navigator.geolocation.getCurrentPosition(
            pos => aturMarker(pos.coords.latitude, pos.coords.longitude),
            () => alert('Tidak dapat mengakses lokasi.')
        );
    }

    function konfirmasiLokasi() {
        if (!lokasiTerpilih) { alert('Pilih lokasi terlebih dahulu.'); return; }
        document.getElementById('input-lat').value      = lokasiTerpilih.lat;
        document.getElementById('input-lng').value      = lokasiTerpilih.lng;
        document.getElementById('input-alamat').value   = lokasiTerpilih.road || lokasiTerpilih.display;
        document.getElementById('input-kelurahan').value = lokasiTerpilih.kelurahan;
        document.getElementById('input-kodepos').value  = lokasiTerpilih.kode_pos;
        const kecSelect = document.getElementById('input-kecamatan');
        const kecDariMaps = lokasiTerpilih.kecamatan.toLowerCase();
        let cocok = false;
        for (let opt of kecSelect.options) {
            if (opt.value.toLowerCase().includes(kecDariMaps) || kecDariMaps.includes(opt.value.toLowerCase())) {
                opt.selected = true; cocok = true; break;
            }
        }
        if (!cocok) kecSelect.value = '';
        document.getElementById('tombol-maps-label').textContent = '📍 Lokasi dipilih dari maps';
        tutupModal();
    }
</script>
@endsection