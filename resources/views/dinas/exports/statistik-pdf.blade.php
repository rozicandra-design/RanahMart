<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Statistik UMKM</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; color: #1f2937; }
        h1 { font-size: 18px; margin-bottom: 4px; }
        .subtitle { color: #6b7280; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 24px; }
        th { background: #7c3aed; color: white; padding: 8px 10px; text-align: left; }
        td { padding: 7px 10px; border-bottom: 1px solid #e5e7eb; }
        tr:nth-child(even) td { background: #f5f3ff; }
        .footer { margin-top: 30px; color: #9ca3af; font-size: 10px; text-align: center; }
        .stat-box { display: inline-block; width: 45%; background: #f5f3ff; border: 1px solid #ddd6fe; border-radius: 8px; padding: 12px; margin-bottom: 16px; }
        .stat-number { font-size: 22px; font-weight: bold; color: #7c3aed; }
    </style>
</head>
<body>

    <h1>Laporan Statistik UMKM Kota Padang</h1>
    <div class="subtitle">Dinas Koperasi & UMKM &mdash; Dicetak {{ now()->format('d F Y') }}</div>

    <div style="margin-bottom: 20px;">
        <div class="stat-box" style="margin-right: 4%">
            <div class="stat-number">{{ number_format($stats['total_aktif']) }}</div>
            <div>UMKM Aktif Terverifikasi</div>
        </div>
        <div class="stat-box">
            <div class="stat-number">{{ number_format($stats['baru_tahun_ini']) }}</div>
            <div>UMKM Baru Tahun {{ now()->year }}</div>
        </div>
    </div>

    <h2 style="font-size: 14px; margin-bottom: 8px;">Sebaran UMKM per Kecamatan</h2>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Kecamatan</th>
                <th>Jumlah UMKM</th>
                <th>Persentase</th>
            </tr>
        </thead>
        <tbody>
            @php $totalSeb = $sebaranKecamatan->sum('total') ?: 1; @endphp
            @foreach($sebaranKecamatan as $i => $k)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ $k->kecamatan }}</td>
                <td>{{ number_format($k->total) }}</td>
                <td>{{ round($k->total / $totalSeb * 100, 1) }}%</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <h2 style="font-size: 14px; margin-bottom: 8px;">Sebaran per Kategori Usaha</h2>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Kategori</th>
                <th>Jumlah UMKM</th>
                <th>Persentase</th>
            </tr>
        </thead>
        <tbody>
            @php $totalKat = $kategoriStats->sum('total') ?: 1; @endphp
            @foreach($kategoriStats as $i => $k)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ $k->kategori }}</td>
                <td>{{ number_format($k->total) }}</td>
                <td>{{ round($k->total / $totalKat * 100, 1) }}%</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Dokumen ini digenerate otomatis oleh sistem RanahMart &mdash; {{ now()->format('d/m/Y H:i') }}
    </div>

</body>
</html>