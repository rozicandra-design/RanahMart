<?php
namespace App\Http\Controllers\Public;
use App\Http\Controllers\Controller;
use App\Models\Produk;
use App\Models\Toko;
use App\Models\Iklan;
use App\Services\IklanService;

class HomeController extends Controller
{
    public function __construct(private IklanService $iklanService) {}

    public function index()
    {
        $iklans       = $this->iklanService->iklanAktifUntukBanner();
        $produkLaris  = Produk::aktif()->orderByDesc('total_terjual')->limit(8)->with('fotoUtama','toko')->get();
        $umkmFeatured = Toko::aktif()->where('terverifikasi_dinas', true)->inRandomOrder()->limit(5)->get();
        $kategoriStats= Produk::aktif()->select('kategori',\DB::raw('count(*) as total'))->groupBy('kategori')->get();

        return view('public.home.index', compact('iklans','produkLaris','umkmFeatured','kategoriStats'));
    }
}
