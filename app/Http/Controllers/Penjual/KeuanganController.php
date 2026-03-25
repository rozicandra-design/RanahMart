<?php

namespace App\Http\Controllers\Penjual;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class KeuanganController extends Controller
{
    public function index()
    {
        $toko = auth()->user()->toko;

        return view('penjual.keuangan.index', compact('toko'));
    }
}