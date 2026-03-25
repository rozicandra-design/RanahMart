@extends('layouts.dashboard')
@section('title', 'Laporan & Analitik')
@section('page-title', 'Laporan & Analitik')
@section('sidebar') @include('components.sidebar-penjual') @endsection

@push('styles')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

    .laporan-wrap * { font-family: 'Plus Jakarta Sans', sans-serif; box-sizing: border-box; }
    .laporan-wrap { background: #f6f5f2; min-height: 100vh; padding: 28px; }

    /* ── Header ── */
    .lap-header {
        display: flex; align-items: flex-start; justify-content: space-between;
        gap: 16px; margin-bottom: 28px; flex-wrap: wrap;
    }
    .lap-header .title { font-size: 20px; font-weight: 800; color: #1a1a1a; margin-bottom: 3px; }
    .lap-header .sub   { font-size: 13px; color: #aaa; font-weight: 500; }

    .lap-header-right { display: flex; gap: 10px; align-items: center; flex-wrap: wrap; }

    .filter-wrap {
        display: flex; align-items: center;
        background: #fff; border: 1.5px solid #e8e8e8; border-radius: 12px;
        overflow: hidden;
    }
    .filter-wrap select {
        background: transparent; border: none; outline: none;
        padding: 10px 14px; font-size: 13px; font-weight: 600;
        color: #1a1a1a; cursor: pointer;
        font-family: 'Plus Jakarta Sans', sans-serif;
    }
    .filter-divider { width: 1px; background: #e8e8e8; height: 20px; flex-shrink: 0; }

    .btn-export {
        background: #0d9488; color: #fff;
        font-size: 13px; font-weight: 700;
        font-family: 'Plus Jakarta Sans', sans-serif;
        border: none; border-radius: 11px;
        padding: 10px 18px; cursor: pointer;
        display: flex; align-items: center; gap: 7px;
        box-shadow: 0 4px 12px rgba(13,148,136,.25);
        text-decoration: none;
        transition: background .2s, transform .1s;
    }
    .btn-export:hover { background: #0f766e; transform: translateY(-1px); }

    /* ── Stat cards ── */
    .stat-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 16px;
        margin-bottom: 24px;
    }
    @media (max-width: 1024px) { .stat-grid { grid-template-columns: repeat(2, 1fr); } }

    .stat-card {
        background: #fff; border-radius: 18px;
        border: 1px solid #ebebeb; padding: 20px 22px;
        box-shadow: 0 1px 4px rgba(0,0,0,.04);
        transition: box-shadow .2s, transform .15s;
    }
    .stat-card:hover { box-shadow: 0 6px 20px rgba(0,0,0,.07); transform: translateY(-2px); }

    .stat-card-top {
        display: flex; align-items: center; justify-content: space-between;
        margin-bottom: 14px;
    }
    .stat-icon {
        width: 40px; height: 40px; border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
    }
    .stat-badge {
        font-size: 10px; font-weight: 800; padding: 3px 9px;
        border-radius: 999px; text-transform: uppercase; letter-spacing: .4px;
    }
    .stat-label { font-size: 10.5px; font-weight: 800; color: #bbb; text-transform: uppercase; letter-spacing: .6px; margin-bottom: 5px; }
    .stat-value { font-size: 22px; font-weight: 800; color: #1a1a1a; line-height: 1.1; }

    /* ── Main grid ── */
    .main-grid {
        display: grid;
        grid-template-columns: 1fr 340px;
        gap: 20px;
        align-items: start;
    }
    @media (max-width: 1100px) { .main-grid { grid-template-columns: 1fr; } }

    .left-col  { display: flex; flex-direction: column; gap: 20px; }
    .right-col { display: flex; flex-direction: column; gap: 20px; }

    /* ── Chart cards ── */
    .chart-card {
        background: #fff; border-radius: 20px;
        border: 1px solid #ebebeb; padding: 24px 26px;
        box-shadow: 0 1px 4px rgba(0,0,0,.04);
    }
    .chart-header { margin-bottom: 20px; }
    .chart-title { font-size: 14px; font-weight: 800; color: #1a1a1a; margin-bottom: 3px; }
    .chart-sub   { font-size: 11.5px; color: #bbb; font-weight: 500; }

    /* ── Top produk ── */
    .top-produk-item { margin-bottom: 18px; }
    .top-produk-item:last-child { margin-bottom: 0; }
    .top-produk-row {
        display: flex; justify-content: space-between; align-items: center;
        margin-bottom: 6px;
    }
    .top-produk-name { font-size: 12.5px; font-weight: 700; color: #1a1a1a; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 160px; }
    .top-produk-val  { font-size: 12px; font-weight: 800; color: #1a1a1a; flex-shrink: 0; }
    .progress-track { width: 100%; background: #f3f3f3; height: 7px; border-radius: 999px; overflow: hidden; margin-bottom: 3px; }
    .progress-fill  { height: 100%; border-radius: 999px; background: linear-gradient(90deg, #0d9488, #34d399); transition: width .7s ease; }
    .top-produk-qty  { font-size: 10.5px; color: #bbb; font-weight: 500; }

    /* ── Status legend ── */
    .legend-row {
        display: flex; align-items: center; justify-content: space-between;
        padding: 7px 0; border-bottom: 1px solid #f5f5f5; font-size: 12px;
    }
    .legend-row:last-child { border-bottom: none; }
    .legend-dot { width: 9px; height: 9px; border-radius: 50%; flex-shrink: 0; }
    .legend-label { color: #666; font-weight: 500; }
    .legend-val   { font-weight: 800; color: #1a1a1a; }
</style>
@endpush

@section('content')
<div class="laporan-wrap">

    {{-- Header ── --}}
    <div class="lap-header">
        <div>
            <div class="title">Laporan & Analitik</div>
            <div class="sub">Periode {{ date('F Y', mktime(0,0,0,$bulan,1,$tahun)) }}</div>
        </div>
        <div class="lap-header-right">
            <form method="GET" id="filterForm">
                <div class="filter-wrap">
                    <select name="bulan" onchange="document.getElementById('filterForm').submit()">
                        @foreach(range(1,12) as $m)
                        <option value="{{ $m }}" {{ $bulan == $m ? 'selected' : '' }}>{{ date('F', mktime(0,0,0,$m,1)) }}</option>
                        @endforeach
                    </select>
                    <div class="filter-divider"></div>
                    <select name="tahun" onchange="document.getElementById('filterForm').submit()">
                        @foreach([date('Y'), date('Y')-1] as $y)
                        <option value="{{ $y }}" {{ $tahun == $y ? 'selected' : '' }}>{{ $y }}</option>
                        @endforeach
                    </select>
                </div>
            </form>
            <a href="{{ route('penjual.laporan.cetak', ['bulan'=>$bulan,'tahun'=>$tahun]) }}" target="_blank" class="btn-export">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                Cetak PDF
            </a>
        </div>
    </div>

    {{-- Stat Cards ── --}}
    <div class="stat-grid">
        <div class="stat-card">
            <div class="stat-card-top">
                <div class="stat-icon" style="background:#f0fdfa;">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#0d9488" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="16"/><line x1="8" y1="12" x2="16" y2="12"/></svg>
                </div>
                <span class="stat-badge" style="background:#f0fdfa;color:#0d9488;">Bulan Ini</span>
            </div>
            <div class="stat-label">Total Omzet</div>
            <div class="stat-value" style="font-size:18px;">Rp {{ number_format($stats['total_penjualan'],0,',','.') }}</div>
        </div>

        <div class="stat-card">
            <div class="stat-card-top">
                <div class="stat-icon" style="background:#eff6ff;">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#3b82f6" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 01-8 0"/></svg>
                </div>
            </div>
            <div class="stat-label">Pesanan Sukses</div>
            <div class="stat-value">{{ $stats['total_pesanan'] }}</div>
        </div>

        <div class="stat-card">
            <div class="stat-card-top">
                <div class="stat-icon" style="background:#f3f0ff;">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#7c3aed" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg>
                </div>
            </div>
            <div class="stat-label">Rata-rata Transaksi</div>
            <div class="stat-value" style="font-size:18px;">
                Rp {{ $stats['total_pesanan'] > 0 ? number_format($stats['total_penjualan']/$stats['total_pesanan'],0,',','.') : 0 }}
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-card-top">
                <div class="stat-icon" style="background:#fffbeb;">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#d97706" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                </div>
            </div>
            <div class="stat-label">Produk Andalan</div>
            <div class="stat-value" style="font-size:15px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                {{ $topProduk->first()?->produk?->nama ?? '—' }}
            </div>
        </div>
    </div>

    {{-- Main Grid ── --}}
    <div class="main-grid">

        {{-- LEFT ── --}}
        <div class="left-col">

            {{-- Grafik Harian ── --}}
            <div class="chart-card">
                <div class="chart-header">
                    <div class="chart-title">Grafik Penjualan Harian</div>
                    <div class="chart-sub">Total pendapatan per hari bulan ini</div>
                </div>
                <div style="height:300px;">
                    <canvas id="chartHarian"></canvas>
                </div>
            </div>

            {{-- Tren Bulanan ── --}}
            <div class="chart-card">
                <div class="chart-header">
                    <div class="chart-title">Tren 6 Bulan Terakhir</div>
                    <div class="chart-sub">Perbandingan omzet bulanan</div>
                </div>
                <div style="height:260px;">
                    <canvas id="chartBulanan"></canvas>
                </div>
            </div>

        </div>

        {{-- RIGHT ── --}}
        <div class="right-col">

            {{-- Distribusi Status ── --}}
            <div class="chart-card">
                <div class="chart-header">
                    <div class="chart-title">Distribusi Status</div>
                    <div class="chart-sub">Komposisi status pesanan</div>
                </div>
                <div style="height:200px;display:flex;justify-content:center;">
                    <canvas id="chartStatus"></canvas>
                </div>
                <div style="margin-top:18px;" id="status-legend"></div>
            </div>

            {{-- Top 5 Produk ── --}}
            <div class="chart-card">
                <div class="chart-header">
                    <div class="chart-title">Top 5 Produk</div>
                    <div class="chart-sub">Berdasarkan omzet bulan ini</div>
                </div>
                @if($topProduk->count())
                @foreach($topProduk->take(5) as $i => $item)
                @php $maxOmzet = $topProduk->max('total_omzet') ?: 1; @endphp
                <div class="top-produk-item">
                    <div class="top-produk-row">
                        <span class="top-produk-name">{{ $item->produk?->nama ?? '—' }}</span>
                        <span class="top-produk-val">Rp {{ number_format($item->total_omzet,0,',','.') }}</span>
                    </div>
                    <div class="progress-track">
                        <div class="progress-fill" style="width:{{ round(($item->total_omzet/$maxOmzet)*100) }}%"></div>
                    </div>
                    <div class="top-produk-qty">{{ $item->total_qty }} unit terjual</div>
                </div>
                @endforeach
                @else
                <div style="text-align:center;padding:32px 0;color:#ccc;">
                    <div style="font-size:32px;margin-bottom:8px;">📦</div>
                    <div style="font-size:12px;font-weight:600;">Belum ada data produk</div>
                </div>
                @endif
            </div>

        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.min.js"></script>
<script>
const theme = {
    teal: '#0d9488',
    tealSoft: 'rgba(13,148,136,0.08)',
    gray: '#f3f4f6',
};
Chart.defaults.font.family = "'Plus Jakarta Sans', sans-serif";
Chart.defaults.color = '#9ca3af';

/* ── Chart Harian ── */
new Chart(document.getElementById('chartHarian'), {
    type: 'line',
    data: {
        labels: {!! json_encode($chartHari) !!},
        datasets: [{
            data: {!! json_encode($chartPenjualan) !!},
            borderColor: theme.teal,
            backgroundColor: theme.tealSoft,
            borderWidth: 2.5,
            fill: true, tension: 0.4,
            pointRadius: 0, pointHoverRadius: 6,
            pointBackgroundColor: theme.teal,
        }]
    },
    options: {
        maintainAspectRatio: false, responsive: true,
        plugins: { legend: { display: false } },
        scales: {
            x: { grid: { display: false }, ticks: { font: { size: 11 } } },
            y: {
                border: { display: false },
                grid: { color: '#f3f3f3' },
                ticks: {
                    font: { size: 11 },
                    callback: v => v >= 1e6 ? (v/1e6).toFixed(1)+'jt' : v >= 1e3 ? (v/1e3).toFixed(0)+'rb' : v
                }
            }
        }
    }
});

/* ── Chart Status Donut ── */
const statusData   = @json($statusPesanan);
const statusLabels = { pending:'Pending', diproses:'Diproses', dikirim:'Dikirim', selesai:'Selesai', dibatalkan:'Dibatalkan' };
const statusColors = { pending:'#f59e0b', diproses:'#3b82f6', dikirim:'#8b5cf6', selesai:'#0d9488', dibatalkan:'#ef4444' };

const sLabels = Object.keys(statusData).map(k => statusLabels[k] ?? k);
const sValues = Object.values(statusData).map(v => v.total);
const sColors = Object.keys(statusData).map(k => statusColors[k] ?? '#d1d5db');

if (sValues.some(v => v > 0)) {
    new Chart(document.getElementById('chartStatus'), {
        type: 'doughnut',
        data: { labels: sLabels, datasets: [{ data: sValues, backgroundColor: sColors, borderWidth: 4, borderColor: '#fff' }] },
        options: {
            maintainAspectRatio: false, cutout: '72%',
            plugins: { legend: { display: false } }
        }
    });
}

const legend = document.getElementById('status-legend');
sLabels.forEach((label, i) => {
    legend.innerHTML += `
        <div class="legend-row">
            <div style="display:flex;align-items:center;gap:9px;">
                <div class="legend-dot" style="background:${sColors[i]};"></div>
                <span class="legend-label">${label}</span>
            </div>
            <span class="legend-val">${sValues[i]}</span>
        </div>`;
});

/* ── Chart Bulanan Bar ── */
const dataBulanan = @json($enamBulan);
new Chart(document.getElementById('chartBulanan'), {
    type: 'bar',
    data: {
        labels: dataBulanan.map(b => b.label),
        datasets: [{
            data: dataBulanan.map(b => b.total),
            backgroundColor: dataBulanan.map((_, i) => i === dataBulanan.length - 1 ? theme.teal : '#e8e8e8'),
            borderRadius: 8,
            maxBarThickness: 44,
        }]
    },
    options: {
        maintainAspectRatio: false, responsive: true,
        plugins: { legend: { display: false } },
        scales: {
            x: { grid: { display: false }, ticks: { font: { size: 11 } } },
            y: {
                grid: { color: '#f3f3f3' },
                border: { display: false },
                ticks: {
                    font: { size: 11 },
                    callback: v => v >= 1e6 ? (v/1e6).toFixed(1)+'jt' : v >= 1e3 ? (v/1e3).toFixed(0)+'rb' : v
                }
            }
        }
    }
});
</script>
@endpush