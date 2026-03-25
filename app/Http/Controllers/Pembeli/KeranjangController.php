<?php

namespace App\Http\Controllers\Pembeli;

use App\Http\Controllers\Controller;
use App\Models\Keranjang;
use Illuminate\Http\Request;

class KeranjangController extends Controller
{
    public function index()
    {
        $keranjangs = Keranjang::where('user_id', auth()->id())
                        ->with(['produk.toko', 'produk.fotoUtama'])
                        ->get()
                        ->groupBy('produk.toko_id');

        return view('pembeli.keranjang', compact('keranjangs'));
    }

    public function tambah(Request $request)
    {
        $request->validate([
            'produk_id' => 'required|exists:produks,id',
            'jumlah'    => 'required|integer|min:1',
        ]);

        $item = Keranjang::where('user_id', auth()->id())
                    ->where('produk_id', $request->produk_id)
                    ->first();

        if ($item) {
            $item->increment('jumlah', $request->jumlah);
        } else {
            Keranjang::create([
                'user_id'   => auth()->id(),
                'produk_id' => $request->produk_id,
                'jumlah'    => $request->jumlah,
            ]);
        }

        return back()->with('success', 'Produk ditambahkan ke keranjang.');
    }

    public function updateQty(Request $request, Keranjang $item)
    {
        abort_if($item->user_id !== auth()->id(), 403);

        $request->validate(['jumlah' => 'required|integer|min:1']);

        $item->update(['jumlah' => $request->jumlah]);

        return back();
    }

    public function hapus(Keranjang $item)
    {
        abort_if($item->user_id !== auth()->id(), 403);

        $item->delete();

        return back()->with('success', 'Item dihapus dari keranjang.');
    }

    public function kosongkan()
    {
        Keranjang::where('user_id', auth()->id())->delete();

        return back()->with('success', 'Keranjang dikosongkan.');
    }
}