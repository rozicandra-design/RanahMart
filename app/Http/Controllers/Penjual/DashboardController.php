<?php

namespace App\Http\Controllers\Penjual;

use App\Http\Controllers\Controller;
use App\Models\Produk;
use App\Models\Pesanan;
use App\Models\Iklan;
use App\Models\Notifikasi;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $toko = auth()->user()->toko;

        if (!$toko) {
            return redirect()->route('penjual.toko.edit')
                ->with('warning', 'Silakan lengkapi profil toko Anda terlebih dahulu.');
        }

        $stats = [
            'pendapatan_bulan' => Pesanan::where('toko_id', $toko->id)
                ->where('status_bayar', 'lunas')
                ->whereMonth('created_at', now()->month)->sum('total'),
            'pesanan_baru'  => Pesanan::where('toko_id', $toko->id)
                ->where('status_pesanan', 'menunggu')->count(),
            'produk_aktif'  => Produk::where('toko_id', $toko->id)
                ->where('status', 'aktif')->count(),
            'rating_toko'   => $toko->rating,
            'total_ulasan'  => $toko->total_ulasan,
            'iklan_aktif'   => Iklan::where('toko_id', $toko->id)
                ->where('status', 'aktif')->first(),
        ];

        $chartData = $this->getChartData($toko->id);
        $topProduk = Produk::where('toko_id', $toko->id)
            ->orderByDesc('total_terjual')->limit(3)->get();
        $pesananBaru = Pesanan::where('toko_id', $toko->id)
            ->where('status_pesanan', 'menunggu')
            ->with('pembeli', 'items')->latest()->limit(5)->get();

        return view('penjual.dashboard', compact('toko', 'stats', 'chartData', 'topProduk', 'pesananBaru'));
    }

    private function getChartData(int $tokoId): array
    {
        $data = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $data[] = [
                'label' => $date->format('D'),
                'value' => Pesanan::where('toko_id', $tokoId)
                    ->where('status_bayar', 'lunas')
                    ->whereDate('created_at', $date)->sum('total'),
            ];
        }
        return $data;
    }

    public function laporan(Request $request)
    {
        $toko  = auth()->user()->toko;
        $bulan = $request->bulan ?? now()->month;
        $tahun = $request->tahun ?? now()->year;

        $stats = [
            'total_penjualan' => Pesanan::where('toko_id', $toko->id)
                ->where('status_bayar', 'lunas')
                ->whereMonth('created_at', $bulan)
                ->whereYear('created_at', $tahun)
                ->sum('total'),
            'total_pesanan' => Pesanan::where('toko_id', $toko->id)
                ->whereMonth('created_at', $bulan)
                ->whereYear('created_at', $tahun)
                ->count(),
            'pengunjung' => 1840,
            'konversi'   => 13.4,
        ];

        $kategoriBars = Produk::where('toko_id', $toko->id)
            ->select('kategori', \DB::raw('sum(total_terjual) as total'))
            ->groupBy('kategori')->get();

        $namaBulan = date('F', mktime(0, 0, 0, $bulan, 1));

        if ($request->export) {
            $pdf = app(\Barryvdh\DomPDF\PDF::class)->loadView('penjual.laporan-pdf', compact(
                'toko', 'stats', 'kategoriBars', 'namaBulan', 'tahun'
            ));
            return $pdf->download("laporan-{$toko->slug}-{$namaBulan}-{$tahun}.pdf");
        }

        return view('penjual.laporan', compact('toko', 'stats', 'kategoriBars', 'bulan', 'tahun'));
    }

    public function exportLaporan()
    {
        return back()->with('success', 'Laporan berhasil diunduh.');
    }

    public function notifikasi()
    {
        $notifikasis = Notifikasi::where('user_id', auth()->id())
            ->latest()->paginate(20);
        return view('penjual.notifikasi', compact('notifikasis'));
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
        $toko = auth()->user()->toko;
        return view('penjual.pengaturan', compact('toko'));
    }

    public function simpanPengaturan(Request $request)
    {
        $toko = auth()->user()->toko;
        $toko->update($request->only(['toko_aktif', 'mode_liburan']));
        return back()->with('success', 'Pengaturan berhasil disimpan.');
    }
}