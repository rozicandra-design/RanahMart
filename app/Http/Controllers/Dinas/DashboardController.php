<?php

namespace App\Http\Controllers\Dinas;

use App\Http\Controllers\Controller;
use App\Models\Toko;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'umkm_terverifikasi'  => Toko::where('terverifikasi_dinas', true)->count(),
            'menunggu_verifikasi' => Toko::where('status', 'menunggu_dinas')->count(),
            'total_omzet'         => 0,
            'umkm_binaan'         => 0,
        ];

        $sebaranKecamatan = Toko::selectRaw('kecamatan, count(*) as total')
            ->whereNotNull('kecamatan')
            ->groupBy('kecamatan')
            ->orderByDesc('total')
            ->get();

        $antrian = Toko::where('status', 'menunggu_dinas')
            ->latest()
            ->take(5)
            ->get();

        return view('dinas.dashboard', compact(
            'stats',
            'sebaranKecamatan',
            'antrian'
        ));
    }
            public function notifikasi()
        {
            $notifikasis = \App\Models\Notifikasi::where('user_id', auth()->id())
                ->latest()
                ->paginate(20);

            return view('dinas.notifikasi', compact('notifikasis'));
        }

public function pengaturan()
{
    return view('dinas.pengaturan');
}

public function simpanPengaturan(\Illuminate\Http\Request $request)
{
    return back()->with('success', 'Pengaturan disimpan.');
}
}