<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FotoProduk extends Model
{
    protected $fillable = ['produk_id', 'path', 'is_utama', 'urutan'];
    protected $casts = ['is_utama' => 'boolean'];

    public function produk() { return $this->belongsTo(Produk::class); }
}
