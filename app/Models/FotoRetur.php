<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FotoRetur extends Model
{
    protected $fillable = ['retur_id', 'path'];

    public function retur() { return $this->belongsTo(Retur::class); }
}
