<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ItemPesanan extends Model
{
    protected $fillable = [
        'pesanan_id',
        'produk_id',
        'nama_produk',
        'harga_satuan',
        'jumlah',
        'subtotal',
    ];

    public function pesanan()
    {
        return $this->belongsTo(Pesanan::class);
    }

    public function produk()
    {
        return $this->belongsTo(Produk::class);
    }

    public function ulasan()
    {
        return $this->hasOne(Ulasan::class, 'pesanan_id', 'pesanan_id')
                    ->where('produk_id', $this->produk_id);
    }
}