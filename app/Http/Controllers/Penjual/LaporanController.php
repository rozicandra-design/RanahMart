<?php

namespace App\Http\Controllers\Penjual;

use App\Http\Controllers\Controller;
use App\Models\Pesanan;
use App\Models\ItemPesanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $toko  = auth()->user()->toko;
        $bulan = (int) $request->get('bulan', now()->month);
        $tahun = (int) $request->get('tahun', date('Y'));

        if (!$toko) {
            return redirect()->route('penjual.toko.edit');
        }

        $baseQuery = fn() => Pesanan::where('toko_id', $toko->id)
            ->whereMonth('created_at', $bulan)
            ->whereYear('created_at', $tahun);

        $stats = [
            'total_penjualan' => (clone $baseQuery())->where('status_bayar', 'lunas')->sum('total'),
            'total_pesanan'   => (clone $baseQuery())->count(),
            'pengunjung'      => 0,
            'konversi'        => 0,
        ];

        $penjualanHarian = (clone $baseQuery())
            ->where('status_bayar', 'lunas')
            ->select(DB::raw('DAY(created_at) as hari'), DB::raw('SUM(total) as total'), DB::raw('COUNT(*) as jumlah'))
            ->groupBy('hari')->orderBy('hari')->get()->keyBy('hari');

        $hariDalamBulan = cal_days_in_month(CAL_GREGORIAN, $bulan, $tahun);
        $chartHari = $chartPenjualan = $chartPesanan = [];
        for ($d = 1; $d <= $hariDalamBulan; $d++) {
            $chartHari[]      = $d;
            $chartPenjualan[] = (float) ($penjualanHarian->get($d)?->total ?? 0);
            $chartPesanan[]   = (int)   ($penjualanHarian->get($d)?->jumlah ?? 0);
        }

        $topProduk = collect();
        try {
            $topProduk = ItemPesanan::whereHas('pesanan', fn($q) => $q
                    ->where('toko_id', $toko->id)
                    ->where('status_bayar', 'lunas')
                    ->whereMonth('created_at', $bulan)
                    ->whereYear('created_at', $tahun))
                ->select('produk_id', DB::raw('SUM(qty) as total_qty'), DB::raw('SUM(subtotal) as total_omzet'))
                ->with('produk:id,nama')
                ->groupBy('produk_id')->orderByDesc('total_omzet')->limit(5)->get();
        } catch (\Exception $e) {}

        $statusPesanan = (clone $baseQuery())
            ->select('status_pesanan as status', DB::raw('COUNT(*) as total'))
            ->groupBy('status_pesanan')->get()->keyBy('status');

        $enamBulan = collect();
        for ($i = 5; $i >= 0; $i--) {
            $tgl = now()->subMonths($i);
            $enamBulan->push([
                'label' => $tgl->format('M Y'),
                'total' => (float) Pesanan::where('toko_id', $toko->id)
                    ->where('status_bayar', 'lunas')
                    ->whereMonth('created_at', $tgl->month)
                    ->whereYear('created_at', $tgl->year)->sum('total'),
            ]);
        }

        $kategoriBars = collect();
        try {
            $kategoriBars = DB::table('produks')
                ->join('item_pesanans', 'produks.id', '=', 'item_pesanans.produk_id')
                ->join('pesanans', 'item_pesanans.pesanan_id', '=', 'pesanans.id')
                ->where('pesanans.toko_id', $toko->id)
                ->where('pesanans.status_bayar', 'lunas')
                ->whereMonth('pesanans.created_at', $bulan)
                ->whereYear('pesanans.created_at', $tahun)
                ->whereNull('produks.deleted_at')
                ->select('produks.kategori', DB::raw('SUM(item_pesanans.qty) as total'))
                ->groupBy('produks.kategori')->orderByDesc('total')->get();
        } catch (\Exception $e) {}

        return view('penjual.laporan', compact(
            'stats', 'chartHari', 'chartPenjualan', 'chartPesanan',
            'topProduk', 'statusPesanan', 'enamBulan', 'kategoriBars', 'bulan', 'tahun'
        ));
    }

    public function cetak(Request $request)
    {
        $toko  = auth()->user()->toko;
        $bulan = (int) $request->get('bulan', now()->month);
        $tahun = (int) $request->get('tahun', date('Y'));

        if (!$toko) return redirect()->route('penjual.toko.edit');

        $namaBulan = \Carbon\Carbon::create()->month($bulan)->translatedFormat('F');

        $baseQuery = fn() => Pesanan::where('toko_id', $toko->id)
            ->whereMonth('created_at', $bulan)
            ->whereYear('created_at', $tahun);

        $stats = [
            'total_penjualan' => (clone $baseQuery())->where('status_bayar', 'lunas')->sum('total'),
            'total_pesanan'   => (clone $baseQuery())->count(),
            'pengunjung'      => 0,
            'konversi'        => 0,
        ];

        $kategoriBars = collect();
        try {
            $kategoriBars = DB::table('produks')
                ->join('item_pesanans', 'produks.id', '=', 'item_pesanans.produk_id')
                ->join('pesanans', 'item_pesanans.pesanan_id', '=', 'pesanans.id')
                ->where('pesanans.toko_id', $toko->id)
                ->where('pesanans.status_bayar', 'lunas')
                ->whereMonth('pesanans.created_at', $bulan)
                ->whereYear('pesanans.created_at', $tahun)
                ->whereNull('produks.deleted_at')
                ->select('produks.kategori', DB::raw('SUM(item_pesanans.qty) as total'))
                ->groupBy('produks.kategori')->orderByDesc('total')->get();
        } catch (\Exception $e) {}

        $pdf = Pdf::loadView('penjual.laporan-pdf', compact(
            'toko', 'stats', 'kategoriBars', 'bulan', 'tahun', 'namaBulan'
        ))->setPaper('a4', 'portrait');

        $namaFile = 'laporan-' . $bulan . '-' . $tahun . '.pdf';

        return $pdf->download($namaFile);
    }

    public function export(Request $request)
    {
        return $this->cetak($request);
    }
}