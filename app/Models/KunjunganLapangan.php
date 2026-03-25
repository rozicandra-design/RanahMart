<?php
// app/Models/KunjunganLapangan.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class KunjunganLapangan extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'toko_id',
        'user_id',       // dinas officer who visited
        'tanggal_kunjungan',
        'catatan',
        'hasil_kunjungan',
        'status',
        // add your actual columns here
    ];

    protected $casts = [
        'tanggal_kunjungan' => 'date',
    ];

    // Relations
    public function toko() { return $this->belongsTo(Toko::class); }
    public function user() { return $this->belongsTo(User::class); }
}