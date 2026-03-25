<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; font-size: 12px; color: #333; background: #fff; }
        .page { padding: 35px 40px; max-width: 794px; margin: 0 auto; }

        /* KOP */
        .kop { display: flex; justify-content: space-between; align-items: flex-start; padding-bottom: 16px; border-bottom: 3px solid #0d9488; margin-bottom: 24px; }
        .kop-kiri .brand { font-size: 24px; font-weight: bold; color: #1f2937; }
        .kop-kiri .brand span { color: #0d9488; }
        .kop-kiri .sub { font-size: 10px; color: #6b7280; margin-top: 2px; }
        .kop-kiri .toko { margin-top: 10px; font-size: 13px; font-weight: bold; color: #1f2937; }
        .kop-kiri .toko-sub { font-size: 10px; color: #6b7280; margin-top: 1px; }
        .kop-kanan { text-align: right; }
        .kop-kanan .doc-label { font-size: 11px; font-weight: bold; color: #0d9488; text-transform: uppercase; letter-spacing: 1px; }
        .kop-kanan .doc-periode { font-size: 11px; color: #374151; margin-top: 4px; font-weight: bold; }
        .kop-kanan .doc-tanggal { font-size: 10px; color: #9ca3af; margin-top: 2px; }

        /* JUDUL */
        .judul { text-align: center; background: #f0fdfa; border-radius: 8px; padding: 14px; margin-bottom: 24px; }
        .judul h2 { font-size: 14px; font-weight: bold; color: #0f766e; text-transform: uppercase; letter-spacing: 1px; }
        .judul p { font-size: 11px; color: #6b7280; margin-top: 3px; }

        /* STATS GRID */
        .stats-grid { display: table; width: 100%; border-collapse: separate; border-spacing: 10px; margin-bottom: 24px; }
        .stats-row { display: table-row; }
        .stat-box { display: table-cell; border: 1.5px solid #e5e7eb; border-radius: 10px; padding: 14px 12px; text-align: center; width: 25%; }
        .stat-box.primary { border-color: #0d9488; background: #f0fdfa; }
        .stat-box .s-label { font-size: 9px; font-weight: bold; color: #9ca3af; text-transform: uppercase; letter-spacing: .5px; margin-bottom: 6px; }
        .stat-box .s-value { font-size: 15px; font-weight: bold; color: #1f2937; }
        .stat-box.primary .s-value { color: #0d9488; font-size: 13px; }

        /* SECTION */
        .section { margin-bottom: 22px; }
        .section-title { font-size: 11px; font-weight: bold; text-transform: uppercase; letter-spacing: .6px; color: #374151; padding: 0 0 7px 10px; border-bottom: 1.5px solid #e5e7eb; margin-bottom: 12px; border-left: 3px solid #0d9488; }

        /* TABLE */
        table { width: 100%; border-collapse: collapse; }
        thead tr { background: #0d9488; }
        thead th { color: #fff; padding: 9px 12px; text-align: left; font-size: 11px; font-weight: bold; }
        tbody td { padding: 8px 12px; border-bottom: 1px solid #f3f4f6; font-size: 11px; }
        tbody tr:nth-child(even) td { background: #f9fafb; }
        tbody tr:last-child td { border-bottom: none; }
        .tr { text-align: right; }
        .fb { font-weight: bold; }

        /* BAR */
        .bar-wrap { margin-bottom: 10px; }
        .bar-head { display: flex; justify-content: space-between; margin-bottom: 4px; }
        .bar-lbl { font-size: 10px; color: #374151; font-weight: 600; }
        .bar-val { font-size: 10px; color: #6b7280; }
        .bar-bg { background: #f3f4f6; border-radius: 4px; height: 10px; width: 100%; }
        .bar-fill { background: #0d9488; border-radius: 4px; height: 10px; }

        /* TTD */
        .ttd { text-align: right; margin-top: 50px; font-size: 11px; color: #374151; }
        .ttd p { margin-bottom: 3px; }
        .ttd-nama { display: inline-block; margin-top: 55px; border-top: 1px solid #374151; padding-top: 5px; min-width: 180px; text-align: center; font-weight: bold; font-size: 12px; color: #1f2937; }

        /* FOOTER */
        .footer { margin-top: 30px; border-top: 1px solid #e5e7eb; padding-top: 10px; text-align: center; font-size: 9px; color: #9ca3af; }
    </style>
</head>
<body>
<div class="page">

    {{-- KOP --}}
    <div class="kop">
        <div class="kop-kiri">
            <div class="brand">Ranah<span>Mart</span></div>
            <div class="sub">Platform UMKM Digital · ranahmart.id</div>
            <div class="toko">{{ $toko->nama_toko }}</div>
            <div class="toko-sub">{{ $toko->kecamatan ?? '' }} · {{ $toko->no_hp ?? '' }}</div>
        </div>
        <div class="kop-kanan">
            <div class="doc-label">Laporan Penjualan</div>
            <div class="doc-periode">Periode: {{ $namaBulan }} {{ $tahun }}</div>
            <div class="doc-tanggal">Dicetak: {{ now()->format('d/m/Y H:i') }}</div>
        </div>
    </div>

    {{-- JUDUL --}}
    <div class="judul">
        <h2>Laporan & Analitik Penjualan</h2>
        <p>Ringkasan performa toko periode {{ $namaBulan }} {{ $tahun }}</p>
    </div>

    {{-- STATS --}}
    <div class="section">
        <div class="section-title">Ringkasan Kinerja</div>
        <div class="stats-grid">
            <div class="stats-row">
                <div class="stat-box primary">
                    <div class="s-label">Total Omzet</div>
                    <div class="s-value">Rp {{ number_format($stats['total_penjualan'], 0, ',', '.') }}</div>
                </div>
                <div class="stat-box">
                    <div class="s-label">Total Pesanan</div>
                    <div class="s-value">{{ number_format($stats['total_pesanan']) }}</div>
                </div>
                <div class="stat-box">
                    <div class="s-label">Rata-rata Transaksi</div>
                    <div class="s-value">Rp {{ $stats['total_pesanan'] > 0 ? number_format($stats['total_penjualan'] / $stats['total_pesanan'], 0, ',', '.') : 0 }}</div>
                </div>
                <div class="stat-box">
                    <div class="s-label">Konversi</div>
                    <div class="s-value">{{ $stats['konversi'] }}%</div>
                </div>
            </div>
        </div>
    </div>

    {{-- TABEL DETAIL --}}
    <div class="section">
        <div class="section-title">Detail Kinerja</div>
        <table>
            <thead>
                <tr>
                    <th>Keterangan</th>
                    <th class="tr">Nilai</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Total Omzet (Lunas)</td>
                    <td class="tr fb">Rp {{ number_format($stats['total_penjualan'], 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td>Total Pesanan</td>
                    <td class="tr">{{ number_format($stats['total_pesanan']) }} pesanan</td>
                </tr>
                <tr>
                    <td>Rata-rata per Transaksi</td>
                    <td class="tr">Rp {{ $stats['total_pesanan'] > 0 ? number_format($stats['total_penjualan'] / $stats['total_pesanan'], 0, ',', '.') : 0 }}</td>
                </tr>
                <tr>
                    <td>Pengunjung Toko</td>
                    <td class="tr">{{ number_format($stats['pengunjung']) }} orang</td>
                </tr>
                <tr>
                    <td>Tingkat Konversi</td>
                    <td class="tr">{{ $stats['konversi'] }}%</td>
                </tr>
            </tbody>
        </table>
    </div>

    {{-- KATEGORI --}}
    @if($kategoriBars->count())
    <div class="section">
        <div class="section-title">Penjualan per Kategori</div>
        @php $maxKat = $kategoriBars->max('total') ?: 1; @endphp
        @foreach($kategoriBars as $kat)
        <div class="bar-wrap">
            <div class="bar-head">
                <span class="bar-lbl">{{ $kat->kategori ?? 'Lainnya' }}</span>
                <span class="bar-val">{{ number_format($kat->total) }} unit terjual</span>
            </div>
            <div class="bar-bg">
                <div class="bar-fill" style="width:{{ round($kat->total / $maxKat * 100) }}%"></div>
            </div>
        </div>
        @endforeach
    </div>
    @endif

    {{-- TTD --}}
    <div class="ttd">
        <p>{{ $toko->kecamatan ?? 'Padang' }}, {{ now()->format('d F Y') }}</p>
        <p>Pemilik Toko</p>
        <div class="ttd-nama">{{ auth()->user()->nama_lengkap }}</div>
    </div>

    {{-- FOOTER --}}
    <div class="footer">
        Dicetak otomatis oleh sistem RanahMart &middot; {{ now()->format('d/m/Y H:i') }} &middot; Dokumen ini sah tanpa tanda tangan basah
    </div>

</div>
</body>
</html>