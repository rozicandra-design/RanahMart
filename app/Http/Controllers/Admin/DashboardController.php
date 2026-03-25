<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Notifikasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_umkm'       => User::where('role', 'penjual')->count(),
            'total_pembeli'    => User::where('role', 'pembeli')->count(),
            'transaksi_bulan'  => 0,
            'perlu_tindakan'   => 0,
        ];

        $chartData = collect(range(6, 0))->map(fn($i) => [
            'label' => now()->subDays($i)->format('d/m'),
            'value' => 0,
        ])->toArray();

        $sebaranKategori = collect([]);

        $perluTindakan = [
            'umkm_pending'   => 0,
            'iklan_pending'  => 0,
            'produk_laporan' => 0,
            'retur_aktif'    => 0,
        ];

        $stats['perlu_tindakan'] = array_sum($perluTindakan);

        return view('admin.dashboard.index', compact(
            'stats',
            'chartData',
            'sebaranKategori',
            'perluTindakan'
        ));
    }

    public function notifikasi()
    {
        $notifikasis = Notifikasi::where('user_id', auth()->id())
                        ->latest()
                        ->paginate(20);

        return view('admin.notifikasi', compact('notifikasis'));
    }

    public function pengaturan()
    {
        // Ambil dari cache/storage, fallback ke default jika belum ada
        $config = Cache::get('admin_config', [
            'registrasi_aktif'    => true,
            'review_produk_wajib' => true,
            'mode_maintenance'    => false,
            'komisi_persen'       => 3,
            'iklan_min_biaya'     => 50000,
        ]);

        return view('admin.pengaturan', compact('config'));
    }

    public function simpanPengaturan(Request $request)
    {
        $request->validate([
            'komisi_persen'  => 'required|numeric|min:0|max:30',
            'iklan_min_biaya'=> 'required|integer|min:0',
        ]);

        // Ambil config lama agar toggle yang tidak terkirim tetap tersimpan
        $lama = Cache::get('admin_config', []);

        $config = array_merge($lama, [
            // Toggle (checkbox): jika tidak ada di request berarti false
            'registrasi_aktif'    => $request->boolean('registrasi_aktif'),
            'review_produk_wajib' => $request->boolean('review_produk_wajib'),
            'mode_maintenance'    => $request->boolean('mode_maintenance'),
            // Input biasa
            'komisi_persen'       => (float) $request->komisi_persen,
            'iklan_min_biaya'     => (int) $request->iklan_min_biaya,
        ]);

        // Simpan ke cache permanen (tanpa expiry)
        Cache::forever('admin_config', $config);

        return back()->with('success', 'Pengaturan berhasil disimpan.');
    }

    public function bacaSemua()
    {
        Notifikasi::where('user_id', auth()->id())
            ->where('sudah_dibaca', false)
            ->update(['sudah_dibaca' => true]);

        return back();
    }
}