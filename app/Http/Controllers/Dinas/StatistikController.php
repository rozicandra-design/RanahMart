<?php

namespace App\Http\Controllers\Dinas;

use App\Http\Controllers\Controller;
use App\Models\Toko;

class StatistikController extends Controller
{
    public function index()
    {
        $stats = [
            'total_aktif'        => Toko::where('status', 'aktif')->count(),
            'baru_tahun_ini'     => Toko::where('status', 'aktif')->whereYear('created_at', now()->year)->count(),
            'tenaga_kerja'       => 0,
            'kontribusi_ekonomi' => 0,
        ];

        $sebaranKecamatan = Toko::where('status', 'aktif')
            ->selectRaw('kecamatan, count(*) as total')
            ->whereNotNull('kecamatan')
            ->groupBy('kecamatan')
            ->orderByDesc('total')
            ->get();

        return view('dinas.statistik', compact('stats', 'sebaranKecamatan'));
    }

    public function export()
{
    $sebaranKecamatan = Toko::where('status', 'aktif')
        ->selectRaw('kecamatan, count(*) as total')
        ->whereNotNull('kecamatan')
        ->groupBy('kecamatan')
        ->orderByDesc('total')
        ->get();

    $kategoriStats = Toko::where('status', 'aktif')
        ->selectRaw('kategori, count(*) as total')
        ->groupBy('kategori')
        ->orderByDesc('total')
        ->get();

    $stats = [
        'total_aktif'    => Toko::where('status', 'aktif')->count(),
        'baru_tahun_ini' => Toko::where('status', 'aktif')->whereYear('created_at', now()->year)->count(),
    ];

    $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('dinas.exports.statistik-pdf', compact(
        'sebaranKecamatan', 'kategoriStats', 'stats'
    ));

    return $pdf->download('statistik-umkm-' . now()->format('Y-m-d') . '.pdf');
}
}