<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Produk extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'toko_id','nama','slug','deskripsi','kategori','sub_kategori',
        'harga','harga_coret','stok','berat','sku','status',
        'catatan_review','is_featured',
    ];

    protected $casts = ['is_featured' => 'boolean'];

    public function getDiskonAttribute(): ?int {
        if (!$this->harga_coret || $this->harga_coret <= $this->harga) return null;
        return (int) round((1 - $this->harga / $this->harga_coret) * 100);
    }

    public function toko()        { return $this->belongsTo(Toko::class); }
    public function fotos()       { return $this->hasMany(FotoProduk::class); }
    public function fotoUtama()   { return $this->hasOne(FotoProduk::class)->where('is_utama', true); }
    public function ulasans()     { return $this->hasMany(Ulasan::class); }
    public function wishlists()   { return $this->hasMany(Wishlist::class); }
    public function keranjangs()  { return $this->hasMany(Keranjang::class); }
    public function itemPesanans(){ return $this->hasMany(ItemPesanan::class); }

    public function scopeAktif($q)    { return $q->where('status', 'aktif'); }
    public function scopePending($q)  { return $q->where('status', 'pending'); }
    public function scopeFeatured($q) { return $q->where('is_featured', true); }
}

class FotoProduk extends Model
{
    protected $fillable = ['produk_id','path','is_utama','urutan'];
    protected $casts = ['is_utama' => 'boolean'];
    public function produk() { return $this->belongsTo(Produk::class); }
}

class Pesanan extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'kode_pesanan','pembeli_id','toko_id','alamat_id',
        'subtotal','ongkir','diskon_voucher','diskon_poin','total',
        'metode_bayar','status_bayar','status_pesanan',
        'jasa_kirim','no_resi','komisi_platform','catatan',
    ];

    protected $casts = [
        'dibayar_at' => 'datetime',
        'dikonfirmasi_at' => 'datetime',
        'dikirim_at' => 'datetime',
        'selesai_at' => 'datetime',
    ];

    protected static function boot() {
        parent::boot();
        static::creating(function ($m) {
            $m->kode_pesanan = 'ORD-' . strtoupper(uniqid());
        });
    }

    public function pembeli()  { return $this->belongsTo(User::class, 'pembeli_id'); }
    public function toko()     { return $this->belongsTo(Toko::class); }
    public function alamat()   { return $this->belongsTo(Alamat::class); }
    public function items()    { return $this->hasMany(ItemPesanan::class); }
    public function ulasans()  { return $this->hasMany(Ulasan::class); }
    public function retur()    { return $this->hasOne(Retur::class); }
    public function poin()     { return $this->hasOne(Poin::class); }
    public function voucherUser(){ return $this->hasOne(VoucherUser::class); }

    public function scopeMenunggu($q)     { return $q->where('status_pesanan', 'menunggu'); }
    public function scopeDikirim($q)      { return $q->where('status_pesanan', 'dikirim'); }
    public function scopeSelesai($q)      { return $q->where('status_pesanan', 'selesai'); }
    public function scopeDibatalkan($q)   { return $q->where('status_pesanan', 'dibatalkan'); }
}

class ItemPesanan extends Model
{
    protected $fillable = ['pesanan_id','produk_id','nama_produk','harga_satuan','jumlah','subtotal','sudah_diulas'];
    protected $casts = ['sudah_diulas' => 'boolean'];
    public function pesanan() { return $this->belongsTo(Pesanan::class); }
    public function produk()  { return $this->belongsTo(Produk::class); }
    public function ulasan()  { return $this->hasOne(Ulasan::class, 'item_pesanan_id'); }
}

class Keranjang extends Model
{
    protected $fillable = ['user_id','produk_id','jumlah','dipilih'];
    protected $casts = ['dipilih' => 'boolean'];
    public function user()   { return $this->belongsTo(User::class); }
    public function produk() { return $this->belongsTo(Produk::class); }
    public function getSubtotalAttribute(): float { return $this->jumlah * $this->produk->harga; }
}

class Alamat extends Model
{
    protected $fillable = ['user_id','label','nama_penerima','no_hp','alamat_lengkap','kelurahan','kecamatan','kota','provinsi','kode_pos','is_utama'];
    protected $casts = ['is_utama' => 'boolean'];
    public function user() { return $this->belongsTo(User::class); }
    public function pesanans() { return $this->hasMany(Pesanan::class); }
}

class Ulasan extends Model
{
    protected $fillable = ['item_pesanan_id','user_id','produk_id','toko_id','rating','komentar','balasan','dibalas_at'];
    protected $casts = ['dibalas_at' => 'datetime'];
    public function user()        { return $this->belongsTo(User::class); }
    public function produk()      { return $this->belongsTo(Produk::class); }
    public function toko()        { return $this->belongsTo(Toko::class); }
    public function itemPesanan() { return $this->belongsTo(ItemPesanan::class); }
    public function fotos()       { return $this->hasMany(FotoUlasan::class); }
}

class FotoUlasan extends Model
{
    protected $fillable = ['ulasan_id','path'];
    public function ulasan() { return $this->belongsTo(Ulasan::class); }
}

class Iklan extends Model
{
    protected $fillable = [
        'toko_id','paket','biaya','posisi','judul','sub_judul','teks_cta',
        'banner','warna_tema','tanggal_mulai','tanggal_selesai',
        'catatan_pengaju','catatan_admin','status',
    ];

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
        'disetujui_at' => 'datetime',
    ];

    public function toko() { return $this->belongsTo(Toko::class); }
    public function disetujuiOleh() { return $this->belongsTo(User::class, 'disetujui_oleh'); }

    public function getCtrAttribute(): float {
        if (!$this->total_tayangan) return 0;
        return round(($this->total_klik / $this->total_tayangan) * 100, 1);
    }

    public function scopeAktif($q) {
        return $q->where('status', 'aktif')
            ->where('tanggal_mulai', '<=', now())
            ->where('tanggal_selesai', '>=', now());
    }
    public function scopeMenunggu($q) { return $q->where('status', 'menunggu'); }
}

class Retur extends Model
{
    protected $fillable = ['kode_retur','pesanan_id','pembeli_id','toko_id','alasan','keterangan','nilai_retur','status','keputusan_admin'];
    protected static function boot() {
        parent::boot();
        static::creating(fn($m) => $m->kode_retur = 'RET-' . strtoupper(uniqid()));
    }
    public function pesanan() { return $this->belongsTo(Pesanan::class); }
    public function pembeli() { return $this->belongsTo(User::class, 'pembeli_id'); }
    public function toko()    { return $this->belongsTo(Toko::class); }
    public function fotos()   { return $this->hasMany(FotoRetur::class); }
}

class FotoRetur extends Model
{
    protected $fillable = ['retur_id','path'];
    public function retur() { return $this->belongsTo(Retur::class); }
}

class Voucher extends Model
{
    protected $fillable = ['toko_id','kode','jenis','nilai','maks_potongan','min_belanja','kuota','berlaku_mulai','berlaku_hingga','aktif','global'];
    protected $casts = ['aktif' => 'boolean', 'global' => 'boolean', 'berlaku_mulai' => 'date', 'berlaku_hingga' => 'date'];
    public function toko()   { return $this->belongsTo(Toko::class); }
    public function users()  { return $this->hasMany(VoucherUser::class); }
    public function isValid(): bool {
        return $this->aktif
            && now()->between($this->berlaku_mulai, $this->berlaku_hingga)
            && (!$this->kuota || $this->terpakai < $this->kuota);
    }
}

class VoucherUser extends Model
{
    protected $fillable = ['voucher_id','user_id','pesanan_id','digunakan_at'];
    protected $casts = ['digunakan_at' => 'datetime'];
    public function voucher() { return $this->belongsTo(Voucher::class); }
    public function user()    { return $this->belongsTo(User::class); }
    public function pesanan() { return $this->belongsTo(Pesanan::class); }
}

class Poin extends Model
{
    protected $fillable = ['user_id','tipe','jumlah','keterangan','pesanan_id'];
    public function user()    { return $this->belongsTo(User::class); }
    public function pesanan() { return $this->belongsTo(Pesanan::class); }
}

class Notifikasi extends Model
{
    protected $fillable = ['user_id','judul','pesan','tipe','url','sudah_dibaca'];
    protected $casts = ['sudah_dibaca' => 'boolean'];
    public function user() { return $this->belongsTo(User::class); }
}

class Wishlist extends Model
{
    protected $fillable = ['user_id','produk_id'];
    public function user()   { return $this->belongsTo(User::class); }
    public function produk() { return $this->belongsTo(Produk::class); }
}

class Pencairan extends Model
{
    protected $fillable = ['toko_id','jumlah','bank','no_rekening','atas_nama','status','bukti_transfer','dicairkan_at'];
    protected $casts = ['dicairkan_at' => 'datetime'];
    public function toko() { return $this->belongsTo(Toko::class); }
}

class ProgramPembinaan extends Model
{
    protected $fillable = ['dibuat_oleh','nama','deskripsi','tanggal_mulai','tanggal_selesai','lokasi','kuota','status'];
    protected $casts = ['tanggal_mulai' => 'date', 'tanggal_selesai' => 'date'];
    public function pembuat()  { return $this->belongsTo(User::class, 'dibuat_oleh'); }
    public function pesertas() { return $this->hasMany(PesertaPembinaan::class, 'program_id'); }
}

class PesertaPembinaan extends Model
{
    protected $fillable = ['program_id','toko_id'];
    public function program() { return $this->belongsTo(ProgramPembinaan::class, 'program_id'); }
    public function toko()    { return $this->belongsTo(Toko::class); }
}

class Pengumuman extends Model
{
    protected $fillable = ['dibuat_oleh','judul','isi','target_penerima','prioritas','total_terkirim'];
    public function pembuat() { return $this->belongsTo(User::class, 'dibuat_oleh'); }
}

class KunjunganLapangan extends Model
{
    protected $fillable = ['toko_id','petugas_id','tanggal_kunjungan','waktu_kunjungan','catatan','status','hasil_kunjungan'];
    protected $casts = ['tanggal_kunjungan' => 'date'];
    public function toko()    { return $this->belongsTo(Toko::class); }
    public function petugas() { return $this->belongsTo(User::class, 'petugas_id'); }
}
