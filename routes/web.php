<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Public\HomeController;
use App\Http\Controllers\Public\ProdukController as PublicProdukController;
use App\Http\Controllers\Public\UmkmController as PublicUmkmController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\UmkmController as AdminUmkm;
use App\Http\Controllers\Admin\ProdukController as AdminProduk;
use App\Http\Controllers\Admin\IklanController as AdminIklan;
use App\Http\Controllers\Admin\TransaksiController as AdminTransaksi;
use App\Http\Controllers\Admin\ReturController as AdminRetur;
use App\Http\Controllers\Admin\LaporanController as AdminLaporan;
use App\Http\Controllers\Penjual\DashboardController as PenjualDashboard;
use App\Http\Controllers\Penjual\ProdukController as PenjualProduk;
use App\Http\Controllers\Penjual\PesananController as PenjualPesanan;
use App\Http\Controllers\Penjual\KeuanganController;
use App\Http\Controllers\Penjual\UlasanController as PenjualUlasan;
use App\Http\Controllers\Penjual\IklanController as PenjualIklan;
use App\Http\Controllers\Penjual\PromoController;
use App\Http\Controllers\Penjual\TokoController;
use App\Http\Controllers\Pembeli\DashboardController as PembeliDashboard;
use App\Http\Controllers\Pembeli\PesananController as PembeliPesanan;
use App\Http\Controllers\Pembeli\KeranjangController;
use App\Http\Controllers\Pembeli\WishlistController;
use App\Http\Controllers\Pembeli\UlasanController as PembeliUlasan;
use App\Http\Controllers\Pembeli\ReturController as PembeliRetur;
use App\Http\Controllers\Pembeli\AlamatController;
use App\Http\Controllers\Pembeli\VoucherController;
use App\Http\Controllers\Pembeli\PoinController;
use App\Http\Controllers\Pembeli\ProfilController;
use App\Http\Controllers\Dinas\DashboardController as DinasDashboard;
use App\Http\Controllers\Dinas\VerifikasiController;
use App\Http\Controllers\Dinas\MonitoringController;
use App\Http\Controllers\Dinas\PembinaanController;
use App\Http\Controllers\Dinas\LaporanController as DinasLaporan;
use App\Http\Controllers\Dinas\StatistikController;
use App\Http\Controllers\Dinas\PengumumanController;
use App\Http\Controllers\Dinas\SertifikatController;

/*
|--------------------------------------------------------------------------
| PUBLIC ROUTES
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/produk', [PublicProdukController::class, 'index'])->name('produk.index');
Route::get('/produk/{produk:slug}', [PublicProdukController::class, 'show'])->name('produk.show');
Route::get('/toko/{toko:slug}', [PublicUmkmController::class, 'show'])->name('toko.show');
Route::get('/kategori/{kategori}', [PublicProdukController::class, 'kategori'])->name('produk.kategori');
Route::get('/flash-sale', [PublicProdukController::class, 'flashSale'])->name('produk.flash-sale');
Route::get('/umkm', [PublicUmkmController::class, 'index'])->name('umkm.index');
Route::get('/tentang', fn() => view('public.tentang'))->name('tentang');

/*
|--------------------------------------------------------------------------
| AUTH ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);

    Route::get('/daftar', [RegisterController::class, 'showPembeliForm'])->name('register.pembeli');
    Route::post('/daftar', [RegisterController::class, 'registerPembeli']);

    Route::get('/daftar/umkm', [RegisterController::class, 'showUmkmStep1'])->name('register.umkm');
    Route::post('/daftar/umkm/step1', [RegisterController::class, 'storeUmkmStep1'])->name('register.umkm.step1');
    Route::get('/daftar/umkm/step2', [RegisterController::class, 'showUmkmStep2'])->name('register.umkm.step2');
    Route::post('/daftar/umkm/step2', [RegisterController::class, 'storeUmkmStep2'])->name('register.umkm.step2.store');
    Route::get('/daftar/umkm/step3', [RegisterController::class, 'showUmkmStep3'])->name('register.umkm.step3');
    Route::post('/daftar/umkm/step3', [RegisterController::class, 'storeUmkmStep3'])->name('register.umkm.step3.store');
});

Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

/*
|--------------------------------------------------------------------------
| ADMIN ROUTES
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/dashboard', [AdminDashboard::class, 'index'])->name('dashboard');

    Route::resource('users', UserController::class);
    Route::patch('users/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggle-status');
    Route::patch('users/{user}/reset-password', [UserController::class, 'resetPassword'])->name('users.reset-password');

    Route::resource('umkm', AdminUmkm::class);
    Route::patch('umkm/{toko}/setujui', [AdminUmkm::class, 'setujui'])->name('umkm.setujui');
    Route::patch('umkm/{toko}/tolak', [AdminUmkm::class, 'tolak'])->name('umkm.tolak');
    Route::patch('umkm/{toko}/minta-dokumen', [AdminUmkm::class, 'mintaDokumen'])->name('umkm.minta-dokumen');
    Route::patch('umkm/{toko}/teruskan-dinas', [AdminUmkm::class, 'teruskanDinas'])->name('umkm.teruskan-dinas');
    Route::patch('umkm/{toko}/nonaktif', [AdminUmkm::class, 'nonaktif'])->name('umkm.nonaktif');

    Route::resource('produk', AdminProduk::class);
    Route::patch('produk/{produk}/setujui', [AdminProduk::class, 'setujui'])->name('produk.setujui');
    Route::patch('produk/{produk}/tolak', [AdminProduk::class, 'tolak'])->name('produk.tolak');
    Route::patch('produk/{produk}/turunkan', [AdminProduk::class, 'turunkan'])->name('produk.turunkan');
    Route::patch('produk/{produk}/peringatkan', [AdminProduk::class, 'peringatkan'])->name('produk.peringatkan');

    Route::resource('iklan', AdminIklan::class);
    Route::patch('iklan/{iklan}/setujui', [AdminIklan::class, 'setujui'])->name('iklan.setujui');
    Route::patch('iklan/{iklan}/tolak', [AdminIklan::class, 'tolak'])->name('iklan.tolak');
    Route::patch('iklan/{iklan}/revisi', [AdminIklan::class, 'revisi'])->name('iklan.revisi');
    Route::patch('iklan/{iklan}/hentikan', [AdminIklan::class, 'hentikan'])->name('iklan.hentikan');

    Route::get('transaksi', [AdminTransaksi::class, 'index'])->name('transaksi.index');
    Route::get('transaksi/export', [AdminTransaksi::class, 'export'])->name('transaksi.export');
    Route::get('transaksi/{transaksi}', [AdminTransaksi::class, 'show'])->name('transaksi.show');

    Route::resource('retur', AdminRetur::class)->only(['index', 'show']);
    Route::patch('retur/{retur}/setujui', [AdminRetur::class, 'setujui'])->name('retur.setujui');
    Route::patch('retur/{retur}/tolak', [AdminRetur::class, 'tolak'])->name('retur.tolak');

    Route::get('laporan', [AdminLaporan::class, 'index'])->name('laporan.index');
    Route::get('laporan/export', [AdminLaporan::class, 'export'])->name('laporan.export');

    Route::get('pengaturan', [AdminDashboard::class, 'pengaturan'])->name('pengaturan');
    Route::post('pengaturan', [AdminDashboard::class, 'simpanPengaturan'])->name('pengaturan.simpan');

    Route::get('notifikasi', [AdminDashboard::class, 'notifikasi'])->name('notifikasi');
    Route::patch('notifikasi/baca-semua', [AdminDashboard::class, 'bacaSemua'])->name('notifikasi.baca-semua');
});

/*
|--------------------------------------------------------------------------
| PENJUAL ROUTES
|--------------------------------------------------------------------------
*/
Route::prefix('penjual')->name('penjual.')->middleware(['auth', 'role:penjual'])->group(function () {
    Route::get('/dashboard', [PenjualDashboard::class, 'index'])->name('dashboard');

    // Produk
    Route::resource('produk', PenjualProduk::class);
    Route::patch('produk/{produk}/toggle-status', [PenjualProduk::class, 'toggleStatus'])->name('produk.toggle');

    // Pesanan
    Route::get('pesanan', [PenjualPesanan::class, 'index'])->name('pesanan.index');
    Route::get('pesanan/{pesanan}', [PenjualPesanan::class, 'show'])->name('pesanan.show');
    Route::patch('pesanan/{pesanan}/konfirmasi', [PenjualPesanan::class, 'konfirmasi'])->name('pesanan.konfirmasi');
    Route::patch('pesanan/{pesanan}/tolak', [PenjualPesanan::class, 'tolak'])->name('pesanan.tolak');
    Route::patch('pesanan/{pesanan}/kirim', [PenjualPesanan::class, 'kirim'])->name('pesanan.kirim');

    // Keuangan
    Route::get('keuangan', [KeuanganController::class, 'index'])->name('keuangan.index');
    Route::post('keuangan/cairkan', [KeuanganController::class, 'cairkan'])->name('keuangan.cairkan');
    Route::get('keuangan/riwayat', [KeuanganController::class, 'riwayat'])->name('keuangan.riwayat');

    // Ulasan
    Route::get('ulasan', [PenjualUlasan::class, 'index'])->name('ulasan.index');
    Route::post('ulasan/{ulasan}/balas', [PenjualUlasan::class, 'balas'])->name('ulasan.balas');

    // Laporan
    Route::get('laporan', [App\Http\Controllers\Penjual\LaporanController::class, 'index'])->name('laporan');
    Route::get('laporan/export', [App\Http\Controllers\Penjual\LaporanController::class, 'export'])->name('laporan.export');
Route::get('laporan/cetak', [App\Http\Controllers\Penjual\LaporanController::class, 'cetak'])->name('laporan.cetak');

    // Iklan
    Route::resource('iklan', PenjualIklan::class)->only(['index', 'create', 'store', 'destroy']);

    // Promo & Voucher
    Route::resource('promo', PromoController::class);
    Route::patch('promo/{promo}/toggle', [PromoController::class, 'toggle'])->name('promo.toggle');

    // Profil Toko & Dokumen
    Route::get('toko', [TokoController::class, 'edit'])->name('toko.edit');
    Route::put('toko', [TokoController::class, 'update'])->name('toko.update');
    Route::get('toko/dokumen', [TokoController::class, 'dokumen'])->name('toko.dokumen');
    Route::post('toko/dokumen', [TokoController::class, 'uploadDokumen'])->name('toko.dokumen.upload');
    Route::get('toko/sertifikat', [TokoController::class, 'sertifikat'])->name('toko.sertifikat'); // ← TAMBAHAN

    // Notifikasi
    Route::get('notifikasi', [PenjualDashboard::class, 'notifikasi'])->name('notifikasi');
    Route::patch('notifikasi/baca-semua', [PenjualDashboard::class, 'bacaSemua'])->name('notifikasi.baca-semua');

    // Pengaturan
    Route::get('pengaturan', [PenjualDashboard::class, 'pengaturan'])->name('pengaturan');
    Route::put('pengaturan', [PenjualDashboard::class, 'simpanPengaturan'])->name('pengaturan.simpan');
});

/*
|--------------------------------------------------------------------------
| PEMBELI ROUTES
|--------------------------------------------------------------------------
*/
Route::prefix('pembeli')->name('pembeli.')->middleware(['auth', 'role:pembeli'])->group(function () {
    Route::get('/dashboard', [PembeliDashboard::class, 'index'])->name('dashboard');

    // Pesanan
    Route::get('pesanan', [PembeliPesanan::class, 'index'])->name('pesanan.index');
    Route::get('pesanan/{pesanan}', [PembeliPesanan::class, 'show'])->name('pesanan.show');
    Route::patch('pesanan/{pesanan}/konfirmasi-terima', [PembeliPesanan::class, 'konfirmasiTerima'])->name('pesanan.konfirmasi-terima');
    Route::patch('pesanan/{pesanan}/batalkan', [PembeliPesanan::class, 'batalkan'])->name('pesanan.batalkan');
    Route::post('pesanan/checkout', [PembeliPesanan::class, 'checkout'])->name('pesanan.checkout');

    // Keranjang
    Route::get('keranjang', [KeranjangController::class, 'index'])->name('keranjang.index');
    Route::post('keranjang', [KeranjangController::class, 'tambah'])->name('keranjang.tambah');
    Route::patch('keranjang/{item}', [KeranjangController::class, 'updateQty'])->name('keranjang.update');
    Route::delete('keranjang/{item}', [KeranjangController::class, 'hapus'])->name('keranjang.hapus');
    Route::delete('keranjang', [KeranjangController::class, 'kosongkan'])->name('keranjang.kosongkan');

    // Wishlist
    Route::get('wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
    Route::post('wishlist/{produk}', [WishlistController::class, 'toggle'])->name('wishlist.toggle');

    // Ulasan
    Route::get('ulasan', [PembeliUlasan::class, 'index'])->name('ulasan.index');
    Route::post('ulasan', [PembeliUlasan::class, 'store'])->name('ulasan.store');

    // Retur
    Route::get('retur', [PembeliRetur::class, 'index'])->name('retur.index');
    Route::post('retur', [PembeliRetur::class, 'store'])->name('retur.store');
    Route::get('retur/{retur}', [PembeliRetur::class, 'show'])->name('retur.show');

    // Alamat
    Route::resource('alamat', AlamatController::class)->except(['show']);
    Route::patch('alamat/{alamat}/utama', [AlamatController::class, 'jadikanUtama'])->name('alamat.utama');

    // Voucher & Poin
    Route::get('voucher', [VoucherController::class, 'index'])->name('voucher.index');
    Route::post('voucher/validasi', [VoucherController::class, 'validasi'])->name('voucher.validasi');
    Route::get('poin', [PoinController::class, 'index'])->name('poin.index');
    Route::post('poin/tukar', [PoinController::class, 'tukar'])->name('poin.tukar');

    // Profil & Pengaturan
    Route::get('profil', [ProfilController::class, 'edit'])->name('profil.edit');
    Route::put('profil', [ProfilController::class, 'update'])->name('profil.update');
    Route::put('profil/password', [ProfilController::class, 'updatePassword'])->name('profil.password');

    // Notifikasi
    Route::get('notifikasi', [PembeliDashboard::class, 'notifikasi'])->name('notifikasi');
    Route::patch('notifikasi/baca-semua', [PembeliDashboard::class, 'bacaSemua'])->name('notifikasi.baca-semua');

    // Pengaturan
    Route::get('pengaturan', [PembeliDashboard::class, 'pengaturan'])->name('pengaturan');
    Route::put('pengaturan', [PembeliDashboard::class, 'simpanPengaturan'])->name('pengaturan.simpan');
});

/*
|--------------------------------------------------------------------------
| DINAS ROUTES
|--------------------------------------------------------------------------
*/
Route::prefix('dinas')->name('dinas.')->middleware(['auth', 'role:dinas'])->group(function () {
    Route::get('/dashboard', [DinasDashboard::class, 'index'])->name('dashboard');

    // Verifikasi UMKM
    Route::get('verifikasi', [VerifikasiController::class, 'index'])->name('verifikasi.index');
    Route::get('verifikasi/{toko}', [VerifikasiController::class, 'show'])->name('verifikasi.show');
    Route::patch('verifikasi/{toko}/setujui', [VerifikasiController::class, 'setujui'])->name('verifikasi.setujui');
    Route::patch('verifikasi/{toko}/tolak', [VerifikasiController::class, 'tolak'])->name('verifikasi.tolak');
    Route::patch('verifikasi/{toko}/minta-dokumen', [VerifikasiController::class, 'mintaDokumen'])->name('verifikasi.minta-dokumen');
    Route::post('verifikasi/{toko}/kunjungan', [VerifikasiController::class, 'jadwalKunjungan'])->name('verifikasi.kunjungan');
    Route::get('verifikasi/{toko}/sertifikat', [SertifikatController::class, 'preview'])->name('verifikasi.sertifikat');
    Route::post('verifikasi/{toko}/sertifikat', [SertifikatController::class, 'simpan'])->name('verifikasi.sertifikat.simpan');
    Route::get('verifikasi/{toko}/sertifikat/pdf', [SertifikatController::class, 'pdf'])->name('verifikasi.sertifikat.pdf');

    // Monitoring UMKM
    Route::get('monitoring', [MonitoringController::class, 'index'])->name('monitoring.index');
    Route::get('monitoring/{toko}', [MonitoringController::class, 'show'])->name('monitoring.show');

    // Statistik Wilayah
    Route::get('statistik', [StatistikController::class, 'index'])->name('statistik.index');
    Route::get('statistik/export', [StatistikController::class, 'export'])->name('statistik.export');

    // Program Pembinaan
    Route::resource('pembinaan', PembinaanController::class);
    Route::post('pembinaan/{program}/daftarkan', [PembinaanController::class, 'daftarkanUmkm'])->name('pembinaan.daftarkan');

    // Pengumuman
    Route::resource('pengumuman', PengumumanController::class);

    // Laporan
    Route::get('laporan', [DinasLaporan::class, 'index'])->name('laporan.index');
    Route::get('laporan/export', [DinasLaporan::class, 'export'])->name('laporan.export');

    // Notifikasi
    Route::get('notifikasi', [DinasDashboard::class, 'notifikasi'])->name('notifikasi');
    Route::patch('notifikasi/baca-semua', [DinasDashboard::class, 'bacaSemua'])->name('notifikasi.baca-semua');

    // Pengaturan
    Route::get('pengaturan', [DinasDashboard::class, 'pengaturan'])->name('pengaturan');
    Route::put('pengaturan', [DinasDashboard::class, 'simpanPengaturan'])->name('pengaturan.simpan');
});