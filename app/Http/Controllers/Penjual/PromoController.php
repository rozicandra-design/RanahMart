<?php

namespace App\Http\Controllers\Penjual;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PromoController extends Controller
{
    public function index()
    {
        $toko = auth()->user()->toko;

        if (!$toko) {
            return redirect()->route('penjual.toko.edit')
                ->with('warning', 'Lengkapi profil toko terlebih dahulu.');
        }

        return view('penjual.promo.index', compact('toko'));
    }

    public function create()
    {
        $toko = auth()->user()->toko;
        return view('penjual.promo.create', compact('toko'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama'          => 'required|string|max:100',
            'kode'          => 'required|string|max:20|unique:promos,kode',
            'tipe'          => 'required|in:persen,nominal',
            'nilai'         => 'required|numeric|min:0',
            'min_belanja'   => 'nullable|numeric|min:0',
            'maks_diskon'   => 'nullable|numeric|min:0',
            'mulai'         => 'required|date',
            'selesai'       => 'required|date|after_or_equal:mulai',
            'kuota'         => 'nullable|integer|min:1',
        ]);

        // TODO: simpan ke tabel promos
        return redirect()->route('penjual.promo.index')
            ->with('success', 'Promo berhasil dibuat.');
    }

    public function destroy($id)
    {
        // TODO: hapus promo
        return back()->with('success', 'Promo berhasil dihapus.');
    }
}