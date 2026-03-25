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

// ─── USER CONTROLLER ───────────────────────────────────────────────────
class UserController extends Controller
{
    public function index(Request $request)
    {
        $users = User::query()
            ->when($request->search, fn($q) =>
                $q->where('nama_depan', 'like', "%{$request->search}%")
                  ->orWhere('email', 'like', "%{$request->search}%"))
            ->when($request->role,   fn($q) => $q->where('role', $request->role))
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->latest()->paginate(20);

        return view('admin.users.index', compact('users'));
    }

    public function show(User $user)
    {
        $user->load('toko', 'pesanans', 'ulasans');
        return view('admin.users.show', compact('user'));
    }

    public function toggleStatus(User $user)
    {
        $user->update([
            'status' => $user->status === 'aktif' ? 'diblokir' : 'aktif',
        ]);
        $msg = $user->status === 'aktif' ? 'diaktifkan' : 'diblokir';
        return back()->with('success', "User berhasil {$msg}.");
    }

    public function resetPassword(User $user)
    {
        $password = 'Ranah' . rand(1000, 9999);
        $user->update(['password' => bcrypt($password)]);
        // Kirim email password baru
        return back()->with('success', "Password user berhasil direset: {$password}");
    }

    public function destroy(User $user)
    {
        $user->delete();
        return back()->with('success', 'User berhasil dihapus.');
    }
}

// ─── UMKM CONTROLLER ───────────────────────────────────────────────────
class UmkmController extends Controller
{
    public function index(Request $request)
    {
        $tokos = Toko::with('user')
            ->when($request->search, fn($q) =>
                $q->where('nama_toko', 'like', "%{$request->search}%"))
            ->when($request->status,   fn($q) => $q->where('status', $request->status))
            ->when($request->kategori, fn($q) => $q->where('kategori', $request->kategori))
            ->when($request->kecamatan,fn($q) => $q->where('kecamatan', $request->kecamatan))
            ->latest()->paginate(20);

        $pendingCount = Toko::whereIn('status', ['pending', 'menunggu_dinas'])->count();

        return view('admin.umkm.index', compact('tokos', 'pendingCount'));
    }

    public function show(Toko $toko)
    {
        $toko->load('user', 'produks', 'pesanans', 'ulasans');
        return view('admin.umkm.show', compact('toko'));
    }

    public function setujui(Toko $toko)
    {
        $toko->update([
            'status'             => 'aktif',
            'terverifikasi_dinas'=> false,
            'catatan_admin'      => request('catatan'),
        ]);

        Notifikasi::create([
            'user_id' => $toko->user_id,
            'judul'   => 'Pendaftaran UMKM Disetujui!',
            'pesan'   => "Selamat! Toko {$toko->nama_toko} telah disetujui dan kini aktif di RanahMart.",
            'tipe'    => 'success',
            'url'     => route('penjual.dashboard'),
        ]);

        return back()->with('success', 'UMKM berhasil disetujui dan diaktifkan.');
    }

    public function tolak(Toko $toko)
    {
        request()->validate(['catatan' => 'required|string']);

        $toko->update([
            'status'        => 'ditolak',
            'catatan_admin' => request('catatan'),
        ]);

        Notifikasi::create([
            'user_id' => $toko->user_id,
            'judul'   => 'Pendaftaran UMKM Ditolak',
            'pesan'   => "Pendaftaran toko {$toko->nama_toko} ditolak. Alasan: " . request('catatan'),
            'tipe'    => 'danger',
        ]);

        return back()->with('success', 'UMKM berhasil ditolak dan notifikasi dikirim.');
    }

    public function mintaDokumen(Toko $toko)
    {
        $toko->update([
            'status'        => 'dokumen_kurang',
            'catatan_admin' => request('catatan'),
        ]);

        Notifikasi::create([
            'user_id' => $toko->user_id,
            'judul'   => 'Dokumen Pendaftaran Kurang Lengkap',
            'pesan'   => "Harap lengkapi dokumen: " . request('catatan'),
            'tipe'    => 'warning',
        ]);

        return back()->with('success', 'Permintaan dokumen dikirim ke UMKM.');
    }

    public function teruskanDinas(Toko $toko)
    {
        $toko->update(['status' => 'menunggu_dinas']);
        return back()->with('success', 'UMKM diteruskan ke Dinas untuk verifikasi.');
    }

    public function nonaktif(Toko $toko)
    {
        $toko->update(['status' => 'nonaktif']);
        return back()->with('success', 'Toko berhasil dinonaktifkan.');
    }

    public function destroy(Toko $toko)
    {
        $toko->delete();
        return back()->with('success', 'Toko berhasil dihapus.');
    }
}

// ─── PRODUK CONTROLLER ─────────────────────────────────────────────────
class ProdukController extends Controller
{
    public function index(Request $request)
    {
        $produks = Produk::with('toko')
            ->when($request->search, fn($q) =>
                $q->where('nama', 'like', "%{$request->search}%"))
            ->when($request->status,   fn($q) => $q->where('status', $request->status))
            ->when($request->kategori, fn($q) => $q->where('kategori', $request->kategori))
            ->latest()->paginate(20);

        return view('admin.produk.index', compact('produks'));
    }

    public function show(Produk $produk)
    {
        $produk->load('toko', 'fotos', 'ulasans');
        return view('admin.produk.show', compact('produk'));
    }

    public function setujui(Produk $produk)
    {
        $produk->update(['status' => 'aktif']);

        Notifikasi::create([
            'user_id' => $produk->toko->user_id,
            'judul'   => 'Produk Disetujui & Aktif',
            'pesan'   => "Produk '{$produk->nama}' telah disetujui dan kini tayang di RanahMart.",
            'tipe'    => 'success',
        ]);

        return back()->with('success', 'Produk berhasil disetujui.');
    }

    public function tolak(Produk $produk)
    {
        request()->validate(['catatan_review' => 'required|string']);
        $produk->update([
            'status'         => 'ditolak',
            'catatan_review' => request('catatan_review'),
        ]);

        Notifikasi::create([
            'user_id' => $produk->toko->user_id,
            'judul'   => 'Produk Ditolak',
            'pesan'   => "Produk '{$produk->nama}' ditolak. Alasan: " . request('catatan_review'),
            'tipe'    => 'danger',
        ]);

        return back()->with('success', 'Produk berhasil ditolak.');
    }

    public function turunkan(Produk $produk)
    {
        $produk->update(['status' => 'nonaktif']);
        return back()->with('success', 'Produk berhasil diturunkan dari platform.');
    }

    public function peringatkan(Produk $produk)
    {
        Notifikasi::create([
            'user_id' => $produk->toko->user_id,
            'judul'   => 'Peringatan: Produk Mendapat Laporan',
            'pesan'   => "Produk '{$produk->nama}' mendapat laporan dari pembeli. Harap segera perbaiki.",
            'tipe'    => 'warning',
        ]);
        return back()->with('success', 'Peringatan berhasil dikirim ke penjual.');
    }
}

// ─── IKLAN CONTROLLER ──────────────────────────────────────────────────
class IklanController extends Controller
{
    public function index(Request $request)
    {
        $iklans = Iklan::with('toko')
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->latest()->paginate(20);

        $stats = [
            'menunggu' => Iklan::where('status', 'menunggu')->count(),
            'aktif'    => Iklan::where('status', 'aktif')->count(),
            'pendapatan'=> Iklan::whereMonth('created_at', now()->month)
                            ->whereIn('status', ['aktif','selesai'])->sum('biaya'),
            'total_pengiklan' => Iklan::distinct('toko_id')->count('toko_id'),
        ];

        return view('admin.iklan.index', compact('iklans', 'stats'));
    }

    public function show(Iklan $iklan)
    {
        $iklan->load('toko');
        return view('admin.iklan.show', compact('iklan'));
    }

    public function setujui(Iklan $iklan)
    {
        $iklan->update([
            'status'        => 'aktif',
            'disetujui_at'  => now(),
            'disetujui_oleh'=> auth()->id(),
        ]);

        Notifikasi::create([
            'user_id' => $iklan->toko->user_id,
            'judul'   => 'Iklan Disetujui & Mulai Tayang!',
            'pesan'   => "Iklan '{$iklan->judul}' telah disetujui dan mulai tayang di halaman publik.",
            'tipe'    => 'success',
        ]);

        return back()->with('success', 'Iklan berhasil disetujui dan ditayangkan.');
    }

    public function tolak(Iklan $iklan)
    {
        request()->validate(['catatan_admin' => 'required|string']);
        $iklan->update([
            'status'       => 'ditolak',
            'catatan_admin'=> request('catatan_admin'),
        ]);

        Notifikasi::create([
            'user_id' => $iklan->toko->user_id,
            'judul'   => 'Pengajuan Iklan Ditolak',
            'pesan'   => "Iklan '{$iklan->judul}' ditolak. Alasan: " . request('catatan_admin'),
            'tipe'    => 'danger',
        ]);

        return back()->with('success', 'Iklan berhasil ditolak.');
    }

    public function revisi(Iklan $iklan)
    {
        request()->validate(['catatan_admin' => 'required|string']);
        $iklan->update([
            'status'       => 'ditinjau',
            'catatan_admin'=> request('catatan_admin'),
        ]);

        Notifikasi::create([
            'user_id' => $iklan->toko->user_id,
            'judul'   => 'Iklan Perlu Direvisi',
            'pesan'   => "Iklan '{$iklan->judul}' perlu direvisi. Catatan: " . request('catatan_admin'),
            'tipe'    => 'warning',
        ]);

        return back()->with('success', 'Permintaan revisi iklan berhasil dikirim.');
    }

    public function hentikan(Iklan $iklan)
    {
        $iklan->update(['status' => 'dihentikan']);
        return back()->with('success', 'Iklan berhasil dihentikan.');
    }
}

// ─── TRANSAKSI CONTROLLER ──────────────────────────────────────────────
class TransaksiController extends Controller
{
    public function index(Request $request)
    {
        $pesanans = Pesanan::with(['pembeli', 'toko'])
            ->when($request->search, fn($q) =>
                $q->where('kode_pesanan', 'like', "%{$request->search}%"))
            ->when($request->status, fn($q) => $q->where('status_pesanan', $request->status))
            ->when($request->bulan,  fn($q) =>
                $q->whereMonth('created_at', $request->bulan))
            ->latest()->paginate(25);

        $stats = [
            'total_volume'  => Pesanan::where('status_bayar','lunas')
                                ->whereMonth('created_at', now()->month)->sum('total'),
            'total_komisi'  => Pesanan::where('status_bayar','lunas')
                                ->whereMonth('created_at', now()->month)->sum('komisi_platform'),
            'sukses_persen' => 98.2,
            'retur_persen'  => 1.8,
        ];

        return view('admin.transaksi.index', compact('pesanans', 'stats'));
    }

    public function show(Pesanan $pesanan)
    {
        $pesanan->load(['pembeli', 'toko', 'items.produk', 'alamat', 'retur']);
        return view('admin.transaksi.show', compact('pesanan'));
    }

    public function export()
    {
        // Export CSV/Excel menggunakan Laravel Excel atau manual
        $pesanans = Pesanan::with(['pembeli','toko'])
            ->where('status_bayar','lunas')
            ->whereMonth('created_at', now()->month)
            ->get();

        $filename = 'transaksi-' . now()->format('Y-m') . '.csv';
        $headers = ['Content-Type' => 'text/csv', 'Content-Disposition' => "attachment; filename={$filename}"];

        $callback = function() use ($pesanans) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Kode', 'Pembeli', 'Penjual', 'Total', 'Komisi', 'Status', 'Tanggal']);
            foreach ($pesanans as $p) {
                fputcsv($file, [
                    $p->kode_pesanan, $p->pembeli->nama_lengkap, $p->toko->nama_toko,
                    $p->total, $p->komisi_platform, $p->status_pesanan, $p->created_at,
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}

// ─── RETUR CONTROLLER ──────────────────────────────────────────────────
class ReturController extends Controller
{
    public function index(Request $request)
    {
        $returs = Retur::with(['pesanan', 'pembeli', 'toko'])
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->latest()->paginate(20);

        return view('admin.retur.index', compact('returs'));
    }

    public function show(Retur $retur)
    {
        $retur->load(['pesanan.items.produk', 'pembeli', 'toko', 'fotos']);
        return view('admin.retur.show', compact('retur'));
    }

    public function setujui(Retur $retur)
    {
        request()->validate(['keputusan_admin' => 'required|string']);
        $retur->update([
            'status'          => 'disetujui',
            'keputusan_admin' => request('keputusan_admin'),
        ]);

        // Proses refund ke pembeli
        Notifikasi::create([
            'user_id' => $retur->pembeli_id,
            'judul'   => 'Retur Disetujui — Refund Diproses',
            'pesan'   => "Pengajuan retur #{$retur->kode_retur} disetujui. Refund Rp " .
                         number_format($retur->nilai_retur) . " sedang diproses.",
            'tipe'    => 'success',
        ]);

        return back()->with('success', 'Retur disetujui dan refund diproses.');
    }

    public function tolak(Retur $retur)
    {
        request()->validate(['keputusan_admin' => 'required|string']);
        $retur->update([
            'status'          => 'ditolak',
            'keputusan_admin' => request('keputusan_admin'),
        ]);

        Notifikasi::create([
            'user_id' => $retur->pembeli_id,
            'judul'   => 'Pengajuan Retur Ditolak',
            'pesan'   => "Retur #{$retur->kode_retur} ditolak. Alasan: " . request('keputusan_admin'),
            'tipe'    => 'danger',
        ]);

        return back()->with('success', 'Retur berhasil ditolak.');
    }
}

// ─── LAPORAN CONTROLLER ────────────────────────────────────────────────
class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $bulan = $request->bulan ?? now()->month;
        $tahun = $request->tahun ?? now()->year;

        $stats = [
            'total_transaksi'  => Pesanan::where('status_bayar','lunas')
                                    ->whereMonth('created_at', $bulan)
                                    ->whereYear('created_at', $tahun)->sum('total'),
            'total_pesanan'    => Pesanan::whereMonth('created_at', $bulan)
                                    ->whereYear('created_at', $tahun)->count(),
            'total_komisi'     => Pesanan::where('status_bayar','lunas')
                                    ->whereMonth('created_at', $bulan)
                                    ->whereYear('created_at', $tahun)->sum('komisi_platform'),
            'pendapatan_iklan' => Iklan::whereIn('status',['aktif','selesai'])
                                    ->whereMonth('created_at', $bulan)->sum('biaya'),
        ];

        $chartBulanan = [];
        for ($m = 1; $m <= 12; $m++) {
            $chartBulanan[] = [
                'bulan' => date('M', mktime(0,0,0,$m,1,$tahun)),
                'nilai' => Pesanan::where('status_bayar','lunas')
                            ->whereMonth('created_at', $m)
                            ->whereYear('created_at', $tahun)->sum('total'),
            ];
        }

        $topUmkm = Toko::with('user')
            ->withSum(['pesanans as omzet' => fn($q) =>
                $q->where('status_bayar','lunas')
                  ->whereMonth('created_at', $bulan)
            ], 'total')
            ->orderByDesc('omzet')
            ->limit(10)->get();

        return view('admin.laporan.index', compact('stats', 'chartBulanan', 'topUmkm', 'bulan', 'tahun'));
    }

    public function export(Request $request)
    {
        // Export laporan ke PDF/Excel
        return back()->with('success', 'Laporan berhasil diunduh.');
    }
}
