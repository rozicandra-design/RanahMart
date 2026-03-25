<?php

namespace App\Http\Controllers\Penjual;

use App\Http\Controllers\Controller;
use App\Models\Ulasan;
use Illuminate\Http\Request;

class UlasanController extends Controller
{
    public function index(Request $request)
    {
        $toko = auth()->user()->toko;

        if (!$toko) {
            return redirect()->route('penjual.toko.edit')
                ->with('warning', 'Lengkapi profil toko terlebih dahulu.');
        }

        $query = Ulasan::whereHas('produk', function ($q) use ($toko) {
            $q->where('toko_id', $toko->id);
        })->with(['produk', 'pembeli'])->latest();

        if ($request->rating) {
            $query->where('rating', $request->rating);
        }

        if ($request->dibalas) {
            $query->where('dibalas', $request->dibalas === 'ya');
        }

        $ulasans = $query->paginate(15)->withQueryString();

        $rata = $ulasans->avg('rating') ?? 0;

        return view('penjual.ulasan.index', compact('ulasans', 'toko', 'rata'));
    }

    public function balas(Request $request, $id)
    {
        $data = $request->validate([
            'balasan' => 'required|string|max:500',
        ]);

        $ulasan = Ulasan::findOrFail($id);

        // Pastikan ulasan milik toko ini
        abort_if(
            $ulasan->produk->toko_id !== auth()->user()->toko?->id,
            403
        );

        $ulasan->update([
            'balasan'    => $data['balasan'],
            'dibalas'    => true,
            'dibalas_at' => now(),
        ]);

        return back()->with('success', 'Balasan berhasil dikirim.');
    }
}