<?php

namespace App\Http\Controllers\Penjual;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class IklanController extends Controller
{
    public function index()
    {
        $toko = auth()->user()->toko;

        if (!$toko) {
            return redirect()->route('penjual.toko.edit')
                ->with('warning', 'Lengkapi profil toko terlebih dahulu.');
        }

        return view('penjual.iklan.index', compact('toko'));
    }

    public function create()
    {
        $toko = auth()->user()->toko;
        return view('penjual.iklan.create', compact('toko'));
    }

    public function store(Request $request)
    {
        // TODO: implementasi simpan iklan
        return redirect()->route('penjual.iklan.index')
            ->with('success', 'Iklan berhasil dipasang.');
    }

    public function destroy($id)
    {
        // TODO: implementasi hapus iklan
        return back()->with('success', 'Iklan berhasil dihapus.');
    }
}