<?php

namespace App\Http\Controllers\Pembeli;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ReturController extends Controller
{
    public function index()
    {
        return view('pembeli.retur');
    }

    public function create($pesanan_id)
    {
        return view('pembeli.retur', compact('pesanan_id'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'pesanan_id' => 'required|exists:pesanans,id',
            'alasan'     => 'required|string|max:500',
            'foto'       => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // TODO: simpan ke tabel returs
        return redirect()->route('pembeli.retur.index')
            ->with('success', 'Permintaan retur berhasil dikirim.');
    }
}