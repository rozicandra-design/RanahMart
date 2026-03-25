<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Produk extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'toko_id',
        'user_id',
        'nama',
        'deskripsi',
        'harga',
        'harga_coret',
        'stok',
        'kategori',
        'sub_kategori',
        'berat',
        'sku',
        'status',
        'foto',
        'slug',
        'catatan_review',
        'total_terjual',
    ];

    protected $casts = [
        'harga'       => 'decimal:2',
        'harga_coret' => 'decimal:2',
        'stok'        => 'integer',
        'berat'       => 'integer',
    ];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    // ── ACCESSOR ───────────────────────────────────

    public function getNamaKategoriAttribute()
    {
        return match($this->kategori) {
            'makanan_minuman' => 'Makanan & Minuman',
            'elektronik' => 'Elektronik',
            'fashion' => 'Fashion',
            default => ucfirst(str_replace('_', ' ', $this->kategori)),
        };
    }

    // ── RELASI ─────────────────────────────────────

    public function toko()
    {
        return $this->belongsTo(Toko::class);
    }

    // 🔥 TAMBAHAN RELASI FOTO
    public function fotos()
    {
        return $this->hasMany(ProdukFoto::class);
    }

    // 🔥 TAMBAHAN RELASI ULASAN
    public function ulasans()
    {
        return $this->hasMany(Ulasan::class);
    }
}