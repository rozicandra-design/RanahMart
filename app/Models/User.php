<?php
// app/Models/User.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'nama_depan', 
        'nama_belakang', 
        'email', 
        'no_hp',
        'password', 
        'role', 
        'status', 
        'foto_profil',
        'tanggal_lahir', 
        'jenis_kelamin', 
        'kota', 
        'kecamatan',
        'notif_pesanan_dikonfirmasi',
        'notif_pesanan_dikirim',
        'notif_pesanan_selesai',
        'notif_promo',
        'notif_flash_sale',
        'privasi_tampilkan_nama',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
    'email_verified_at'          => 'datetime',
    'password'                   => 'hashed',
    'tanggal_lahir'              => 'date',
    'notif_pesanan_dikonfirmasi' => 'boolean',
    'notif_pesanan_dikirim'      => 'boolean',
    'notif_pesanan_selesai'      => 'boolean',
    'notif_promo'                => 'boolean',
    'notif_flash_sale'           => 'boolean',
    'privasi_tampilkan_nama'     => 'boolean',
];

    // ─── Accessors ───
    public function getNamaLengkapAttribute(): string
    {
        if ($this->nama_depan) {
            return trim("{$this->nama_depan} {$this->nama_belakang}");
        }
        return $this->name ?? '';
    }

    public function getTotalPoinAttribute(): int
    {
        return $this->poins()->sum('jumlah');
    }

    // ─── Role Checks ───
    public function isAdmin(): bool    { return $this->role === 'admin'; }
    public function isPenjual(): bool  { return $this->role === 'penjual'; }
    public function isPembeli(): bool  { return $this->role === 'pembeli'; }
    public function isDinas(): bool    { return $this->role === 'dinas'; }
    public function isAktif(): bool    { return $this->status === 'aktif'; }

    // ─── Relations ───
    public function toko()        { return $this->hasOne(Toko::class); }
    public function pesanans()    { return $this->hasMany(Pesanan::class, 'user_id'); }
    public function keranjangs()  { return $this->hasMany(Keranjang::class); }
    public function wishlists()   { return $this->hasMany(Wishlist::class); }
    public function alamats()     { return $this->hasMany(Alamat::class); }
    public function ulasans()     { return $this->hasMany(Ulasan::class); }
    public function returs()   { return $this->hasMany(Retur::class, 'user_id'); }
    public function poins()       { return $this->hasMany(Poin::class); }
    public function notifikasis() { return $this->hasMany(Notifikasi::class); }
    public function voucherUsers(){ return $this->hasMany(VoucherUser::class); }

    public function alamatUtama()
    {
        return $this->hasOne(Alamat::class)->where('is_utama', true)->latest();
    }
}
