<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{
    protected $fillable = [
        'toko_id', 'kode', 'jenis', 'nilai', 'maks_potongan',
        'min_belanja', 'kuota', 'berlaku_mulai', 'berlaku_hingga', 'aktif', 'global',
    ];

    protected $casts = [
        'aktif'          => 'boolean',
        'global'         => 'boolean',
        'berlaku_mulai'  => 'date',
        'berlaku_hingga' => 'date',
    ];

    public function toko()  { return $this->belongsTo(Toko::class); }
    public function users() { return $this->hasMany(VoucherUser::class); }

    public function isValid(): bool
    {
        return $this->aktif
            && now()->between($this->berlaku_mulai, $this->berlaku_hingga)
            && (!$this->kuota || $this->terpakai < $this->kuota);
    }
}