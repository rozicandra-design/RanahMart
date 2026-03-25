<?php
namespace App\Http\Controllers\Penjual;

use App\Http\Controllers\Controller;
use App\Models\Produk;
use App\Models\FotoProduk;
use App\Models\Pesanan;
use App\Models\Ulasan;
use App\Models\Iklan;
use App\Models\Voucher;
use App\Models\Notifikasi;
use App\Models\Pencairan;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

// ─── DASHBOARD ────────────────────────────────────────────────────────
class DashboardController extends Controller
{
    public function index()
    {
        $toko = auth()->user()->toko;

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
        $toko = auth()->user()->toko;
        $bulan = $request->bulan ?? now()->month;
        $tahun = $request->tahun ?? now()->year;

        $stats = [
            'total_penjualan' => Pesanan::where('toko_id', $toko->id)
                ->where('status_bayar', 'lunas')
                ->whereMonth('created_at', $bulan)->sum('total'),
            'total_pesanan'  => Pesanan::where('toko_id', $toko->id)
                ->whereMonth('created_at', $bulan)->count(),
            'pengunjung'     => 1840,
            'konversi'       => 13.4,
        ];

        $kategoriBars = Produk::where('toko_id', $toko->id)
            ->select('kategori', \DB::raw('sum(total_terjual) as total'))
            ->groupBy('kategori')->get();

        return view('penjual.laporan', compact('toko','stats','kategoriBars','bulan','tahun'));
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

// ─── PRODUK ────────────────────────────────────────────────────────────
class ProdukController extends Controller
{
    public function index(Request $request)
    {
        $toko = auth()->user()->toko;
        $produks = Produk::where('toko_id', $toko->id)
            ->when($request->search, fn($q) =>
                $q->where('nama', 'like', "%{$request->search}%"))
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->with('fotoUtama')
            ->latest()->paginate(12);

        return view('penjual.produk.index', compact('produks'));
    }

    public function create()
    {
        return view('penjual.produk.create');
    }

    public function store(Request $request)
    {
        $toko = auth()->user()->toko;

        $data = $request->validate([
            'nama'           => 'required|string|max:200',
            'deskripsi'      => 'nullable|string',
            'kategori'       => 'required|string',
            'sub_kategori'   => 'nullable|string',
            'harga'          => 'required|numeric|min:0',
            'harga_coret'    => 'nullable|numeric|min:0',
            'stok'           => 'required|integer|min:0',
            'berat'          => 'nullable|integer|min:0',
            'sku'            => 'nullable|string|max:50',
            'foto_produk'    => 'required|array|min:1|max:5',
            'foto_produk.*'  => 'image|mimes:jpg,jpeg,png,webp|max:5120',
            'jasa_kirim'     => 'nullable|array',
            'estimasi_kirim' => 'nullable|string',
        ]);

        $produk = Produk::create([
            ...$data,
            'toko_id' => $toko->id,
            'slug'    => Str::slug($data['nama']) . '-' . uniqid(),
            'status'  => 'pending',
        ]);

        // Upload foto
        foreach ($request->file('foto_produk') as $index => $foto) {
            $path = $foto->store("produk/{$produk->id}", 'public');
            FotoProduk::create([
                'produk_id' => $produk->id,
                'path'      => $path,
                'is_utama'  => $index === 0,
                'urutan'    => $index + 1,
            ]);
        }

        return redirect()->route('penjual.produk.index')
            ->with('success', 'Produk berhasil dikirim untuk review admin (1–2 hari kerja).');
    }

    public function edit(Produk $produk)
    {
        $this->authorize('update', $produk);
        $produk->load('fotos');
        return view('penjual.produk.edit', compact('produk'));
    }

    public function update(Request $request, Produk $produk)
    {
        $this->authorize('update', $produk);

        $data = $request->validate([
            'nama'        => 'required|string|max:200',
            'deskripsi'   => 'nullable|string',
            'harga'       => 'required|numeric|min:0',
            'harga_coret' => 'nullable|numeric|min:0',
            'stok'        => 'required|integer|min:0',
        ]);

        $produk->update([...$data, 'status' => 'pending']);

        return redirect()->route('penjual.produk.index')
            ->with('success', 'Produk diperbarui dan dikirim ulang untuk review.');
    }

    public function toggleStatus(Produk $produk)
    {
        $this->authorize('update', $produk);
        $produk->update([
            'status' => $produk->status === 'aktif' ? 'nonaktif' : 'aktif',
        ]);
        return back()->with('success', 'Status produk berhasil diubah.');
    }

    public function destroy(Produk $produk)
    {
        $this->authorize('delete', $produk);
        $produk->fotos->each(fn($f) => Storage::disk('public')->delete($f->path));
        $produk->delete();
        return back()->with('success', 'Produk berhasil dihapus.');
    }
}

// ─── PESANAN ────────────────────────────────────────────────────────────
class PesananController extends Controller
{
    public function index(Request $request)
    {
        $toko = auth()->user()->toko;
        $pesanans = Pesanan::where('toko_id', $toko->id)
            ->when($request->status, fn($q) =>
                $q->where('status_pesanan', $request->status))
            ->with('pembeli', 'items.produk')
            ->latest()->paginate(15);

        $counts = [
            'baru'    => Pesanan::where('toko_id',$toko->id)->where('status_pesanan','menunggu')->count(),
            'proses'  => Pesanan::where('toko_id',$toko->id)->where('status_pesanan','diproses')->count(),
            'kirim'   => Pesanan::where('toko_id',$toko->id)->where('status_pesanan','dikirim')->count(),
            'selesai' => Pesanan::where('toko_id',$toko->id)->where('status_pesanan','selesai')->count(),
        ];

        return view('penjual.pesanan.index', compact('pesanans', 'counts'));
    }

    public function show(Pesanan $pesanan)
    {
        $this->authorize('view', $pesanan);
        $pesanan->load('pembeli', 'items.produk', 'alamat');
        return view('penjual.pesanan.show', compact('pesanan'));
    }

    public function konfirmasi(Pesanan $pesanan)
    {
        $this->authorize('update', $pesanan);
        $pesanan->update([
            'status_pesanan' => 'diproses',
            'dikonfirmasi_at' => now(),
        ]);

        Notifikasi::create([
            'user_id' => $pesanan->pembeli_id,
            'judul'   => 'Pesanan Dikonfirmasi Penjual',
            'pesan'   => "Pesanan #{$pesanan->kode_pesanan} sedang diproses oleh penjual.",
            'tipe'    => 'info',
        ]);

        return back()->with('success', 'Pesanan berhasil dikonfirmasi.');
    }

    public function tolak(Pesanan $pesanan)
    {
        $this->authorize('update', $pesanan);
        $pesanan->update(['status_pesanan' => 'dibatalkan']);

        Notifikasi::create([
            'user_id' => $pesanan->pembeli_id,
            'judul'   => 'Pesanan Dibatalkan Penjual',
            'pesan'   => "Pesanan #{$pesanan->kode_pesanan} dibatalkan oleh penjual.",
            'tipe'    => 'danger',
        ]);

        return back()->with('success', 'Pesanan berhasil ditolak.');
    }

    public function kirim(Request $request, Pesanan $pesanan)
    {
        $this->authorize('update', $pesanan);
        $data = $request->validate([
            'jasa_kirim' => 'required|string',
            'no_resi'    => 'required|string|max:50',
        ]);

        $pesanan->update([
            ...$data,
            'status_pesanan' => 'dikirim',
            'dikirim_at'     => now(),
        ]);

        Notifikasi::create([
            'user_id' => $pesanan->pembeli_id,
            'judul'   => 'Pesanan Sedang Dikirim!',
            'pesan'   => "Pesanan #{$pesanan->kode_pesanan} dikirim via {$data['jasa_kirim']}. No. Resi: {$data['no_resi']}",
            'tipe'    => 'success',
        ]);

        return back()->with('success', 'Resi pengiriman berhasil diinput.');
    }
}

// ─── KEUANGAN ───────────────────────────────────────────────────────────
class KeuanganController extends Controller
{
    public function index()
    {
        $toko = auth()->user()->toko;

        $stats = [
            'saldo'           => $toko->saldo,
            'saldo_proses'    => $toko->saldo_proses,
            'pendapatan_bulan'=> Pesanan::where('toko_id', $toko->id)
                ->where('status_bayar', 'lunas')
                ->whereMonth('created_at', now()->month)->sum('total'),
            'total_pesanan'   => Pesanan::where('toko_id', $toko->id)
                ->whereMonth('created_at', now()->month)->count(),
            'total_dicairkan' => Pencairan::where('toko_id', $toko->id)
                ->where('status', 'berhasil')->sum('jumlah'),
        ];

        $riwayat = Pencairan::where('toko_id', $toko->id)
            ->latest()->limit(10)->get();

        return view('penjual.keuangan.index', compact('toko', 'stats', 'riwayat'));
    }

    public function cairkan(Request $request)
    {
        $toko = auth()->user()->toko;
        $data = $request->validate([
            'jumlah' => ['required', 'numeric', 'min:50000',
                function ($attr, $val, $fail) use ($toko) {
                    if ($val > $toko->saldo) $fail('Saldo tidak mencukupi.');
                }
            ],
        ]);

        Pencairan::create([
            'toko_id'     => $toko->id,
            'jumlah'      => $data['jumlah'],
            'bank'        => $toko->bank,
            'no_rekening' => $toko->no_rekening,
            'atas_nama'   => $toko->atas_nama_rekening,
            'status'      => 'proses',
        ]);

        $toko->decrement('saldo', $data['jumlah']);

        return back()->with('success', 'Pencairan dana berhasil diajukan. Estimasi 1–2 hari kerja.');
    }
}

// ─── ULASAN ─────────────────────────────────────────────────────────────
class UlasanController extends Controller
{
    public function index(Request $request)
    {
        $toko = auth()->user()->toko;

        $ulasans = Ulasan::where('toko_id', $toko->id)
            ->when($request->status === 'belum_dibalas', fn($q) => $q->whereNull('balasan'))
            ->when($request->rating, fn($q) => $q->where('rating', $request->rating))
            ->with('user', 'produk')
            ->latest()->paginate(15);

        $stats = [
            'total'         => $toko->total_ulasan,
            'rating'        => $toko->rating,
            'belum_dibalas' => Ulasan::where('toko_id', $toko->id)->whereNull('balasan')->count(),
            'bintang5'      => Ulasan::where('toko_id', $toko->id)->where('rating', 5)->count(),
        ];

        return view('penjual.ulasan.index', compact('ulasans', 'stats'));
    }

    public function balas(Request $request, Ulasan $ulasan)
    {
        $request->validate(['balasan' => 'required|string|max:500']);

        $ulasan->update([
            'balasan'    => $request->balasan,
            'dibalas_at' => now(),
        ]);

        return back()->with('success', 'Balasan berhasil dikirim.');
    }
}

// ─── IKLAN ──────────────────────────────────────────────────────────────
class IklanController extends Controller
{
    public function index()
    {
        $toko = auth()->user()->toko;
        $iklans = Iklan::where('toko_id', $toko->id)->latest()->get();
        $iklanAktif = $iklans->firstWhere('status', 'aktif');

        return view('penjual.iklan.index', compact('toko', 'iklans', 'iklanAktif'));
    }

    public function create()
    {
        return view('penjual.iklan.create');
    }

    public function store(Request $request)
    {
        $toko = auth()->user()->toko;

        $data = $request->validate([
            'paket'           => 'required|in:starter,mingguan,bulanan',
            'posisi'          => 'required|string',
            'judul'           => 'required|string|max:150',
            'sub_judul'       => 'nullable|string|max:200',
            'teks_cta'        => 'required|string|max:50',
            'tanggal_mulai'   => 'required|date|after_or_equal:today',
            'catatan_pengaju' => 'nullable|string',
            'banner'          => 'nullable|image|mimes:jpg,jpeg,png|max:5120',
        ]);

        $biaya = match($data['paket']) {
            'starter'   => 50000,
            'mingguan'  => 150000,
            'bulanan'   => 450000,
        };

        $durasi = match($data['paket']) {
            'starter'  => 3,
            'mingguan' => 7,
            'bulanan'  => 30,
        };

        $bannerPath = null;
        if ($request->hasFile('banner')) {
            $bannerPath = $request->file('banner')->store("iklan/{$toko->id}", 'public');
        }

        Iklan::create([
            ...$data,
            'toko_id'         => $toko->id,
            'biaya'           => $biaya,
            'banner'          => $bannerPath,
            'tanggal_selesai' => Carbon::parse($data['tanggal_mulai'])->addDays($durasi),
            'status'          => 'menunggu',
        ]);

        return redirect()->route('penjual.iklan.index')
            ->with('success', 'Pengajuan iklan dikirim! Admin akan merespons dalam 1×24 jam.');
    }

    public function destroy(Iklan $iklan)
    {
        $iklan->delete();
        return back()->with('success', 'Pengajuan iklan dibatalkan.');
    }
}

// ─── PROMO & VOUCHER ────────────────────────────────────────────────────
class PromoController extends Controller
{
    public function index()
    {
        $toko = auth()->user()->toko;
        $vouchers = Voucher::where('toko_id', $toko->id)->latest()->get();
        return view('penjual.promo.index', compact('vouchers'));
    }

    public function store(Request $request)
    {
        $toko = auth()->user()->toko;
        $data = $request->validate([
            'kode'           => 'required|string|max:30|unique:vouchers',
            'jenis'          => 'required|in:persen,nominal,gratis_ongkir',
            'nilai'          => 'required_unless:jenis,gratis_ongkir|numeric|min:0',
            'maks_potongan'  => 'nullable|numeric|min:0',
            'min_belanja'    => 'nullable|numeric|min:0',
            'kuota'          => 'nullable|integer|min:1',
            'berlaku_mulai'  => 'required|date',
            'berlaku_hingga' => 'required|date|after:berlaku_mulai',
        ]);

        Voucher::create([...$data, 'toko_id' => $toko->id, 'aktif' => true]);

        return back()->with('success', 'Voucher berhasil dibuat.');
    }

    public function toggle(Voucher $voucher)
    {
        $voucher->update(['aktif' => !$voucher->aktif]);
        return back()->with('success', 'Status voucher berhasil diubah.');
    }

    public function destroy(Voucher $voucher)
    {
        $voucher->delete();
        return back()->with('success', 'Voucher berhasil dihapus.');
    }
}

// ─── PROFIL TOKO ────────────────────────────────────────────────────────
class TokoController extends Controller
{
    public function edit()
    {
        $toko = auth()->user()->toko;
        return view('penjual.toko.edit', compact('toko'));
    }

    public function update(Request $request)
    {
        $toko = auth()->user()->toko;

        $data = $request->validate([
            'nama_toko'       => 'required|string|max:150',
            'deskripsi'       => 'nullable|string',
            'kategori'        => 'required|string',
            'kecamatan'       => 'required|string',
            'alamat_lengkap'  => 'nullable|string',
            'no_hp'           => 'nullable|string|max:20',
            'jam_operasional' => 'nullable|string',
            'logo'            => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'banner'          => 'nullable|image|mimes:jpg,jpeg,png|max:5120',
        ]);

        if ($request->hasFile('logo')) {
            if ($toko->logo) Storage::disk('public')->delete($toko->logo);
            $data['logo'] = $request->file('logo')->store("toko/logo/{$toko->id}", 'public');
        }

        if ($request->hasFile('banner')) {
            if ($toko->banner) Storage::disk('public')->delete($toko->banner);
            $data['banner'] = $request->file('banner')->store("toko/banner/{$toko->id}", 'public');
        }

        $toko->update($data);

        return back()->with('success', 'Profil toko berhasil diperbarui.');
    }
}
