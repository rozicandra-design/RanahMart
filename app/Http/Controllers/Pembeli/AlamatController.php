<?php

namespace App\Http\Controllers\Pembeli;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AlamatController extends Controller
{
    public function index()
    {
        $alamats = auth()->user()->alamats()->latest()->get();
        return view('pembeli.alamat', compact('alamats'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'label'          => 'required|string|max:50',
            'nama_penerima'  => 'required|string|max:100',
            'no_hp'          => 'required|string|max:20',
            'alamat_lengkap' => 'required|string',
            'kelurahan'      => 'nullable|string|max:100',
            'kecamatan'      => 'required|string|max:100',
            'kode_pos'       => 'nullable|string|max:10',
        ]);

        $validated['kota']     = 'Kota Padang';
        $validated['provinsi'] = 'Sumatera Barat';
        $validated['is_utama'] = auth()->user()->alamats()->count() === 0;

        auth()->user()->alamats()->create($validated);

        return back()->with('success', 'Alamat berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $alamat  = auth()->user()->alamats()->findOrFail($id);
        $alamats = auth()->user()->alamats()->latest()->get();
        return view('pembeli.alamat', compact('alamats', 'alamat'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'label'          => 'required|string|max:50',
            'nama_penerima'  => 'required|string|max:100',
            'no_hp'          => 'required|string|max:20',
            'alamat_lengkap' => 'required|string',
            'kelurahan'      => 'nullable|string|max:100',
            'kecamatan'      => 'required|string|max:100',
            'kode_pos'       => 'nullable|string|max:10',
        ]);

        $validated['kota']     = 'Kota Padang';
        $validated['provinsi'] = 'Sumatera Barat';

        $alamat = auth()->user()->alamats()->findOrFail($id);
        $alamat->update($validated);

        return back()->with('success', 'Alamat berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $alamat = auth()->user()->alamats()->findOrFail($id);
        $alamat->delete();
        return back()->with('success', 'Alamat berhasil dihapus.');
    }

    public function setUtama($id)
    {
        auth()->user()->alamats()->update(['is_utama' => false]);
        auth()->user()->alamats()->findOrFail($id)->update(['is_utama' => true]);
        return back()->with('success', 'Alamat utama berhasil diubah.');
    }
}