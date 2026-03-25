<?php

namespace App\Http\Controllers\Dinas;

use App\Http\Controllers\Controller;
use App\Models\Toko;

class MonitoringController extends Controller
{
    public function index()
    {
        $tokos = Toko::where('status', 'aktif')->latest()->paginate(15);
        return view('dinas.monitoring.index', compact('tokos'));
    }

    public function show($id)
    {
        $toko = Toko::findOrFail($id);
        return view('dinas.monitoring.show', compact('toko'));
    }
}