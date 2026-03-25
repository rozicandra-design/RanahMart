<?php

namespace App\Http\Controllers\Dinas;

use App\Http\Controllers\Controller;
use App\Models\Toko;
use App\Models\Notifikasi;
use Illuminate\Http\Request;
use Carbon\Carbon;

class VerifikasiController extends Controller
{
    public function index()
    {
        $tokos = Toko::with('user')->where('status', 'menunggu_dinas')->latest()->paginate(15);
        return view('dinas.verifikasi.index', compact('tokos'));
    }

    public function show($id)
    {
        $toko = Toko::with(['user', 'produks'])->findOrFail($id);
        return view('dinas.verifikasi.show', compact('toko'));
    }

    public function setujui(Request $request, $id)
    {
        $toko = Toko::findOrFail($id);

        $syaratKurang = [];
        if (!$toko->foto_ktp)           $syaratKurang[] = 'Foto KTP belum diupload';
        if (!$toko->foto_usaha)         $syaratKurang[] = 'Foto tempat usaha belum diupload';
        if (!$toko->foto_produk_sample) $syaratKurang[] = 'Foto produk sample belum diupload';
        if (!$toko->no_hp)              $syaratKurang[] = 'Nomor HP belum diisi';
        if (!$toko->alamat_lengkap)     $syaratKurang[] = 'Alamat lengkap belum diisi';
        if (!$toko->no_rekening)        $syaratKurang[] = 'Rekening bank belum diisi';

        if (!empty($syaratKurang)) {
            return back()->with('error_syarat', $syaratKurang);
        }

        $toko->update([
            'status'                => 'aktif',
            'terverifikasi_dinas'   => true,
            'tanggal_sertifikat'    => now(),
            'kadaluarsa_sertifikat' => now()->addYear(),
        ]);

        Notifikasi::create([
            'user_id' => $toko->user_id,
            'judul'   => '🎉 Toko Anda Telah Diverifikasi!',
            'pesan'   => "Selamat! Toko \"{$toko->nama_toko}\" telah diverifikasi oleh Dinas. Sertifikat berlaku hingga " .
                         now()->addYear()->format('d M Y') . '.' .
                         ($request->catatan_dinas ? " Catatan: {$request->catatan_dinas}" : ''),
            'tipe'    => 'success',
            'url'     => route('penjual.toko.edit'),
        ]);

        return back()->with('success', "Toko \"{$toko->nama_toko}\" berhasil diverifikasi dan sertifikat diterbitkan.");
    }

    public function tolak(Request $request, $id)
    {
        $request->validate([
            'catatan_dinas' => 'required|string|max:500',
        ]);

        $toko = Toko::findOrFail($id);

        $toko->update(['status' => 'ditolak', 'terverifikasi_dinas' => false]);

        Notifikasi::create([
            'user_id' => $toko->user_id,
            'judul'   => '❌ Verifikasi Toko Ditolak',
            'pesan'   => "Maaf, toko \"{$toko->nama_toko}\" ditolak oleh Dinas. Alasan: {$request->catatan_dinas}",
            'tipe'    => 'danger',
            'url'     => route('penjual.toko.edit'),
        ]);

        return back()->with('success', "Toko \"{$toko->nama_toko}\" telah ditolak.");
    }

    public function mintaDokumen(Request $request, $id)
    {
        $request->validate([
            'catatan_dinas' => 'required|string|max:500',
        ]);

        $toko = Toko::findOrFail($id);

        $toko->update(['status' => 'menunggu_dokumen']);

        Notifikasi::create([
            'user_id' => $toko->user_id,
            'judul'   => '📄 Dokumen Tambahan Diperlukan',
            'pesan'   => "Dinas memerlukan dokumen tambahan untuk toko \"{$toko->nama_toko}\": {$request->catatan_dinas}",
            'tipe'    => 'warning',
            'url'     => route('penjual.toko.edit'),
        ]);

        return back()->with('success', 'Permintaan dokumen telah dikirim ke penjual.');
    }

    public function jadwalKunjungan(Request $request, $id)
    {
        $request->validate([
            'tanggal_kunjungan' => 'required|date|after:today',
            'waktu_kunjungan'   => 'nullable|string',
            'catatan'           => 'nullable|string|max:300',
        ]);

        $toko = Toko::findOrFail($id);

        $tanggal = Carbon::parse($request->tanggal_kunjungan)->format('d M Y');
        $waktu   = $request->waktu_kunjungan ? " pukul {$request->waktu_kunjungan}" : '';

        Notifikasi::create([
            'user_id' => $toko->user_id,
            'judul'   => '🗓️ Jadwal Kunjungan Lapangan',
            'pesan'   => "Dinas akan melakukan kunjungan ke toko \"{$toko->nama_toko}\" pada {$tanggal}{$waktu}." .
                         ($request->catatan ? " Catatan: {$request->catatan}" : ''),
            'tipe'    => 'info',
            'url'     => route('penjual.notifikasi'),
        ]);

        return back()->with('success', 'Jadwal kunjungan telah dikirim ke penjual.');
    }
}