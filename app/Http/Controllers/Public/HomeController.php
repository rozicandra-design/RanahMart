<?php
namespace App\Http\Controllers\Public;
use App\Http\Controllers\Controller;
use App\Models\Produk;
use App\Models\Toko;
use App\Models\Iklan;
use Illuminate\Support\Collection;

class HomeController extends Controller
{
    public function index()
    {
        // Iklan aktif — aman jika tabel kosong
        try {
            $iklans = Iklan::where('status', 'aktif')
                ->where('tanggal_mulai', '<=', now())
                ->where('tanggal_selesai', '>=', now())
                ->with('toko')
                ->orderByDesc('biaya')
                ->limit(3)
                ->get();
        } catch (\Exception $e) {
            $iklans = collect();
        }

        // Produk terlaris — aman jika tabel kosong
        try {
            $produkLaris = Produk::where('status', 'aktif')
                ->orderByDesc('total_terjual')
                ->with(['fotoUtama', 'toko'])
                ->limit(8)
                ->get();
        } catch (\Exception $e) {
            $produkLaris = collect();
        }

        // UMKM terverifikasi
        try {
            $umkmFeatured = Toko::where('status', 'aktif')
                ->where('terverifikasi_dinas', true)
                ->inRandomOrder()
                ->limit(5)
                ->get();
        } catch (\Exception $e) {
            $umkmFeatured = collect();
        }

        return view('public.home.index', compact(
            'iklans',
            'produkLaris',
            'umkmFeatured'
        ));
    }
}