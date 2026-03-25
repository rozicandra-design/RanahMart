<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FotoUlasan extends Model
{
    protected $fillable = ['ulasan_id', 'path'];

    public function ulasan() { return $this->belongsTo(Ulasan::class); }
}
