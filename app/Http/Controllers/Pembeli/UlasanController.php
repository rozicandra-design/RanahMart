<?php

namespace App\Http\Controllers\Pembeli;

use App\Http\Controllers\Controller;
use App\Models\Ulasan;
use App\Models\ItemPesanan;
use Illuminate\Http\Request;

class UlasanController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // Produk yang sudah selesai tapi belum diulas
        $belumDiulas = ItemPesanan::whereHas('pesanan', function ($q) use ($user) {
                            $q->where('user_id', $user->id)
                              ->where('status_pesanan', 'selesai');
                        })
                        ->whereDoesntHave('ulasan')
                        ->with('pesanan')
                        ->get();

        // Ulasan yang sudah dikirim
        $ulasanSaya = Ulasan::where('user_id', $user->id)
                        ->with(['produk', 'produk.toko'])
                        ->latest()
                        ->get();

        return view('pembeli.ulasan', compact('belumDiulas', 'ulasanSaya'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'item_pesanan_id' => 'required|exists:item_pesanans,id',
            'rating'          => 'required|integer|min:1|max:5',
            'komentar'        => 'nullable|string|max:1000',
            'foto_ulasan'     => 'nullable|array|max:3',
            'foto_ulasan.*'   => 'image|max:2048',
        ]);

        $item = ItemPesanan::findOrFail($request->item_pesanan_id);

        // Pastikan item milik user yang login
        abort_if($item->pesanan->user_id !== auth()->id(), 403);

        // Cegah ulasan duplikat
        if ($item->ulasan) {
            return back()->with('error', 'Produk ini sudah diulas.');
        }

        Ulasan::create([
            'produk_id'  => $item->produk_id,
            'user_id'    => auth()->id(),
            'pesanan_id' => $item->pesanan_id,
            'rating'     => $request->rating,
            'komentar'   => $request->komentar,
        ]);

        return back()->with('success', 'Ulasan berhasil dikirim! +10 Poin ditambahkan.');
    }
}