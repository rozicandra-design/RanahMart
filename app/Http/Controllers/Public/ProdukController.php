<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Produk;

class ProdukController extends Controller
{
    public function index()
    {
        $produks = Produk::latest()->paginate(12);
        return view('public.produk.index', compact('produks'));
    }

    public function kategori($kategori)
    {
        $produks = Produk::where('kategori', $kategori)->latest()->paginate(12);
        $label = config('ranahmart.kategori_umkm')[$kategori] ?? $kategori;
        return view('public.produk.kategori', compact('produks', 'kategori', 'label'));
    }

    public function show(Produk $produk)
    {
        return view('public.produk.show', compact('produk'));
    }
}