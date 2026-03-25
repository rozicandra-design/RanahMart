<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Produk;
use Illuminate\Http\Request;

class ProdukController extends Controller
{
    public function index()
    {
        $produks      = Produk::latest()->paginate(20);
        $pendingCount = Produk::where('status', 'pending')->count();
        $aktifCount   = Produk::where('status', 'aktif')->count();
        $totalCount   = Produk::count();

        return view('admin.produk.index', compact(
            'produks',
            'pendingCount',
            'aktifCount',
            'totalCount'
        ));
    }

    public function show(Produk $produk)
    {
        return view('admin.produk.show', compact('produk'));
    }

    public function setujui(Produk $produk)
    {
        $produk->update(['status' => 'aktif']);
        return back()->with('success', 'Produk disetujui.');
    }

    public function tolak(Produk $produk)
    {
        $produk->update(['status' => 'ditolak']);
        return back()->with('success', 'Produk ditolak.');
    }

    public function turunkan(Produk $produk)
    {
        $produk->update(['status' => 'nonaktif']);
        return back()->with('success', 'Produk diturunkan.');
    }

    public function peringatkan(Produk $produk)
    {
        return back()->with('success', 'Peringatan dikirim.');
    }

    public function destroy(Produk $produk)
    {
        $produk->delete();
        return redirect()->route('admin.produk.index')->with('success', 'Produk dihapus.');
    }
}