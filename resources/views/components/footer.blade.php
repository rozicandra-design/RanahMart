<footer class="bg-white border-t border-gray-200 py-10 mt-16">
    <div class="max-w-5xl mx-auto px-4">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6 mb-8">
            <div>
                <div class="font-bold text-lg text-gray-800 mb-2">
                    Ranah<span class="text-red-600">Mart</span>
                </div>
                <p class="text-xs text-gray-500 leading-relaxed">
                    Platform e-commerce resmi pemberdayaan UMKM Kota Padang, Sumatera Barat.
                </p>
                <div class="flex gap-2 mt-3">
                    <div class="w-6 h-6 bg-blue-600 rounded text-white text-xs flex items-center justify-center font-bold">f</div>
                    <div class="w-6 h-6 bg-pink-500 rounded text-white text-xs flex items-center justify-center font-bold">ig</div>
                    <div class="w-6 h-6 bg-red-600 rounded text-white text-xs flex items-center justify-center font-bold">yt</div>
                </div>
            </div>
            <div>
                <div class="text-xs font-bold text-gray-700 uppercase tracking-wider mb-3">Tentang</div>
                <div class="space-y-2 text-xs text-gray-500">
                    <div><a href="#" class="hover:text-red-600 transition">Tentang RanahMart</a></div>
                    <div><a href="#" class="hover:text-red-600 transition">Cara Kerja</a></div>
                    <div><a href="#" class="hover:text-red-600 transition">Kontak Kami</a></div>
                    <div><a href="#" class="hover:text-red-600 transition">Karir</a></div>
                </div>
            </div>
            <div>
                <div class="text-xs font-bold text-gray-700 uppercase tracking-wider mb-3">Untuk UMKM</div>
                <div class="space-y-2 text-xs text-gray-500">
                    <div><a href="{{ route('register.umkm') }}" class="hover:text-red-600 transition">Daftar UMKM</a></div>
                    <div><a href="#" class="hover:text-red-600 transition">Panduan Penjual</a></div>
                    <div><a href="#" class="hover:text-red-600 transition">Pasang Iklan</a></div>
                    <div><a href="#" class="hover:text-red-600 transition">Program Pembinaan</a></div>
                </div>
            </div>
            <div>
                <div class="text-xs font-bold text-gray-700 uppercase tracking-wider mb-3">Bantuan</div>
                <div class="space-y-2 text-xs text-gray-500">
                    <div><a href="#" class="hover:text-red-600 transition">Pusat Bantuan</a></div>
                    <div><a href="#" class="hover:text-red-600 transition">Info Pengiriman</a></div>
                    <div><a href="#" class="hover:text-red-600 transition">Pengembalian Barang</a></div>
                    <div><a href="#" class="hover:text-red-600 transition">Syarat & Ketentuan</a></div>
                </div>
            </div>
        </div>
        <div class="border-t border-gray-100 pt-5 flex flex-col md:flex-row items-center justify-between gap-2">
            <p class="text-xs text-gray-400">© {{ date('Y') }} RanahMart · Platform UMKM Kota Padang</p>
            <div class="flex items-center gap-3">
                <span class="text-xs text-gray-400 bg-gray-100 px-2 py-1 rounded">🔒 SSL Secured</span>
                <span class="text-xs text-gray-400">Didukung Dinas Koperasi & UMKM Kota Padang</span>
            </div>
        </div>
    </div>
</footer>