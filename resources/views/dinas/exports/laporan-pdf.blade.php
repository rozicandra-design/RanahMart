<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Dinas</title>
    <style>
        body { font-family: sans-serif; font-size: 11px; color: #1f2937; margin: 0; padding: 20px; }
        .header { text-align: center; border-bottom: 2px solid #7c3aed; padding-bottom: 12px; margin-bottom: 20px; }
        .header h1 { font-size: 16px; margin: 0 0 4px; }
        .header p { color: #6b7280; margin: 0; font-size: 11px; }
        .stats-grid { display: flex; gap: 10px; margin-bottom: 20px; }
        .stat-box { flex: 1; background: #f5f3ff; border: 1px solid #ddd6fe; border-radius: 6px; padding: 10px; text-align: center; }
        .stat-number { font-size: 20px; font-weight: bold; color: #7c3aed; }
        .stat-label { font-size: 9px; color: #6b7280; text-transform: uppercase; margin-top: 2px; }
        h2 { font-size: 13px; margin: 16px 0 8px; color: #374151; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 16px; }
        th { background: #7c3aed; color: white; padding: 7px 8px; text-align: left; font-size: 10px; }
        td { padding: 6px 8px; border-bottom: 1px solid #e5e7eb; font-size: 11px; }
        tr:nth-child(even) td { background: #faf5ff; }
        tfoot td { background: #ede9fe; font-weight: bold; border-top: 2px solid #7c3aed; }
        .narasi { background: #f5f3ff; border: 1px solid #ddd6fe; border-radius: 6px; padding: 12px; margin-top: 16px; }
        .narasi p { margin: 4px 0; line-height: 1.6; }
        .footer { margin-top: 24px; text-align: center; color: #9ca3af; font-size: 9px; border-top: 1px solid #e5e7eb; padding-top: 10px; }
        .kop { display: flex; align-items: center; justify-content: center; margin-bottom: 8px; }
        .kop-text { text-align: center; }
        .kop-text .instansi { font-size: 15px; font-weight: bold; text-transform: uppercase; }
        .kop-text .sub { font-size: 11px; }
    </style>
</head>
<body>

    {{-- Kop Surat --}}
    <div class="header">
        <div class="kop">
            <div class="kop-text">
                <div class="instansi">Dinas Koperasi & UMKM Kota Padang</div>
                <div class="sub">Laporan Rekapitulasi Verifikasi UMKM</div>
                <div class="sub" style="color:#7c3aed; font-weight:bold;">
                    Periode: {{ $namaBulan }} {{ $tahun }}
                </div>
            </div>
        </div>
    </div>

    {{-- Metrik --}}
    <div class="stats-grid">
        <div class="stat-box">
            <div class="stat-number" style="color:#0d9488">{{ $stats['diverifikasi'] }}</div>
            <div class="stat-label">UMKM Diverifikasi</div>
        </div>
        <div class="stat-box">
            <div class="stat-number" style="color:#dc2626">{{ $stats['ditolak'] }}</div>
            <div class="stat-label">UMKM Ditolak</div>
        </div>
        <div class="stat-box">
            <div class="stat-number">{{ $stats['peserta_pembinaan'] }}</div>
            <div class="stat-label">Peserta Pembinaan</div>
        </div>
        <div class="stat-box">
            <div class="stat-number">{{ $stats['kunjungan'] }}</div>
            <div class="stat-label">Kunjungan Lapangan</div>
        </div>
    </div>

    {{-- Tabel Rekap --}}
    <h2>Rekap Verifikasi per Kecamatan</h2>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Kecamatan</th>
                <th>Disetujui</th>
                <th>Pending</th>
                <th>Total Aktif</th>
            </tr>
        </thead>
        <tbody>
            @forelse($rekapKecamatan as $i => $k)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ $k->kecamatan }}</td>
                <td>{{ $k->terverifikasi }}</td>
                <td>{{ \App\Models\Toko::where('kecamatan', $k->kecamatan)->where('status','menunggu_dinas')->count() }}</td>
                <td>{{ $k->total }}</td>
            </tr>
            @empty
            <tr><td colspan="5" style="text-align:center; color:#9ca3af">Tidak ada data</td></tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr>
                <td colspan="2">Total</td>
                <td>{{ $rekapKecamatan->sum('terverifikasi') }}</td>
                <td>{{ \App\Models\Toko::where('status','menunggu_dinas')->count() }}</td>
                <td>{{ $rekapKecamatan->sum('total') }}</td>
            </tr>
        </tfoot>
    </table>

    {{-- Narasi --}}
    <div class="narasi">
        <strong>Ringkasan:</strong>
        <p>
            Pada bulan {{ $namaBulan }} {{ $tahun }}, Dinas Koperasi & UMKM Kota Padang
            telah memverifikasi <strong>{{ $stats['diverifikasi'] }} UMKM</strong>
            dan menolak <strong>{{ $stats['ditolak'] }} pengajuan</strong> karena dokumen tidak lengkap.
        </p>
        <p>
            Saat ini terdapat <strong>{{ \App\Models\Toko::where('terverifikasi_dinas', true)->count() }} UMKM</strong>
            aktif yang telah mendapat sertifikat resmi Dinas.
        </p>
    </div>

    {{-- TTD --}}
    <div style="margin-top: 30px; text-align: right; font-size: 11px;">
        <p>Padang, {{ now()->format('d F Y') }}</p>
        <p>Kepala Dinas Koperasi & UMKM</p>
        <br><br><br>
        <p style="text-decoration: underline; font-weight: bold;">___________________________</p>
        <p>NIP. -</p>
    </div>

    <div class="footer">
        Dicetak otomatis oleh sistem RanahMart &mdash; {{ now()->format('d/m/Y H:i') }}
    </div>

</body>
</html>