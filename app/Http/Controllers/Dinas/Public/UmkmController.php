<?php
namespace App\Http\Controllers\Public;
use App\Http\Controllers\Controller;
use App\Models\Toko;
use Illuminate\Http\Request;

class UmkmController extends Controller
{
    public function index(Request $request)
    {
        $tokos = Toko::aktif()
            ->when($request->kecamatan, fn($q) => $q->where('kecamatan', $request->kecamatan))
            ->when($request->kategori,  fn($q) => $q->where('kategori', $request->kategori))
            ->when($request->q,         fn($q) => $q->where('nama_toko','like',"%{$request->q}%"))
            ->with('user')->paginate(20);
        return view('public.toko.index', compact('tokos'));
    }

    public function show(Toko $toko)
    {
        abort_if($toko->status !== 'aktif', 404);
        $toko->load('user','produksAktif.fotoUtama','ulasans.user');
        return view('public.toko.show', compact('toko'));
    }
}
