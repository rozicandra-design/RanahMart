<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaksi;
use Illuminate\Http\Request;

class TransaksiController extends Controller
{
    public function index()
    {
        $total      = Transaksi::count();
        $selesai    = Transaksi::where('status', 'selesai')->count();
        $dibatalkan = Transaksi::where('status', 'dibatalkan')->count();
        $totalVolume = Transaksi::sum('total') ?? 0;

        $stats = [
            'total'         => $total,
            'pending'       => Transaksi::where('status', 'pending')->count(),
            'selesai'       => $selesai,
            'dibatalkan'    => $dibatalkan,
            'total_volume'  => $totalVolume,
            'total_komisi'  => $totalVolume * 0.03,
            'sukses_persen' => $total > 0 ? round($selesai / $total * 100, 1) : 0,
            'retur_persen'  => $total > 0 ? round($dibatalkan / $total * 100, 1) : 0,
        ];

        $pesanans = Transaksi::with(['pesanan.toko', 'pesanan.pembeli'])
            ->latest()
            ->paginate(20);

        return view('admin.transaksi.index', compact('pesanans', 'stats'));
    }

    public function show(Transaksi $transaksi)
    {
        return view('admin.transaksi.show', compact('transaksi'));
    }

    public function export(Request $request)
    {
        // Filter opsional
        $query = Transaksi::with(['pesanan.toko', 'pesanan.pembeli'])->latest();

        if ($request->status) {
            $query->where('status', $request->status);
        }
        if ($request->dari) {
            $query->whereDate('created_at', '>=', $request->dari);
        }
        if ($request->sampai) {
            $query->whereDate('created_at', '<=', $request->sampai);
        }

        $transaksis = $query->get();

        $filename = 'transaksi-' . date('Y-m-d-His') . '.csv';

        $headers = [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Pragma'              => 'no-cache',
            'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0',
            'Expires'             => '0',
        ];

        $callback = function () use ($transaksis) {
            $handle = fopen('php://output', 'w');

            // BOM untuk Excel agar baca UTF-8 dengan benar
            fprintf($handle, chr(0xEF) . chr(0xBB) . chr(0xBF));

            // Header kolom
            fputcsv($handle, [
                'ID Transaksi',
                'Tanggal',
                'Nama Toko',
                'Nama Pembeli',
                'Email Pembeli',
                'Status',
                'Total (Rp)',
                'Komisi 3% (Rp)',
                'Metode Bayar',
                'Catatan',
            ]);

            // Baris data
            foreach ($transaksis as $t) {
                fputcsv($handle, [
                    $t->id,
                    $t->created_at->format('d/m/Y H:i'),
                    $t->pesanan?->toko?->nama_toko ?? '-',
                    $t->pesanan?->pembeli?->nama_lengkap ?? '-',
                    $t->pesanan?->pembeli?->email ?? '-',
                    ucfirst($t->status),
                    $t->total,
                    round($t->total * 0.03),
                    $t->metode_bayar ?? '-',
                    $t->catatan ?? '-',
                ]);
            }

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }
}