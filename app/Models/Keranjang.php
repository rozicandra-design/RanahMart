<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Keranjang extends Model
{
    protected $fillable = ['user_id', 'produk_id', 'jumlah', 'dipilih'];

    protected $casts = ['dipilih' => 'boolean'];

    public function user()   { return $this->belongsTo(User::class); }
    public function produk() { return $this->belongsTo(Produk::class); }

    public function getSubtotalAttribute(): float
    {
        return $this->jumlah * $this->produk->harga;
    }
}