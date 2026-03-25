<?php

namespace App\Http\Controllers\Pembeli;

use App\Http\Controllers\Controller;
use App\Models\Pesanan;
use App\Models\Notifikasi;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Mengambil data statistik untuk dashboard
        $stats = [
            'pesanan_aktif'   => Pesanan::where('user_id', $user->id)
                                    ->whereIn('status_pesanan', ['menunggu', 'diproses', 'dikirim'])
                                    ->count(),
            'pesanan_selesai' => Pesanan::where('user_id', $user->id)
                                    ->where('status_pesanan', 'selesai')
                                    ->count(),
            'total_belanja'   => Pesanan::where('user_id', $user->id)
                                    ->where('status_bayar', 'lunas')
                                    ->sum('total'),
            'total_poin'      => $user->poins()->sum('jumlah') ?? 0,
            'voucher_aktif'   => 0, // Ganti dengan logic voucher jika sudah ada modelnya
        ];

        // Mengambil 5 pesanan terbaru (Pastikan nama variabel ini sesuai dengan di Blade)
        $pesananTerkini = Pesanan::where('user_id', $user->id)
                            ->with('toko')
                            ->latest()
                            ->limit(5)
                            ->get();

        // Mengambil rekomendasi produk secara acak
        $rekomendasi = Produk::inRandomOrder()->limit(4)->get();

        return view('pembeli.dashboard', compact('stats', 'pesananTerkini', 'rekomendasi'));
    }

    public function notifikasi()
    {
        $notifikasis = Notifikasi::where('user_id', auth()->id())
                        ->latest()
                        ->paginate(20);

        return view('pembeli.notifikasi', compact('notifikasis'));
    }

    public function bacaSemua()
    {
        Notifikasi::where('user_id', auth()->id())
            ->where('sudah_dibaca', false)
            ->update(['sudah_dibaca' => true]);

        return back()->with('success', 'Semua notifikasi ditandai sudah dibaca.');
    }

    public function pengaturan()
    {
        return view('pembeli.pengaturan', ['user' => auth()->user()]);
    }

    public function simpanPengaturan(Request $request)
    {
        auth()->user()->update([
            'notif_pesanan_dikonfirmasi' => $request->boolean('notif_pesanan_dikonfirmasi'),
            'notif_pesanan_dikirim'      => $request->boolean('notif_pesanan_dikirim'),
            'notif_pesanan_selesai'      => $request->boolean('notif_pesanan_selesai'),
            'notif_promo'                => $request->boolean('notif_promo'),
            'notif_flash_sale'           => $request->boolean('notif_flash_sale'),
            'privasi_tampilkan_nama'     => $request->boolean('privasi_tampilkan_nama'),
        ]);

        return back()->with('success', 'Pengaturan berhasil disimpan.');
    }
}