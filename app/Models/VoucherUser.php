<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VoucherUser extends Model
{
    protected $fillable = ['voucher_id', 'user_id', 'pesanan_id', 'digunakan_at'];
    protected $casts = ['digunakan_at' => 'datetime'];

    public function voucher() { return $this->belongsTo(Voucher::class); }
    public function user()    { return $this->belongsTo(User::class); }
    public function pesanan() { return $this->belongsTo(Pesanan::class); }
}
