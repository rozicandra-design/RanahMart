<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Iklan;

class IklanController extends Controller
{
    public function index()
    {
        $query  = Iklan::query();
        if (request('status')) {
            $query->where('status', request('status'));
        }

        $iklans = $query->latest()->paginate(20);

        $stats = [
            'menunggu'        => Iklan::whereIn('status', ['menunggu', 'ditinjau'])->count(),
            'aktif'           => Iklan::where('status', 'aktif')->count(),
            'pendapatan'      => Iklan::where('status', 'aktif')
                                    ->whereMonth('created_at', now()->month)
                                    ->sum('harga'),
            'total_pengiklan' => Iklan::distinct('toko_id')->count('toko_id'),
        ];

        return view('admin.iklan.index', compact('iklans', 'stats'));
    }

    public function show(Iklan $iklan)
    {
        return view('admin.iklan.show', compact('iklan'));
    }

    public function setujui(Iklan $iklan)  { return back()->with('success', 'Iklan disetujui.'); }
    public function tolak(Iklan $iklan)    { return back()->with('success', 'Iklan ditolak.'); }
    public function revisi(Iklan $iklan)   { return back()->with('success', 'Revisi dikirim.'); }
    public function hentikan(Iklan $iklan) { return back()->with('success', 'Iklan dihentikan.'); }
    public function destroy(Iklan $iklan)  { $iklan->delete(); return redirect()->route('admin.iklan.index'); }
}