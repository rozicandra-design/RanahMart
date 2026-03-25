<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Retur;

class ReturController extends Controller
{
    public function index()
    {
        $returs = Retur::latest()->paginate(20);
        return view('admin.retur.index', compact('returs'));
    }

    public function show(Retur $retur)
    {
        return view('admin.retur.show', compact('retur'));
    }

    public function setujui(Retur $retur) { return back()->with('success', 'Retur disetujui.'); }
    public function tolak(Retur $retur)   { return back()->with('success', 'Retur ditolak.'); }
}