<?php
namespace App\Http\Controllers\Public;
use App\Http\Controllers\Controller;
use App\Models\Produk;
use Illuminate\Http\Request;

class ProdukController extends Controller
{
    public function index(Request $request)
    {
        $produks = Produk::aktif()
            ->when($request->q,        fn($q) => $q->where('nama','like',"%{$request->q}%"))
            ->when($request->kategori, fn($q) => $q->where('kategori', $request->kategori))
            ->when($request->min_harga,fn($q) => $q->where('harga','>=',$request->min_harga))
            ->when($request->max_harga,fn($q) => $q->where('harga','<=',$request->max_harga))
            ->when($request->sort === 'terlaris',  fn($q) => $q->orderByDesc('total_terjual'))
            ->when($request->sort === 'termurah',  fn($q) => $q->orderBy('harga'))
            ->when($request->sort === 'termahal',  fn($q) => $q->orderByDesc('harga'))
            ->when($request->sort === 'terbaru',   fn($q) => $q->latest())
            ->with('fotoUtama','toko')
            ->paginate(20);

        return view('public.produk.index', compact('produks'));
    }

    public function show(Produk $produk)
    {
        abort_if($produk->status !== 'aktif', 404);
        $produk->increment('total_dilihat');
        $produk->load('toko','fotos','ulasans.user');
        $produkTerkait = Produk::aktif()
            ->where('kategori', $produk->kategori)
            ->where('id','!=',$produk->id)
            ->limit(4)->with('fotoUtama')->get();
        return view('public.produk.show', compact('produk','produkTerkait'));
    }

    public function kategori(string $kategori)
    {
        $produks = Produk::aktif()->where('kategori', $kategori)
            ->with('fotoUtama','toko')->paginate(20);
        return view('public.produk.index', compact('produks','kategori'));
    }

    public function flashSale()
    {
        $produks = Produk::aktif()->whereNotNull('harga_coret')
            ->orderByDesc(\DB::raw('(harga_coret - harga) / harga_coret'))
            ->with('fotoUtama','toko')->paginate(20);
        return view('public.produk.index', compact('produks'));
    }
}
