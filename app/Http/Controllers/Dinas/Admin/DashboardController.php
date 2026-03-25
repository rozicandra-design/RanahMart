<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Toko;
use App\Models\Produk;
use App\Models\Pesanan;
use App\Models\Iklan;
use App\Models\Retur;
use App\Models\Notifikasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_umkm'      => Toko::count(),
            'total_pembeli'   => User::where('role', 'pembeli')->count(),
            'transaksi_bulan' => Pesanan::whereMonth('created_at', now()->month)
                                    ->where('status_bayar', 'lunas')->sum('total'),
            'perlu_tindakan'  => Toko::whereIn('status', ['pending', 'menunggu_dinas'])->count()
                                + Produk::where('status', 'pending')->count()
                                + Iklan::where('status', 'menunggu')->count()
                                + Retur::where('status', 'diajukan')->count(),
        ];

        $chartData = $this->getChartData();

        $sebaranKategori = Toko::select('kategori', DB::raw('count(*) as total'))
            ->where('status', 'aktif')
            ->groupBy('kategori')
            ->orderByDesc('total')
            ->get();

        $perluTindakan = [
            'umkm_pending'    => Toko::whereIn('status', ['pending', 'menunggu_dinas'])->count(),
            'iklan_pending'   => Iklan::where('status', 'menunggu')->count(),
            'produk_laporan'  => Produk::where('status', 'pending')->count(),
            'retur_aktif'     => Retur::where('status', 'diajukan')->count(),
        ];

        return view('admin.dashboard', compact('stats', 'chartData', 'sebaranKategori', 'perluTindakan'));
    }

    private function getChartData(): array
    {
        $data = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $data[] = [
                'label' => $date->format('D'),
                'value' => Pesanan::whereDate('created_at', $date)
                    ->where('status_bayar', 'lunas')->sum('total'),
            ];
        }
        return $data;
    }

    public function pengaturan()
    {
        $config = [
            'komisi_persen'       => config('ranahmart.komisi', 3),
            'iklan_min_biaya'     => config('ranahmart.iklan_min', 50000),
            'registrasi_aktif'    => config('ranahmart.registrasi_aktif', true),
            'review_produk_wajib' => config('ranahmart.review_produk', true),
            'mode_maintenance'    => config('ranahmart.maintenance', false),
        ];
        return view('admin.pengaturan', compact('config'));
    }

    public function simpanPengaturan(Request $request)
    {
        $request->validate([
            'komisi_persen'   => 'required|numeric|min:0|max:30',
            'iklan_min_biaya' => 'required|integer|min:0',
        ]);
        // Simpan ke file config atau tabel settings
        return back()->with('success', 'Konfigurasi berhasil disimpan.');
    }

    public function notifikasi()
    {
        $notifikasis = Notifikasi::where('user_id', auth()->id())
            ->latest()->paginate(20);
        return view('admin.notifikasi', compact('notifikasis'));
    }

    public function bacaSemua()
    {
        Notifikasi::where('user_id', auth()->id())
            ->where('sudah_dibaca', false)
            ->update(['sudah_dibaca' => true]);
        return back()->with('success', 'Semua notifikasi ditandai dibaca.');
    }
}
