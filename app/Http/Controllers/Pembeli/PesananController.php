<?php

namespace App\Http\Controllers\Pembeli;

use App\Http\Controllers\Controller;
use App\Models\Pesanan;
use Illuminate\Http\Request;

class PesananController extends Controller
{
    public function index(Request $request)
    {
        $query = Pesanan::where('user_id', auth()->id())
                    ->with(['toko', 'items'])
                    ->latest();

        if ($request->status) {
            $query->where('status_pesanan', $request->status);
        }

        $pesanans = $query->paginate(10);

        return view('pembeli.pesanan.index', compact('pesanans'));
    }

    public function show(Pesanan $pesanan)
    {
        // Pastikan pesanan milik user yang login
        abort_if($pesanan->user_id !== auth()->id(), 403);

        $pesanan->load(['toko', 'items']);

        return view('pembeli.pesanan.show', compact('pesanan'));
    }

    public function konfirmasiTerima(Pesanan $pesanan)
    {
        abort_if($pesanan->user_id !== auth()->id(), 403);

        $pesanan->update(['status_pesanan' => 'selesai']);

        return back()->with('success', 'Pesanan dikonfirmasi selesai.');
    }

    public function batalkan(Pesanan $pesanan)
    {
        abort_if($pesanan->user_id !== auth()->id(), 403);

        $pesanan->update(['status_pesanan' => 'dibatalkan']);

        return back()->with('success', 'Pesanan dibatalkan.');
    }

    public function checkout(Request $request)
    {
        // Isi nanti setelah flow checkout siap
        return back()->with('info', 'Fitur checkout segera hadir.');
    }
}