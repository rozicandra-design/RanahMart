<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Retur extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'pesanan_id',
        'user_id',
        'toko_id',
        'alasan',
        'status',
        'foto',
        'catatan_admin',
    ];

    public function pesanan() { return $this->belongsTo(Pesanan::class); }
    public function pembeli() { return $this->belongsTo(User::class, 'user_id'); }
    public function toko()    { return $this->belongsTo(Toko::class); }
}