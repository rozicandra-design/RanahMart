<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Poin extends Model
{
    protected $fillable = ['user_id', 'tipe', 'jumlah', 'keterangan', 'pesanan_id'];

    public function user()    { return $this->belongsTo(User::class); }
    public function pesanan() { return $this->belongsTo(Pesanan::class); }
}