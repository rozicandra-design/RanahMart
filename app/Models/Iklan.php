<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Iklan extends Model
{
    protected $table = 'iklans'; // Laravel will assume this by default
    protected $fillable = ['title', 'image', /* your columns */];
}