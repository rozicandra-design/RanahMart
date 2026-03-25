<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Toko;
use Illuminate\Http\Request;

class UmkmController extends Controller
{
    public function index()
{
    $tokos        = Toko::latest()->paginate(20);
    $pendingCount = Toko::whereIn('status', ['pending', 'menunggu_dinas'])->count();
    $totalCount   = Toko::count();
    $aktifCount   = Toko::where('status', 'aktif')->count();
    $ditolakCount = Toko::where('status', 'ditolak')->count();

    return view('admin.umkm.index', compact(
        'tokos',
        'pendingCount',
        'totalCount',
        'aktifCount',
        'ditolakCount'
    ));
}

    public function show(Toko $toko)
    {
        return view('admin.umkm.show', compact('toko'));
    }

    public function setujui(Toko $toko)
    {
        $toko->update(['status' => 'aktif']);
        return back()->with('success', 'UMKM disetujui.');
    }

    public function tolak(Toko $toko)
    {
        $toko->update(['status' => 'ditolak']);
        return back()->with('success', 'UMKM ditolak.');
    }

    public function mintaDokumen(Toko $toko)
    {
        return back()->with('success', 'Permintaan dokumen dikirim.');
    }

    public function teruskanDinas(Toko $toko)
    {
        $toko->update(['status' => 'menunggu_dinas']);
        return back()->with('success', 'Diteruskan ke dinas.');
    }

    public function nonaktif(Toko $toko)
    {
        $toko->update(['status' => 'nonaktif']);
        return back()->with('success', 'UMKM dinonaktifkan.');
    }
}