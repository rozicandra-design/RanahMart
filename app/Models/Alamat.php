<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Alamat extends Model
{
    protected $fillable = [
        'user_id', 'label', 'nama_penerima', 'no_hp',
        'alamat_lengkap', 'kelurahan', 'kecamatan',
        'kota', 'provinsi', 'kode_pos', 'is_utama',
    ];

    protected $casts = ['is_utama' => 'boolean'];

    public function user()     { return $this->belongsTo(User::class); }
    public function pesanans() { return $this->hasMany(Pesanan::class); }
}