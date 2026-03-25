<?php

namespace App\Http\Controllers\Pembeli;

use App\Http\Controllers\Controller;
use App\Models\Wishlist;

class WishlistController extends Controller
{
    public function index()
    {
        $wishlists = Wishlist::where('user_id', auth()->id())
                        ->with(['produk.toko', 'produk.fotoUtama'])
                        ->latest()
                        ->get();

        return view('pembeli.wishlist', compact('wishlists'));
    }

    public function toggle($produkId)
    {
        $wishlist = Wishlist::where('user_id', auth()->id())
                        ->where('produk_id', $produkId)
                        ->first();

        if ($wishlist) {
            $wishlist->delete();
            return back()->with('success', 'Produk dihapus dari wishlist.');
        }

        Wishlist::create([
            'user_id'   => auth()->id(),
            'produk_id' => $produkId,
        ]);

        return back()->with('success', 'Produk ditambahkan ke wishlist.');
    }
}