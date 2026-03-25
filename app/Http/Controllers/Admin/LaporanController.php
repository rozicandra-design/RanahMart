<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaksi;
use App\Models\Iklan;
use App\Models\Toko;

class LaporanController extends Controller
{
    public function index()
    {
        $bulan = request('bulan', now()->month);
        $tahun = request('tahun', now()->year);

        $totalTransaksi = Transaksi::whereMonth('created_at', $bulan)
                            ->whereYear('created_at', $tahun)
                            ->sum('total') ?? 0;

        $totalPesanan = Transaksi::whereMonth('created_at', $bulan)
                            ->whereYear('created_at', $tahun)
                            ->count();

        $stats = [
            'total_transaksi'  => $totalTransaksi,
            'total_pesanan'    => $totalPesanan,
            'total_komisi'     => $totalTransaksi * 0.03,
            'pendapatan_iklan' => Iklan::whereMonth('created_at', $bulan)
                                    ->whereYear('created_at', $tahun)
                                    ->sum('biaya') ?? 0,
        ];

        $chartBulanan = collect(range(1, 12))->map(fn($m) => [
            'bulan' => date('M', mktime(0, 0, 0, $m, 1)),
            'nilai' => Transaksi::whereMonth('created_at', $m)
                        ->whereYear('created_at', $tahun)
                        ->sum('total') ?? 0,
        ])->toArray();

        $topUmkm = collect(); // isi nanti setelah relasi Toko-Transaksi siap

        return view('admin.laporan.index', compact('stats', 'chartBulanan', 'topUmkm'));
    }

    public function export()
{
    $bulan = request('bulan', now()->month);
    $tahun = request('tahun', now()->year);

    $totalTransaksi = Transaksi::whereMonth('created_at', $bulan)
                        ->whereYear('created_at', $tahun)
                        ->sum('total') ?? 0;

    $totalPesanan = Transaksi::whereMonth('created_at', $bulan)
                        ->whereYear('created_at', $tahun)
                        ->count();

    $stats = [
        'total_transaksi'  => $totalTransaksi,
        'total_pesanan'    => $totalPesanan,
        'total_komisi'     => $totalTransaksi * 0.03,
        'pendapatan_iklan' => Iklan::whereMonth('created_at', $bulan)
                                ->whereYear('created_at', $tahun)
                                ->sum('biaya') ?? 0,
    ];

    $namaBulan = date('F', mktime(0, 0, 0, $bulan, 1));

    $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.laporan.pdf', compact(
        'stats', 'namaBulan', 'tahun'
    ));

    return $pdf->download("laporan-{$namaBulan}-{$tahun}.pdf");
}
}