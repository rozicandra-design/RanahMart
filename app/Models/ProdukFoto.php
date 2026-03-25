<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProdukFoto extends Model
{
    protected $fillable = [
        'produk_id',
        'path',
    ];

    // Relasi ke produk
    public function produk()
    {
        return $this->belongsTo(Produk::class);
    }
}
