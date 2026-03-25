<?php

namespace App\Http\Controllers\Dinas;

use App\Http\Controllers\Controller;
use App\Models\Toko;

class LaporanController extends Controller
{
    public function index()
    {
        $bulan = request('bulan', now()->month);
        $tahun = request('tahun', date('Y'));

        $stats = [
            'diverifikasi'      => Toko::where('terverifikasi_dinas', true)
                                    ->whereMonth('updated_at', $bulan)
                                    ->whereYear('updated_at', $tahun)
                                    ->count(),
            'ditolak'           => Toko::where('status', 'ditolak')
                                    ->whereMonth('updated_at', $bulan)
                                    ->whereYear('updated_at', $tahun)
                                    ->count(),
            'peserta_pembinaan' => 0,
            'kunjungan'         => 0,
        ];

        $rekapKecamatan = Toko::where('status', 'aktif')
            ->selectRaw('kecamatan, count(*) as total, sum(terverifikasi_dinas) as terverifikasi')
            ->whereNotNull('kecamatan')
            ->groupBy('kecamatan')
            ->orderByDesc('total')
            ->get();

        return view('dinas.laporan', compact('stats', 'rekapKecamatan'));
    }

    public function export()
{
    $bulan = request('bulan', now()->month);
    $tahun = request('tahun', date('Y'));

    $stats = [
        'diverifikasi'      => Toko::where('terverifikasi_dinas', true)
                                ->whereMonth('updated_at', $bulan)
                                ->whereYear('updated_at', $tahun)
                                ->count(),
        'ditolak'           => Toko::where('status', 'ditolak')
                                ->whereMonth('updated_at', $bulan)
                                ->whereYear('updated_at', $tahun)
                                ->count(),
        'peserta_pembinaan' => 0,
        'kunjungan'         => 0,
    ];

    $rekapKecamatan = Toko::where('status', 'aktif')
        ->selectRaw('kecamatan, count(*) as total, sum(terverifikasi_dinas) as terverifikasi')
        ->whereNotNull('kecamatan')
        ->groupBy('kecamatan')
        ->orderByDesc('total')
        ->get();

    $namaBulan = date('F', mktime(0, 0, 0, $bulan, 1));

    $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('dinas.exports.laporan-pdf', compact(
        'stats', 'rekapKecamatan', 'bulan', 'tahun', 'namaBulan'
    ))->setPaper('a4', 'portrait');

    return $pdf->download("laporan-dinas-{$namaBulan}-{$tahun}.pdf");
}
}