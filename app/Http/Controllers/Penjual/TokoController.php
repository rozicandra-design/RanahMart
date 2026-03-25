<?php
namespace App\Http\Controllers\Penjual;

use App\Http\Controllers\Controller;
use App\Models\Toko;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class TokoController extends Controller
{
    public function edit()
    {
        $toko = auth()->user()->toko;
        return view('penjual.toko.edit', compact('toko'));
    }

    public function update(Request $request)
    {
        $toko = auth()->user()->toko;

        $data = $request->validate([
            'nama_toko'       => 'required|string|max:150',
            'deskripsi'       => 'nullable|string',
            'kategori'        => 'required|string',
            'kecamatan'       => 'required|string',
            'alamat_lengkap'  => 'nullable|string',
            'no_hp'           => 'nullable|string|max:20',
            'jam_operasional' => 'nullable|string',
            'logo'            => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'banner'          => 'nullable|image|mimes:jpg,jpeg,png|max:5120',
        ]);

        if ($request->hasFile('logo')) {
            if ($toko?->logo) Storage::disk('public')->delete($toko->logo);
            $data['logo'] = $request->file('logo')->store("toko/logo/" . ($toko?->id ?? auth()->id()), 'public');
        }

        if ($request->hasFile('banner')) {
            if ($toko?->banner) Storage::disk('public')->delete($toko->banner);
            $data['banner'] = $request->file('banner')->store("toko/banner/" . ($toko?->id ?? auth()->id()), 'public');
        }

        if ($toko) {
            $toko->update($data);
        } else {
            Toko::create([
                ...$data,
                'user_id' => auth()->id(),
                'status'  => 'menunggu_dinas',
                'slug'    => Str::slug($data['nama_toko']) . '-' . Str::random(5),
            ]);
        }

        return back()->with('success', 'Profil toko berhasil diperbarui.');
    }

    public function dokumen()
    {
        $toko = auth()->user()->toko;
        if (!$toko) return redirect()->route('penjual.toko.edit')->with('error', 'Toko belum dibuat.');
        return view('penjual.toko-dokumen', compact('toko'));
    }

    public function uploadDokumen(Request $request)
    {
        $request->validate([
            'nib'                => 'nullable|string|max:50',
            'no_sku'             => 'nullable|string|max:50',
            'bank'               => 'nullable|string|max:50',
            'no_rekening'        => 'nullable|string|max:50',
            'atas_nama_rekening' => 'nullable|string|max:100',
            'foto_ktp'           => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'foto_usaha'         => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'foto_produk_sample' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $toko = auth()->user()->toko;
        $data = $request->only(['nib', 'no_sku', 'bank', 'no_rekening', 'atas_nama_rekening']);

        foreach (['foto_ktp', 'foto_usaha', 'foto_produk_sample'] as $field) {
            if ($request->hasFile($field)) {
                if ($toko->$field) Storage::disk('public')->delete($toko->$field);
                $data[$field] = $request->file($field)->store('dokumen-toko', 'public');
            }
        }

        if (!in_array($toko->status, ['aktif', 'ditolak'])) {
            $data['status'] = 'menunggu_dinas';
        }

        $toko->update($data);

        return back()->with('success', 'Dokumen berhasil disimpan' .
            ($data['status'] ?? null === 'menunggu_dinas' ? ' dan diajukan ke Dinas.' : '.'));
    }

    public function sertifikat()
    {
        $toko = auth()->user()->toko;

        if (!$toko || !$toko->terverifikasi_dinas) {
            return redirect()->route('penjual.toko.dokumen')
                ->with('error', 'Sertifikat belum tersedia.');
        }

        $html = view('dinas.verifikasi.sertifikat-pdf', compact('toko'))->render();

        $pdf = \Spatie\Browsershot\Browsershot::html($html)
            ->noSandbox()
            ->setOption('printBackground', true)
            ->setOption('landscape', false)
            ->paperSize(210, 297, 'mm')
            ->margins(0, 0, 0, 0)
            ->pdf();

        return response($pdf)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="sertifikat-' . ($toko->slug ?? $toko->id) . '.pdf"');
    }
}