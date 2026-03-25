<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; color: #333; }

        /* KOP SURAT */
        .kop { display: flex; align-items: center; border-bottom: 3px solid #d97706; padding-bottom: 12px; margin-bottom: 20px; }
        .kop-logo { font-size: 28px; font-weight: bold; color: #1f2937; margin-right: 16px; }
        .kop-logo span { color: #d97706; }
        .kop-info { flex: 1; }
        .kop-info h1 { font-size: 16px; font-weight: bold; margin: 0 0 2px; }
        .kop-info p { font-size: 10px; color: #6b7280; margin: 0; }

        .judul { text-align: center; margin: 16px 0; }
        .judul h2 { font-size: 14px; font-weight: bold; text-transform: uppercase; margin: 0; }
        .judul p { font-size: 11px; color: #6b7280; margin: 4px 0 0; }

        table { width: 100%; border-collapse: collapse; margin-top: 16px; }
        th { background: #f59e0b; color: white; padding: 8px 10px; text-align: left; font-size: 11px; }
        td { padding: 8px 10px; border-bottom: 1px solid #e5e7eb; font-size: 11px; }
        tr:nth-child(even) td { background: #fafafa; }

        .footer { margin-top: 40px; text-align: right; font-size: 10px; color: #6b7280; }
        .ttd { margin-top: 60px; text-align: right; font-size: 11px; }
        .ttd .nama { border-top: 1px solid #333; display: inline-block; padding-top: 4px; min-width: 150px; text-align: center; }
    </style>
</head>
<body>

    {{-- KOP --}}
    <div class="kop">
        <div class="kop-logo">Ranah<span>Mart</span></div>
        <div class="kop-info">
            <h1>Platform UMKM Digital Ranah Minang</h1>
            <p>Jl. Sudirman No. 1, Padang, Sumatera Barat · admin@ranahmart.id · ranahmart.id</p>
        </div>
    </div>

    {{-- JUDUL --}}
    <div class="judul">
        <h2>Laporan Kinerja Platform</h2>
        <p>Periode: {{ $namaBulan }} {{ $tahun }}</p>
    </div>

    {{-- TABEL STATISTIK --}}
    <table>
        <thead>
            <tr>
                <th>Keterangan</th>
                <th>Nilai</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Total Volume Transaksi</td>
                <td>Rp {{ number_format($stats['total_transaksi'], 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Total Pesanan</td>
                <td>{{ number_format($stats['total_pesanan']) }} pesanan</td>
            </tr>
            <tr>
                <td>Komisi Platform (3%)</td>
                <td>Rp {{ number_format($stats['total_komisi'], 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Pendapatan Iklan</td>
                <td>Rp {{ number_format($stats['pendapatan_iklan'], 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td><strong>Total Pendapatan Platform</strong></td>
                <td><strong>Rp {{ number_format($stats['total_komisi'] + $stats['pendapatan_iklan'], 0, ',', '.') }}</strong></td>
            </tr>
        </tbody>
    </table>

    {{-- TTD --}}
    <div class="ttd">
        <p>Padang, {{ now()->format('d F Y') }}</p>
        <p>Admin Platform</p>
        <br><br><br>
        <div class="nama">{{ auth()->user()->nama_lengkap }}</div>
    </div>

    <div class="footer">
        Dicetak otomatis oleh sistem RanahMart · {{ now()->format('d/m/Y H:i') }}
    </div>

</body>
</html>