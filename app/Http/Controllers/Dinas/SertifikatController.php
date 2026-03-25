<?php

namespace App\Http\Controllers\Dinas;

use App\Http\Controllers\Controller;
use App\Models\Toko;
use App\Models\Notifikasi;
use Illuminate\Http\Request;
use Spatie\Browsershot\Browsershot;

class SertifikatController extends Controller
{
    public function preview($id)
    {
        $toko = Toko::with('user')->findOrFail($id);

        if (!$toko->no_sertifikat) {
            $toko->no_sertifikat = 'SK/UMKM/' . date('Y') . '/' . str_pad($toko->id, 4, '0', STR_PAD_LEFT);
            $toko->save(); // ✅ Simpan ke DB
        }

        return view('dinas.verifikasi.sertifikat', compact('toko'));
    }

    public function simpan(Request $request, $id)
    {
        $request->validate([
            'no_sertifikat'         => 'required|string|max:100',
            'nama_kepala_dinas'     => 'required|string|max:150',
            'jabatan_kepala_dinas'  => 'required|string|max:150',
            'tanggal_sertifikat'    => 'required|date',
            'kadaluarsa_sertifikat' => 'required|date|after:tanggal_sertifikat',
        ]);

        $toko = Toko::findOrFail($id);

        $toko->update([
            'status'                => 'aktif',
            'terverifikasi_dinas'   => true,
            'no_sertifikat'         => $request->no_sertifikat,
            'nama_kepala_dinas'     => $request->nama_kepala_dinas,
            'jabatan_kepala_dinas'  => $request->jabatan_kepala_dinas,
            'tanggal_sertifikat'    => $request->tanggal_sertifikat,
            'kadaluarsa_sertifikat' => $request->kadaluarsa_sertifikat,
        ]);

        Notifikasi::create([
            'user_id' => $toko->user_id,
            'judul'   => '🎉 Toko Anda Telah Diverifikasi!',
            'pesan'   => "Selamat! Toko \"{$toko->nama_toko}\" telah diverifikasi oleh Dinas. " .
                         "Sertifikat No. {$request->no_sertifikat} berlaku hingga " .
                         \Carbon\Carbon::parse($request->kadaluarsa_sertifikat)->format('d M Y') . '.',
            'tipe'    => 'success',
            'url'     => route('penjual.toko.edit'),
        ]);

        return redirect()->route('dinas.verifikasi.sertifikat.pdf', $toko->id)
            ->with('success', 'Sertifikat berhasil diterbitkan dan dikirim ke penjual.');
    }

    public function pdf($id)
    {
        $toko = Toko::with('user')->findOrFail($id);

        $html = view('dinas.verifikasi.sertifikat-pdf', compact('toko'))->render();

        $pdf = Browsershot::html($html)
            ->noSandbox()
            ->setOption('printBackground', true)
            ->setOption('landscape', true)
            ->paperSize(297, 210, 'mm')
            ->margins(0, 0, 0, 0)
            ->pdf();

        return response($pdf)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="sertifikat-' . ($toko->slug ?? $toko->id) . '.pdf"');
    }
}