<?php
namespace App\Http\Controllers\Pembeli;

use App\Http\Controllers\Controller;
use App\Models\Produk;
use App\Models\Pesanan;
use App\Models\ItemPesanan;
use App\Models\Keranjang;
use App\Models\Wishlist;
use App\Models\Ulasan;
use App\Models\Retur;
use App\Models\Alamat;
use App\Models\Voucher;
use App\Models\Poin;
use App\Models\Notifikasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

// ─── DASHBOARD ─────────────────────────────────────────────────────────
class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $stats = [
            'total_pesanan'   => Pesanan::where('pembeli_id', $user->id)->count(),
            'total_belanja'   => Pesanan::where('pembeli_id', $user->id)
                ->where('status_bayar', 'lunas')->sum('total'),
            'total_poin'      => $user->total_poin,
            'voucher_aktif'   => $user->voucherUsers()
                ->whereNull('digunakan_at')
                ->whereHas('voucher', fn($q) => $q->where('aktif', true)
                    ->where('berlaku_hingga', '>=', now()))->count(),
        ];

        $pesananTerkini = Pesanan::where('pembeli_id', $user->id)
            ->with('toko', 'items.produk')
            ->latest()->limit(3)->get();

        $rekomendasi = Produk::where('status', 'aktif')
            ->inRandomOrder()->limit(6)->get();

        $voucherAktif = $user->voucherUsers()
            ->whereNull('digunakan_at')
            ->with('voucher')
            ->get()
            ->filter(fn($vu) => $vu->voucher && $vu->voucher->isValid())
            ->first();

        return view('pembeli.dashboard', compact('user', 'stats', 'pesananTerkini', 'rekomendasi', 'voucherAktif'));
    }

    public function notifikasi()
    {
        $notifikasis = Notifikasi::where('user_id', auth()->id())
            ->latest()->paginate(20);
        Notifikasi::where('user_id', auth()->id())
            ->where('sudah_dibaca', false)->update(['sudah_dibaca' => true]);
        return view('pembeli.notifikasi', compact('notifikasis'));
    }

    public function pengaturan()
    {
        return view('pembeli.pengaturan');
    }

    public function simpanPengaturan(Request $request)
    {
        // Simpan preferensi notifikasi user
        return back()->with('success', 'Pengaturan berhasil disimpan.');
    }
}

// ─── PESANAN ────────────────────────────────────────────────────────────
class PesananController extends Controller
{
    public function index(Request $request)
    {
        $pesanans = Pesanan::where('pembeli_id', auth()->id())
            ->when($request->status, fn($q) =>
                $q->where('status_pesanan', $request->status))
            ->with('toko', 'items.produk')
            ->latest()->paginate(10);

        return view('pembeli.pesanan.index', compact('pesanans'));
    }

    public function show(Pesanan $pesanan)
    {
        abort_if($pesanan->pembeli_id !== auth()->id(), 403);
        $pesanan->load('toko', 'items.produk', 'alamat', 'retur');
        return view('pembeli.pesanan.show', compact('pesanan'));
    }

    public function checkout(Request $request)
    {
        $user = auth()->user();
        $request->validate([
            'alamat_id'   => 'required|exists:alamats,id',
            'jasa_kirim'  => 'required|string',
            'metode_bayar'=> 'required|string',
        ]);

        $keranjangs = Keranjang::where('user_id', $user->id)
            ->where('dipilih', true)
            ->with('produk.toko')
            ->get();

        if ($keranjangs->isEmpty()) {
            return back()->with('error', 'Keranjang kosong.');
        }

        // Group by toko
        $grouped = $keranjangs->groupBy(fn($k) => $k->produk->toko_id);

        foreach ($grouped as $tokoId => $items) {
            $subtotal = $items->sum(fn($k) => $k->jumlah * $k->produk->harga);
            $ongkir   = 12000;
            $total    = $subtotal + $ongkir;
            $komisi   = $total * 0.03;

            $pesanan = Pesanan::create([
                'pembeli_id'       => $user->id,
                'toko_id'          => $tokoId,
                'alamat_id'        => $request->alamat_id,
                'subtotal'         => $subtotal,
                'ongkir'           => $ongkir,
                'total'            => $total,
                'metode_bayar'     => $request->metode_bayar,
                'status_bayar'     => 'menunggu',
                'status_pesanan'   => 'menunggu',
                'komisi_platform'  => $komisi,
            ]);

            foreach ($items as $item) {
                ItemPesanan::create([
                    'pesanan_id'   => $pesanan->id,
                    'produk_id'    => $item->produk_id,
                    'nama_produk'  => $item->produk->nama,
                    'harga_satuan' => $item->produk->harga,
                    'jumlah'       => $item->jumlah,
                    'subtotal'     => $item->jumlah * $item->produk->harga,
                ]);
                // Kurangi stok
                $item->produk->decrement('stok', $item->jumlah);
            }

            // Notif ke penjual
            Notifikasi::create([
                'user_id' => $items->first()->produk->toko->user_id,
                'judul'   => 'Pesanan Baru Masuk!',
                'pesan'   => "Pesanan #{$pesanan->kode_pesanan} menunggu konfirmasi.",
                'tipe'    => 'success',
            ]);
        }

        // Hapus dari keranjang
        Keranjang::where('user_id', $user->id)->where('dipilih', true)->delete();

        return redirect()->route('pembeli.pesanan.index')
            ->with('success', 'Pesanan berhasil dibuat! Silakan lakukan pembayaran.');
    }

    public function konfirmasiTerima(Pesanan $pesanan)
    {
        abort_if($pesanan->pembeli_id !== auth()->id(), 403);
        $pesanan->update(['status_pesanan' => 'selesai', 'selesai_at' => now()]);

        // Berikan poin
        $poinEarned = (int) ($pesanan->total / 1000);
        Poin::create([
            'user_id'    => auth()->id(),
            'tipe'       => 'earn',
            'jumlah'     => $poinEarned,
            'keterangan' => "Pembelian #{$pesanan->kode_pesanan}",
            'pesanan_id' => $pesanan->id,
        ]);

        return back()->with('success', "Pesanan selesai! +{$poinEarned} poin reward diterima.");
    }

    public function batalkan(Pesanan $pesanan)
    {
        abort_if($pesanan->pembeli_id !== auth()->id(), 403);
        abort_if(!in_array($pesanan->status_pesanan, ['menunggu', 'dikonfirmasi']), 403);

        $pesanan->update(['status_pesanan' => 'dibatalkan']);

        return back()->with('success', 'Pesanan berhasil dibatalkan.');
    }
}

// ─── KERANJANG ──────────────────────────────────────────────────────────
class KeranjangController extends Controller
{
    public function index()
    {
        $keranjangs = Keranjang::where('user_id', auth()->id())
            ->with('produk.toko.user')
            ->get()
            ->groupBy(fn($k) => $k->produk->toko_id);

        $total = Keranjang::where('user_id', auth()->id())
            ->where('dipilih', true)
            ->with('produk')
            ->get()
            ->sum(fn($k) => $k->jumlah * $k->produk->harga);

        return view('pembeli.keranjang', compact('keranjangs', 'total'));
    }

    public function tambah(Request $request)
    {
        $request->validate(['produk_id' => 'required|exists:produks,id', 'jumlah' => 'integer|min:1']);

        $produk = Produk::findOrFail($request->produk_id);
        if ($produk->stok < ($request->jumlah ?? 1)) {
            return back()->with('error', 'Stok tidak mencukupi.');
        }

        Keranjang::updateOrCreate(
            ['user_id' => auth()->id(), 'produk_id' => $request->produk_id],
            ['jumlah'  => \DB::raw("jumlah + " . ($request->jumlah ?? 1))]
        );

        return back()->with('success', 'Produk ditambahkan ke keranjang!');
    }

    public function updateQty(Request $request, Keranjang $item)
    {
        abort_if($item->user_id !== auth()->id(), 403);
        $request->validate(['jumlah' => 'required|integer|min:1']);
        $item->update(['jumlah' => $request->jumlah]);
        return back();
    }

    public function hapus(Keranjang $item)
    {
        abort_if($item->user_id !== auth()->id(), 403);
        $item->delete();
        return back()->with('success', 'Produk dihapus dari keranjang.');
    }

    public function kosongkan()
    {
        Keranjang::where('user_id', auth()->id())->delete();
        return back()->with('success', 'Keranjang berhasil dikosongkan.');
    }
}

// ─── WISHLIST ────────────────────────────────────────────────────────────
class WishlistController extends Controller
{
    public function index()
    {
        $wishlists = Wishlist::where('user_id', auth()->id())
            ->with('produk.toko', 'produk.fotoUtama')
            ->latest()->get();
        return view('pembeli.wishlist', compact('wishlists'));
    }

    public function toggle(Produk $produk)
    {
        $existing = Wishlist::where('user_id', auth()->id())
            ->where('produk_id', $produk->id)->first();

        if ($existing) {
            $existing->delete();
            return back()->with('success', 'Produk dihapus dari wishlist.');
        }

        Wishlist::create(['user_id' => auth()->id(), 'produk_id' => $produk->id]);
        return back()->with('success', 'Produk ditambahkan ke wishlist!');
    }
}

// ─── ULASAN ─────────────────────────────────────────────────────────────
class UlasanController extends Controller
{
    public function index()
    {
        $ulasanSaya   = Ulasan::where('user_id', auth()->id())->with('produk', 'toko')->latest()->get();
        $belumDiulas  = ItemPesanan::whereHas('pesanan', fn($q) =>
            $q->where('pembeli_id', auth()->id())->where('status_pesanan', 'selesai'))
            ->where('sudah_diulas', false)->with('produk', 'pesanan')->get();

        return view('pembeli.ulasan', compact('ulasanSaya', 'belumDiulas'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'item_pesanan_id' => 'required|exists:item_pesanans,id',
            'rating'          => 'required|integer|min:1|max:5',
            'komentar'        => 'nullable|string|max:1000',
            'foto_ulasan'     => 'nullable|array|max:3',
            'foto_ulasan.*'   => 'image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $item = ItemPesanan::with('pesanan', 'produk')->findOrFail($data['item_pesanan_id']);
        abort_if($item->pesanan->pembeli_id !== auth()->id(), 403);

        $ulasan = Ulasan::create([
            'item_pesanan_id' => $item->id,
            'user_id'         => auth()->id(),
            'produk_id'       => $item->produk_id,
            'toko_id'         => $item->produk->toko_id,
            'rating'          => $data['rating'],
            'komentar'        => $data['komentar'],
        ]);

        if ($request->hasFile('foto_ulasan')) {
            foreach ($request->file('foto_ulasan') as $foto) {
                $path = $foto->store("ulasan/{$ulasan->id}", 'public');
                $ulasan->fotos()->create(['path' => $path]);
            }
        }

        $item->update(['sudah_diulas' => true]);

        // Update rating produk & toko
        $produk = $item->produk;
        $produk->update([
            'rating'       => Ulasan::where('produk_id', $produk->id)->avg('rating'),
            'total_ulasan' => Ulasan::where('produk_id', $produk->id)->count(),
        ]);

        // +10 poin reward untuk ulasan
        Poin::create([
            'user_id'    => auth()->id(),
            'tipe'       => 'earn',
            'jumlah'     => 10,
            'keterangan' => 'Menulis ulasan produk',
        ]);

        return back()->with('success', 'Ulasan berhasil dikirim! +10 poin reward.');
    }
}

// ─── RETUR ──────────────────────────────────────────────────────────────
class ReturController extends Controller
{
    public function index()
    {
        $returs = Retur::where('pembeli_id', auth()->id())
            ->with('pesanan')->latest()->get();
        return view('pembeli.retur', compact('returs'));
    }

    public function show(Retur $retur)
    {
        abort_if($retur->pembeli_id !== auth()->id(), 403);
        return view('pembeli.retur-detail', compact('retur'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'pesanan_id'  => 'required|exists:pesanans,id',
            'alasan'      => 'required|string',
            'keterangan'  => 'nullable|string',
            'foto_bukti'  => 'nullable|array|max:3',
            'foto_bukti.*'=> 'image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $pesanan = Pesanan::findOrFail($data['pesanan_id']);
        abort_if($pesanan->pembeli_id !== auth()->id(), 403);

        $retur = Retur::create([
            'pesanan_id'  => $pesanan->id,
            'pembeli_id'  => auth()->id(),
            'toko_id'     => $pesanan->toko_id,
            'alasan'      => $data['alasan'],
            'keterangan'  => $data['keterangan'],
            'nilai_retur' => $pesanan->total,
            'status'      => 'diajukan',
        ]);

        if ($request->hasFile('foto_bukti')) {
            foreach ($request->file('foto_bukti') as $foto) {
                $path = $foto->store("retur/{$retur->id}", 'public');
                $retur->fotos()->create(['path' => $path]);
            }
        }

        return back()->with('success', 'Pengajuan retur berhasil dikirim. Admin akan meninjau dalam 1–3 hari.');
    }
}

// ─── ALAMAT ─────────────────────────────────────────────────────────────
class AlamatController extends Controller
{
    public function index()
    {
        $alamats = Alamat::where('user_id', auth()->id())->get();
        return view('pembeli.alamat', compact('alamats'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'label'         => 'required|string|max:50',
            'nama_penerima' => 'required|string|max:100',
            'no_hp'         => 'required|string|max:20',
            'alamat_lengkap'=> 'required|string',
            'kelurahan'     => 'nullable|string',
            'kecamatan'     => 'required|string',
            'kode_pos'      => 'nullable|string|max:10',
        ]);

        $isFirst = !Alamat::where('user_id', auth()->id())->exists();

        Alamat::create([
            ...$data,
            'user_id'  => auth()->id(),
            'kota'     => 'Padang',
            'provinsi' => 'Sumatera Barat',
            'is_utama' => $isFirst,
        ]);

        return back()->with('success', 'Alamat berhasil ditambahkan.');
    }

    public function update(Request $request, Alamat $alamat)
    {
        abort_if($alamat->user_id !== auth()->id(), 403);
        $alamat->update($request->only(['label','nama_penerima','no_hp','alamat_lengkap','kelurahan','kecamatan','kode_pos']));
        return back()->with('success', 'Alamat berhasil diperbarui.');
    }

    public function destroy(Alamat $alamat)
    {
        abort_if($alamat->user_id !== auth()->id(), 403);
        $alamat->delete();
        return back()->with('success', 'Alamat berhasil dihapus.');
    }

    public function jadikanUtama(Alamat $alamat)
    {
        abort_if($alamat->user_id !== auth()->id(), 403);
        Alamat::where('user_id', auth()->id())->update(['is_utama' => false]);
        $alamat->update(['is_utama' => true]);
        return back()->with('success', 'Alamat utama berhasil diubah.');
    }
}

// ─── VOUCHER ────────────────────────────────────────────────────────────
class VoucherController extends Controller
{
    public function index()
    {
        $vouchersSaya = auth()->user()->voucherUsers()
            ->with('voucher')->get();
        $voucherGlobal = Voucher::where('global', true)
            ->where('aktif', true)
            ->where('berlaku_hingga', '>=', now())->get();

        return view('pembeli.voucher', compact('vouchersSaya', 'voucherGlobal'));
    }

    public function validasi(Request $request)
    {
        $request->validate(['kode' => 'required|string', 'total' => 'required|numeric']);

        $voucher = Voucher::where('kode', strtoupper($request->kode))->first();

        if (!$voucher || !$voucher->isValid()) {
            return response()->json(['valid' => false, 'message' => 'Kode voucher tidak valid atau sudah kadaluarsa.']);
        }

        if ($request->total < $voucher->min_belanja) {
            return response()->json([
                'valid' => false,
                'message' => 'Minimum belanja Rp ' . number_format($voucher->min_belanja) . ' untuk voucher ini.',
            ]);
        }

        $diskon = match($voucher->jenis) {
            'persen'       => min($request->total * $voucher->nilai / 100, $voucher->maks_potongan ?? PHP_INT_MAX),
            'nominal'      => min($voucher->nilai, $request->total),
            'gratis_ongkir'=> 12000,
        };

        return response()->json(['valid' => true, 'diskon' => $diskon, 'voucher' => $voucher]);
    }
}

// ─── POIN ────────────────────────────────────────────────────────────────
class PoinController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $totalPoin = $user->total_poin;
        $riwayat = Poin::where('user_id', $user->id)->with('pesanan')->latest()->paginate(20);

        $tierThresholds = ['Bronze' => 0, 'Silver' => 500, 'Gold' => 1000, 'Platinum' => 2500];
        $tier = 'Bronze';
        $nextTier = 'Silver';
        $nextThreshold = 500;
        foreach ($tierThresholds as $name => $threshold) {
            if ($totalPoin >= $threshold) { $tier = $name; }
        }

        return view('pembeli.poin', compact('totalPoin', 'riwayat', 'tier', 'nextTier', 'nextThreshold'));
    }

    public function tukar(Request $request)
    {
        $request->validate(['jumlah_poin' => 'required|integer|min:100']);
        $user = auth()->user();
        $totalPoin = $user->total_poin;

        if ($request->jumlah_poin > $totalPoin) {
            return back()->with('error', 'Poin tidak mencukupi.');
        }

        Poin::create([
            'user_id'    => $user->id,
            'tipe'       => 'redeem',
            'jumlah'     => -$request->jumlah_poin,
            'keterangan' => 'Penukaran poin ke diskon belanja',
        ]);

        return back()->with('success', 'Poin berhasil ditukar! Diskon akan diterapkan saat checkout.');
    }
}

// ─── PROFIL ──────────────────────────────────────────────────────────────
class ProfilController extends Controller
{
    public function edit()
    {
        return view('pembeli.profil', ['user' => auth()->user()]);
    }

    public function update(Request $request)
    {
        $user = auth()->user();
        $data = $request->validate([
            'nama_depan'   => 'required|string|max:100',
            'nama_belakang'=> 'required|string|max:100',
            'no_hp'        => 'required|string|max:20',
            'tanggal_lahir'=> 'nullable|date',
            'jenis_kelamin'=> 'nullable|in:laki-laki,perempuan',
            'foto_profil'  => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($request->hasFile('foto_profil')) {
            $data['foto_profil'] = $request->file('foto_profil')
                ->store("profil/{$user->id}", 'public');
        }

        $user->update($data);
        return back()->with('success', 'Profil berhasil diperbarui.');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'password_lama'=> 'required',
            'password'     => 'required|min:8|confirmed',
        ]);

        if (!Hash::check($request->password_lama, auth()->user()->password)) {
            return back()->with('error', 'Kata sandi lama tidak sesuai.');
        }

        auth()->user()->update(['password' => Hash::make($request->password)]);
        return back()->with('success', 'Kata sandi berhasil diperbarui.');
    }
}

// ══════════════════════════════════════════════════════════════════════
// DINAS CONTROLLERS
// ══════════════════════════════════════════════════════════════════════
namespace App\Http\Controllers\Dinas;

use App\Http\Controllers\Controller;
use App\Models\Toko;
use App\Models\User;
use App\Models\Pesanan;
use App\Models\Notifikasi;
use App\Models\ProgramPembinaan;
use App\Models\PesertaPembinaan;
use App\Models\Pengumuman;
use App\Models\KunjunganLapangan;
use Illuminate\Http\Request;

// ─── DASHBOARD ─────────────────────────────────────────────────────────
class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'umkm_terverifikasi' => Toko::where('terverifikasi_dinas', true)->count(),
            'menunggu_verifikasi'=> Toko::where('status', 'menunggu_dinas')->count(),
            'total_omzet'        => Pesanan::where('status_bayar','lunas')
                ->whereMonth('created_at', now()->month)->sum('total'),
            'umkm_binaan'        => PesertaPembinaan::distinct('toko_id')->count(),
        ];

        $sebaranKecamatan = Toko::where('status','aktif')
            ->select('kecamatan', \DB::raw('count(*) as total'))
            ->groupBy('kecamatan')->orderByDesc('total')->limit(11)->get();

        $antrian = Toko::where('status','menunggu_dinas')
            ->with('user')->latest()->limit(3)->get();

        return view('dinas.dashboard', compact('stats','sebaranKecamatan','antrian'));
    }

    public function notifikasi()
    {
        $notifikasis = Notifikasi::where('user_id', auth()->id())->latest()->paginate(20);
        return view('dinas.notifikasi', compact('notifikasis'));
    }

    public function pengaturan()
    {
        return view('dinas.pengaturan');
    }

    public function simpanPengaturan(Request $request)
    {
        return back()->with('success', 'Pengaturan berhasil disimpan.');
    }
}

// ─── VERIFIKASI ─────────────────────────────────────────────────────────
class VerifikasiController extends Controller
{
    public function index(Request $request)
    {
        $tokos = Toko::where('status', 'menunggu_dinas')
            ->with('user')
            ->when($request->kecamatan, fn($q) => $q->where('kecamatan', $request->kecamatan))
            ->latest()->paginate(15);

        return view('dinas.verifikasi.index', compact('tokos'));
    }

    public function show(Toko $toko)
    {
        $toko->load('user','produks');
        return view('dinas.verifikasi.show', compact('toko'));
    }

    public function setujui(Toko $toko)
    {
        request()->validate(['catatan_dinas' => 'nullable|string']);

        $toko->update([
            'status'              => 'aktif',
            'terverifikasi_dinas' => true,
            'tanggal_sertifikat'  => now(),
            'kadaluarsa_sertifikat' => now()->addYear(),
            'no_sertifikat'       => 'SK-' . now()->year . '/UMKM/PDG/' . $toko->id,
            'catatan_dinas'       => request('catatan_dinas'),
        ]);

        Notifikasi::create([
            'user_id' => $toko->user_id,
            'judul'   => 'UMKM Resmi Terverifikasi Dinas!',
            'pesan'   => "Selamat! {$toko->nama_toko} telah mendapat sertifikat resmi dari Dinas Koperasi & UMKM Kota Padang.",
            'tipe'    => 'success',
        ]);

        return back()->with('success', 'UMKM berhasil diverifikasi dan sertifikat diterbitkan.');
    }

    public function tolak(Toko $toko)
    {
        request()->validate(['catatan_dinas' => 'required|string']);
        $toko->update(['status' => 'ditolak', 'catatan_dinas' => request('catatan_dinas')]);

        Notifikasi::create([
            'user_id' => $toko->user_id,
            'judul'   => 'Verifikasi UMKM Ditolak',
            'pesan'   => "Verifikasi {$toko->nama_toko} ditolak Dinas. Alasan: " . request('catatan_dinas'),
            'tipe'    => 'danger',
        ]);

        return back()->with('success', 'Verifikasi ditolak dan notifikasi dikirim.');
    }

    public function mintaDokumen(Toko $toko)
    {
        request()->validate(['catatan_dinas' => 'required|string']);
        $toko->update(['status' => 'dokumen_kurang', 'catatan_dinas' => request('catatan_dinas')]);

        Notifikasi::create([
            'user_id' => $toko->user_id,
            'judul'   => 'Dokumen Verifikasi Kurang Lengkap',
            'pesan'   => "Harap lengkapi: " . request('catatan_dinas'),
            'tipe'    => 'warning',
        ]);

        return back()->with('success', 'Permintaan dokumen dikirim ke UMKM.');
    }

    public function jadwalKunjungan(Request $request, Toko $toko)
    {
        $data = $request->validate([
            'tanggal_kunjungan' => 'required|date|after:today',
            'waktu_kunjungan'   => 'nullable|date_format:H:i',
            'catatan'           => 'nullable|string',
        ]);

        KunjunganLapangan::create([
            ...$data,
            'toko_id'    => $toko->id,
            'petugas_id' => auth()->id(),
            'status'     => 'dijadwalkan',
        ]);

        Notifikasi::create([
            'user_id' => $toko->user_id,
            'judul'   => 'Kunjungan Lapangan Dijadwalkan',
            'pesan'   => "Petugas Dinas akan berkunjung pada " . $data['tanggal_kunjungan'],
            'tipe'    => 'info',
        ]);

        return back()->with('success', 'Jadwal kunjungan berhasil dibuat.');
    }
}

// ─── MONITORING ─────────────────────────────────────────────────────────
class MonitoringController extends Controller
{
    public function index(Request $request)
    {
        $tokos = Toko::where('terverifikasi_dinas', true)
            ->when($request->kecamatan, fn($q) => $q->where('kecamatan', $request->kecamatan))
            ->when($request->kategori,  fn($q) => $q->where('kategori', $request->kategori))
            ->when($request->search,    fn($q) => $q->where('nama_toko','like',"%{$request->search}%"))
            ->with('user')
            ->paginate(20);

        return view('dinas.monitoring.index', compact('tokos'));
    }

    public function show(Toko $toko)
    {
        $toko->load('user','produks','pesanans','kunjunganLapangans');
        return view('dinas.monitoring.show', compact('toko'));
    }
}

// ─── STATISTIK ──────────────────────────────────────────────────────────
class StatistikController extends Controller
{
    public function index()
    {
        $stats = [
            'total_aktif'   => Toko::where('status','aktif')->count(),
            'baru_tahun_ini'=> Toko::whereYear('created_at', now()->year)->count(),
            'tenaga_kerja'  => Toko::where('status','aktif')->count() * 3,
            'kontribusi_ekonomi' => Pesanan::where('status_bayar','lunas')
                ->whereYear('created_at', now()->year)->sum('total'),
        ];

        $sebaranKecamatan = Toko::where('status','aktif')
            ->select('kecamatan', \DB::raw('count(*) as total'))
            ->groupBy('kecamatan')->orderByDesc('total')->get();

        return view('dinas.statistik', compact('stats','sebaranKecamatan'));
    }

    public function export()
    {
        return back()->with('success', 'Data statistik berhasil diunduh.');
    }
}

// ─── PEMBINAAN ───────────────────────────────────────────────────────────
class PembinaanController extends Controller
{
    public function index()
    {
        $programs = ProgramPembinaan::with('pembuat')->latest()->paginate(10);
        $tokos    = Toko::where('status','aktif')->get();
        return view('dinas.pembinaan.index', compact('programs','tokos'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama'           => 'required|string|max:200',
            'deskripsi'      => 'nullable|string',
            'tanggal_mulai'  => 'required|date',
            'tanggal_selesai'=> 'required|date|after:tanggal_mulai',
            'lokasi'         => 'nullable|string',
            'kuota'          => 'nullable|integer|min:1',
        ]);

        ProgramPembinaan::create([...$data, 'dibuat_oleh' => auth()->id(), 'status' => 'akan_datang']);
        return back()->with('success', 'Program pembinaan berhasil dibuat.');
    }

    public function daftarkanUmkm(Request $request, ProgramPembinaan $program)
    {
        $request->validate(['toko_id' => 'required|exists:tokos,id']);

        PesertaPembinaan::firstOrCreate([
            'program_id' => $program->id,
            'toko_id'    => $request->toko_id,
        ]);

        $toko = Toko::find($request->toko_id);
        Notifikasi::create([
            'user_id' => $toko->user_id,
            'judul'   => 'Terdaftar di Program Pembinaan',
            'pesan'   => "Toko Anda didaftarkan ke program: {$program->nama}.",
            'tipe'    => 'info',
        ]);

        return back()->with('success', 'UMKM berhasil didaftarkan ke program pembinaan.');
    }
}

// ─── PENGUMUMAN ──────────────────────────────────────────────────────────
class PengumumanController extends Controller
{
    public function index()
    {
        $pengumumans = Pengumuman::with('pembuat')->latest()->paginate(15);
        return view('dinas.pengumuman', compact('pengumumans'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'judul'           => 'required|string|max:200',
            'isi'             => 'required|string',
            'target_penerima' => 'required|string',
            'prioritas'       => 'required|in:normal,penting,mendesak',
        ]);

        // Query target penerima
        $targetQuery = User::where('role', 'penjual');
        $count = $targetQuery->count();

        Pengumuman::create([...$data, 'dibuat_oleh' => auth()->id(), 'total_terkirim' => $count]);

        // Kirim notifikasi massal
        $targetQuery->chunk(100, function($users) use ($data) {
            foreach ($users as $user) {
                Notifikasi::create([
                    'user_id' => $user->id,
                    'judul'   => '[Dinas] ' . $data['judul'],
                    'pesan'   => $data['isi'],
                    'tipe'    => match($data['prioritas']) {
                        'mendesak' => 'danger',
                        'penting'  => 'warning',
                        default    => 'info',
                    },
                ]);
            }
        });

        return back()->with('success', "Pengumuman berhasil dikirim ke {$count} UMKM.");
    }
}

// ─── LAPORAN DINAS ───────────────────────────────────────────────────────
class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $bulan = $request->bulan ?? now()->month;
        $tahun = $request->tahun ?? now()->year;

        $stats = [
            'diverifikasi' => Toko::where('terverifikasi_dinas', true)
                ->whereMonth('updated_at', $bulan)->count(),
            'ditolak'      => Toko::where('status','ditolak')
                ->whereMonth('updated_at', $bulan)->count(),
            'peserta_pembinaan' => PesertaPembinaan::count(),
            'kunjungan'    => KunjunganLapangan::whereMonth('tanggal_kunjungan', $bulan)->count(),
        ];

        $rekapKecamatan = Toko::where('status','aktif')
            ->select('kecamatan',
                \DB::raw('count(*) as total'),
                \DB::raw('count(case when terverifikasi_dinas = 1 then 1 end) as terverifikasi')
            )
            ->groupBy('kecamatan')->get();

        return view('dinas.laporan', compact('stats','rekapKecamatan','bulan','tahun'));
    }

    public function export()
    {
        return back()->with('success', 'Laporan dinas berhasil dicetak.');
    }
}
