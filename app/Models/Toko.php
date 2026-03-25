<?php
// app/Models/Toko.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Toko extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
    'user_id',
    'nama_toko',
    'slug',
    'deskripsi',
    'kategori',
    'kecamatan',
    'alamat_lengkap',
    'no_hp',
    'jam_operasional',
    'logo',
    'banner',
    'status',
    'terverifikasi_dinas',
    'tanggal_sertifikat', 
    'kadaluarsa_sertifikat',
    'nib', 
    'no_sku', 
    'foto_ktp', 
    'foto_usaha', 
    'foto_produk_sample',
    'catatan_dinas',
    'bank', 
    'no_rekening', 
    'atas_nama_rekening',
    'no_sertifikat', 
    'nama_kepala_dinas', 
    'jabatan_kepala_dinas',
];

    protected $casts = [
        'terverifikasi_dinas' => 'boolean',
        'toko_aktif' => 'boolean',
        'mode_liburan' => 'boolean',
        'tanggal_sertifikat' => 'date',
        'kadaluarsa_sertifikat' => 'date',
    ];

    // Status helpers
    public function isAktif(): bool    { return $this->status === 'aktif'; }
    public function isPending(): bool  { return $this->status === 'pending'; }
    public function getKategoriFriendlyAttribute(): string {
        return match($this->kategori) {
            'makanan_minuman' => 'Makanan & Minuman',
            'fashion' => 'Fashion & Pakaian',
            'kerajinan' => 'Kerajinan Tangan',
            'herbal_kesehatan' => 'Herbal & Kesehatan',
            'seni_budaya' => 'Seni & Budaya',
            'kecantikan' => 'Kecantikan',
            default => 'Lainnya',
        };
    }

    // Relations
    public function user()      { return $this->belongsTo(User::class); }
    public function produks()   { return $this->hasMany(Produk::class); }
    public function pesanans()  { return $this->hasMany(Pesanan::class); }
    public function iklans()    { return $this->hasMany(Iklan::class); }
    public function ulasans()   { return $this->hasMany(Ulasan::class); }
    public function vouchers()  { return $this->hasMany(Voucher::class); }
    public function pencairans(){ return $this->hasMany(Pencairan::class); }
    public function returs()    { return $this->hasMany(Retur::class); }
    public function pesertaPembinaans() { return $this->hasMany(PesertaPembinaan::class); }
    public function kunjunganLapangans(){ return $this->hasMany(KunjunganLapangan::class); }

    public function produksAktif()
    {
        return $this->hasMany(Produk::class)->where('status', 'aktif');
    }

    public function iklanAktif()
    {
        return $this->hasMany(Iklan::class)
            ->where('status', 'aktif')
            ->where('tanggal_mulai', '<=', now())
            ->where('tanggal_selesai', '>=', now());
    }

    // Scopes
    public function scopeAktif($q)       { return $q->where('status', 'aktif'); }
    public function scopePending($q)     { return $q->where('status', 'pending'); }
    public function scopeMenungguDinas($q){ return $q->where('status', 'menunggu_dinas'); }
    public function scopeByKecamatan($q, $kec){ return $q->where('kecamatan', $kec); }
    public function scopeByKategori($q, $kat) { return $q->where('kategori', $kat); }
}
