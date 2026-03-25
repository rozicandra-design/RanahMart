<?php
namespace App\Http\Controllers\Penjual;

use App\Http\Controllers\Controller;
use App\Models\Pesanan;
use Illuminate\Http\Request;

class PesananController extends Controller
{
    public function index(Request $request)
    {
        $toko = auth()->user()->toko;

        if (!$toko) {
            return redirect()->route('penjual.toko.edit')
                ->with('warning', 'Lengkapi profil toko terlebih dahulu.');
        }

        $status = $request->status ?? 'semua';

        $query = Pesanan::where('toko_id', $toko->id)
            ->with(['pembeli', 'items'])
            ->latest();

        if ($status !== 'semua') {
            $query->where('status_pesanan', $status);
        }

        $pesanans = $query->paginate(15)->withQueryString();

        $counts = [
            'semua'      => Pesanan::where('toko_id', $toko->id)->count(),
            'menunggu'   => Pesanan::where('toko_id', $toko->id)->where('status_pesanan', 'menunggu')->count(),
            'diproses'   => Pesanan::where('toko_id', $toko->id)->where('status_pesanan', 'diproses')->count(),
            'dikirim'    => Pesanan::where('toko_id', $toko->id)->where('status_pesanan', 'dikirim')->count(),
            'selesai'    => Pesanan::where('toko_id', $toko->id)->where('status_pesanan', 'selesai')->count(),
            'dibatalkan' => Pesanan::where('toko_id', $toko->id)->where('status_pesanan', 'dibatalkan')->count(),
        ];

        return view('penjual.pesanan.index', compact('pesanans', 'status', 'counts', 'toko'));
    }

    public function show(Pesanan $pesanan)
    {
        $this->authorizeToko($pesanan);
        $pesanan->load(['pembeli', 'items.produk']);
        return view('penjual.pesanan.show', compact('pesanan'));
    }

    public function proses(Pesanan $pesanan)
    {
        $this->authorizeToko($pesanan);
        $pesanan->update(['status_pesanan' => 'diproses']);
        return back()->with('success', 'Pesanan sedang diproses.');
    }

    public function kirim(Request $request, Pesanan $pesanan)
    {
        $this->authorizeToko($pesanan);
        $request->validate(['no_resi' => 'required|string']);
        $pesanan->update([
            'status_pesanan' => 'dikirim',
            'no_resi'        => $request->no_resi,
            'dikirim_at'     => now(),
        ]);
        return back()->with('success', 'Pesanan telah dikirim.');
    }

    public function tolak(Request $request, Pesanan $pesanan)
    {
        $this->authorizeToko($pesanan);
        $request->validate(['alasan' => 'required|string']);
        $pesanan->update([
            'status_pesanan' => 'dibatalkan',
            'catatan'        => $request->alasan,
        ]);
        return back()->with('success', 'Pesanan ditolak.');
    }

    private function authorizeToko(Pesanan $pesanan): void
    {
        abort_if($pesanan->toko_id !== auth()->user()->toko?->id, 403);
    }
}