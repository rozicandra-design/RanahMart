<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ulasan extends Model
{
    protected $fillable = [
        'produk_id',
        'user_id',
        'pesanan_id',
        'rating',
        'komentar',
        'balasan',
        'dibalas',
        'dibalas_at',
    ];

    protected $casts = [
        'dibalas'    => 'boolean',
        'dibalas_at' => 'datetime',
        'rating'     => 'integer',
    ];

    public function produk()
    {
        return $this->belongsTo(Produk::class);
    }

    public function pembeli()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function pesanan()
    {
        return $this->belongsTo(Pesanan::class);
    }
}