<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pesanan extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'toko_id',
        'user_id',
        'kode_pesanan',
        'status_pesanan',
        'status_bayar',
        'total',
        'ongkos_kirim',
        'metode_pembayaran',
        'alamat_pengiriman',
        'kurir',
        'no_resi',
        'catatan',
        'dibayar_at',
        'dikirim_at',
        'selesai_at',
    ];

    protected $casts = [
        'total'        => 'decimal:2',
        'ongkos_kirim' => 'decimal:2',
        'dibayar_at'   => 'datetime',
        'dikirim_at'   => 'datetime',
        'selesai_at'   => 'datetime',
    ];

    // ── Relasi ──────────────────────────────────────

    public function pembeli()
{
    return $this->belongsTo(User::class, 'user_id');
}

    public function toko()
    {
        return $this->belongsTo(Toko::class);
    }

    public function items()
    {
        return $this->hasMany(PesananItem::class);
    }

    public function ulasan()
    {
        return $this->hasMany(Ulasan::class);
    }

    public function retur()
    {
        return $this->hasOne(Retur::class);
    }

    // ── Helper methods ───────────────────────────────

    public function sudahDibayar(): bool
    {
        return $this->status_bayar === 'lunas';
    }

    public function bisaDiretur(): bool
    {
        return $this->status_pesanan === 'selesai'
            && is_null($this->retur);
    }

    public function bisaDiulasan(): bool
    {
        return $this->status_pesanan === 'selesai';
    }

    public function labelStatus(): string
    {
        return match($this->status_pesanan) {
            'menunggu'       => 'Menunggu Konfirmasi',
            'dikonfirmasi'   => 'Dikonfirmasi',
            'diproses'       => 'Diproses',
            'dikirim'        => 'Dikirim',
            'selesai'        => 'Selesai',
            'dibatalkan'     => 'Dibatalkan',
            default          => ucfirst($this->status_pesanan),
        };
    }

    public function warnaBadgeStatus(): string
    {
        return match($this->status_pesanan) {
            'menunggu'     => 'bg-amber-100 text-amber-700',
            'dikonfirmasi' => 'bg-blue-100 text-blue-700',
            'diproses'     => 'bg-blue-100 text-blue-700',
            'dikirim'      => 'bg-indigo-100 text-indigo-700',
            'selesai'      => 'bg-green-100 text-green-700',
            'dibatalkan'   => 'bg-red-100 text-red-700',
            default        => 'bg-gray-100 text-gray-700',
        };
    }
}