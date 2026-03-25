@extends('layouts.dashboard')

@section('title', 'Detail Verifikasi UMKM')
@section('page-title', 'Detail Verifikasi UMKM')

@section('sidebar') 
    @include('components.sidebar-dinas') 
@endsection

@push('styles')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;600&display=swap');

    .monitoring-wrap * { font-family: 'Plus Jakarta Sans', sans-serif; }
    .monitoring-wrap {
        background: #f6f5f2;
        min-height: 100vh;
        padding: 28px 28px 48px;
    }

    /* Navigation */
    .back-link {
        display: inline-flex; align-items: center; gap: 6px;
        font-size: 13px; font-weight: 600; color: #888;
        text-decoration: none; margin-bottom: 24px; transition: color .2s;
    }
    .back-link:hover { color: #1a1a1a; }
    .back-link svg { transition: transform .2s; }
    .back-link:hover svg { transform: translateX(-3px); }

    /* Layout Grid */
    .main-grid {
        display: grid;
        grid-template-columns: 1.1fr 0.9fr;
        gap: 20px;
    }
    @media (max-width: 1024px) { .main-grid { grid-template-columns: 1fr; } }
    .col { display: flex; flex-direction: column; gap: 20px; }

    /* Card Base */
    .card {
        background: #fff;
        border-radius: 18px;
        border: 1px solid #ebebeb;
        padding: 24px;
        box-shadow: 0 1px 3px rgba(0,0,0,.04);
    }

    /* Hero Card Style */
    .hero-card {
        background: linear-gradient(135deg, #1a0533 0%, #2d1052 100%);
        border-radius: 18px;
        padding: 24px;
        color: #fff;
        position: relative;
        overflow: hidden;
    }
    .hero-card::before {
        content: '';
        position: absolute; inset: 0;
        background: radial-gradient(circle at 90% 10%, rgba(167,139,250,.25) 0%, transparent 50%);
        pointer-events: none;
    }

    /* Elements */
    .store-avatar {
        width: 56px; height: 56px;
        border-radius: 14px;
        background: linear-gradient(135deg, #a78bfa, #7c3aed);
        display: flex; align-items: center; justify-content: center;
        font-size: 24px; font-weight: 800; color: #fff;
        box-shadow: 0 4px 16px rgba(124,58,237,.4);
    }
    .store-name { font-size: 20px; font-weight: 800; letter-spacing: -.3px; }
    .badge-active {
        background: rgba(52,211,153,.15);
        border: 1px solid rgba(52,211,153,.35);
        color: #34d399; font-size: 11.5px; font-weight: 700;
        padding: 4px 12px; border-radius: 999px;
    }

    /* Stats & Info */
    .stats-row { display: grid; grid-template-columns: repeat(4, 1fr); gap: 10px; margin: 20px 0; }
    .stat-pill {
        background: rgba(255,255,255,.07); border: 1px solid rgba(255,255,255,.1);
        border-radius: 12px; padding: 12px 8px; text-align: center;
    }
    .stat-pill .val { font-size: 18px; font-weight: 800; color: #fff; display: block; }
    .stat-pill .lbl { font-size: 10px; color: rgba(255,255,255,.45); text-transform: uppercase; }

    .info-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; }
    .info-cell {
        background: rgba(255,255,255,.07); border: 1px solid rgba(255,255,255,.1);
        border-radius: 12px; padding: 12px 14px;
    }
    .info-cell .lbl { font-size: 10px; color: rgba(255,255,255,.4); font-weight: 600; text-transform: uppercase; }
    .info-cell .val { font-size: 13px; font-weight: 700; color: #fff; margin-top: 4px; }

    /* Form & Components */
    .form-label { font-size: 11px; font-weight: 700; color: #888; text-transform: uppercase; margin-bottom: 8px; display: block; }
    .input-field {
        width: 100%; border: 1.5px solid #e8e8e8; border-radius: 10px;
        padding: 10px 14px; font-size: 13px; background: #fafafa; outline: none;
    }
    .divider { height: 1px; background: #f3f3f3; margin: 16px 0; }
    
    .berkas-item {
        display: flex; align-items: center; justify-content: space-between;
        padding: 10px 14px; border-radius: 10px; margin-bottom: 8px; border: 1px solid #f0eeeb;
    }
    .berkas-item.ada { background: #f0fdf8; border-color: #bbf7d0; color: #065f46; }
    .berkas-item.kurang { background: #fef2f2; border-color: #fecaca; color: #991b1b; }
</style>
@endpush

@section('content')
<div class="monitoring-wrap">
    {{-- Back Link --}}
    <a href="{{ route('dinas.verifikasi.index') }}" class="back-link">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <path d="M19 12H5M12 5l-7 7 7 7"/>
        </svg>
        Kembali ke Verifikasi
    </a>

    @php
        $berkas = [
            ['label' => 'Foto KTP Pemilik',    'field' => 'foto_ktp',           'wajib' => true],
            ['label' => 'Foto Tempat Usaha',   'field' => 'foto_usaha',         'wajib' => true],
            ['label' => 'Foto Produk Sample',  'field' => 'foto_produk_sample', 'wajib' => true],
            ['label' => 'Nomor HP',            'field' => 'no_hp',              'wajib' => true],
            ['label' => 'Alamat Lengkap',      'field' => 'alamat_lengkap',     'wajib' => true],
            ['label' => 'Rekening Bank',       'field' => 'no_rekening',        'wajib' => true],
            ['label' => 'NIB',                 'field' => 'nib',                'wajib' => false],
            ['label' => 'No. SKU / SIUP',      'field' => 'no_sku',             'wajib' => false],
        ];
        $totalKurang = collect($berkas)->filter(fn($b) => $b['wajib'] && !$toko->{$b['field']})->count();
    @endphp

    {{-- Alerts Section --}}
    @if(session('success'))
        <div style="background:#f0fdf8; border:1px solid #bbf7d0; border-radius:12px; padding:14px 18px; margin-bottom:20px; color:#059669; font-weight:700; font-size:13px;">
            ✓ {{ session('success') }}
        </div>
    @endif

    @if(session('error_syarat'))
        <div style="background:#fef2f2; border:1px solid #fecaca; border-radius:12px; padding:16px; margin-bottom:20px;">
            <div style="font-size:13px; font-weight:700; color:#dc2626; margin-bottom:8px;">⚠ Verifikasi Gagal — Syarat Belum Lengkap</div>
            <ul style="list-style:disc; padding-left:18px; margin:0;">
                @foreach(session('error_syarat') as $syarat)
                    <li style="font-size:12.5px; color:#dc2626;">{{ $syarat }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="main-grid">
        {{-- ===== LEFT COLUMN ===== --}}
        <div class="col">
            {{-- Hero Profile Card --}}
            <div class="hero-card">
                <div style="display:flex; align-items:flex-start; justify-content:space-between;">
                    <div style="display:flex; align-items:center; gap:14px;">
                        <div class="store-avatar">{{ strtoupper(substr($toko->nama_toko, 0, 1)) }}</div>
                        <div>
                            <div class="store-name">{{ $toko->nama_toko }}</div>
                            <div style="font-size:12px; opacity:0.7;">{{ $toko->kategori_friendly }} &middot; {{ $toko->kecamatan }}</div>
                            @if($toko->terverifikasi_dinas)
                                <span class="badge-verified" style="background:rgba(52,211,153,.2); padding:2px 8px; border-radius:10px; font-size:10px; display:inline-flex; align-items:center; gap:4px; margin-top:5px; border:1px solid rgba(52,211,153,.3);">
                                    <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="20 6 9 17 4 12"/></svg>
                                    Terverifikasi Dinas
                                </span>
                            @endif
                        </div>
                    </div>
                    <span class="badge-active">● Aktif</span>
                </div>

                <div class="stats-row">
                    <div class="stat-pill"><span class="val">★ {{ $toko->rating ?? 0 }}</span><span class="lbl">Rating</span></div>
                    <div class="stat-pill"><span class="val">{{ $toko->total_pesanan ?? 0 }}</span><span class="lbl">Pesanan</span></div>
                    <div class="stat-pill"><span class="val">{{ $toko->produksAktif->count() }}</span><span class="lbl">Produk</span></div>
                    <div class="stat-pill"><span class="val">{{ $toko->total_ulasan ?? 0 }}</span><span class="lbl">Ulasan</span></div>
                </div>

                <div class="info-grid">
                    <div class="info-cell"><div class="lbl">Pemilik</div><div class="val">{{ $toko->user->nama_lengkap ?? '-' }}</div></div>
                    <div class="info-cell"><div class="lbl">Kontak</div><div class="val">{{ $toko->no_hp ?? '-' }}</div></div>
                    <div class="info-cell"><div class="lbl">NIB</div><div class="val" style="font-family:'JetBrains Mono'">{{ $toko->nib ?? '-' }}</div></div>
                    <div class="info-cell"><div class="lbl">Bergabung</div><div class="val">{{ $toko->created_at->format('d M Y') }}</div></div>
                </div>
            </div>

            {{-- Certificate & Description --}}
            @if($toko->no_sertifikat || $toko->deskripsi)
            <div class="card">
                @if($toko->no_sertifikat)
                    <div style="background:linear-gradient(135deg, #f0fdf8, #dcfce7); border:1px solid #bbf7d0; border-radius:14px; padding:16px;">
                        <div style="font-size:10px; font-weight:800; color:#059669; text-transform:uppercase; margin-bottom:12px;">🏅 Sertifikat Dinas</div>
                        <div style="display:grid; grid-template-columns: repeat(3, 1fr); gap:10px;">
                            <div>
                                <div style="font-size:10px; color:#6b7280;">No. Sertifikat</div>
                                <div style="font-size:12px; font-weight:700; font-family:'JetBrains Mono';">{{ $toko->no_sertifikat }}</div>
                            </div>
                            <div>
                                <div style="font-size:10px; color:#6b7280;">Tanggal Terbit</div>
                                <div style="font-size:12px; font-weight:700;">{{ $toko->tanggal_sertifikat?->format('d M Y') }}</div>
                            </div>
                            <div>
                                <div style="font-size:10px; color:#6b7280;">Masa Berlaku</div>
                                <div style="font-size:12px; font-weight:700; color: {{ $toko->kadaluarsa_sertifikat?->isPast() ? '#dc2626' : '#059669' }}">
                                    {{ $toko->kadaluarsa_sertifikat?->format('d M Y') }}
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                @if($toko->deskripsi)
                    <div style="margin-top:16px; background:#f9f8f7; border:1px solid #f0eeeb; border-radius:12px; padding:14px;">
                        <div style="font-size:10px; font-weight:800; color:#bbb; text-transform:uppercase; margin-bottom:6px;">Deskripsi Usaha</div>
                        <p style="font-size:13px; color:#555; line-height:1.6; margin:0;">{{ $toko->deskripsi }}</p>
                    </div>
                @endif
            </div>
            @endif
        </div>

        {{-- ===== RIGHT COLUMN ===== --}}
        <div class="col">
            {{-- Document Checklist --}}
            <div class="card">
                <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:16px;">
                    <div style="display:flex; align-items:center; gap:8px; font-size:14px; font-weight:800;">
                        <span style="width:8px; height:8px; border-radius:50%; background:{{ $totalKurang > 0 ? '#dc2626' : '#059669' }}"></span>
                        Kelengkapan Berkas
                    </div>
                    @if($totalKurang > 0)
                        <span style="font-size:11px; font-weight:700; color:#dc2626; background:#fee2e2; padding:2px 10px; border-radius:99px;">{{ $totalKurang }} Kurang</span>
                    @else
                        <span style="font-size:11px; font-weight:700; color:#059669; background:#dcfce7; padding:2px 10px; border-radius:99px;">Lengkap ✓</span>
                    @endif
                </div>

                @foreach($berkas as $b)
                    @php
                        $ada = !empty($toko->{$b['field']});
                        $isFile = in_array($b['field'], ['foto_ktp', 'foto_usaha', 'foto_produk_sample']);
                    @endphp
                    <div class="berkas-item {{ $ada ? 'ada' : ($b['wajib'] ? 'kurang' : '') }}">
                        <div style="display:flex; align-items:center; gap:10px;">
                            <span>{{ $ada ? '✅' : ($b['wajib'] ? '❌' : '⚠️') }}</span>
                            <div>
                                <div style="font-size:12.5px; font-weight:600;">{{ $b['label'] }}</div>
                                @if(!$ada)
                                    <div style="font-size:10px; opacity:0.7;">{{ $b['wajib'] ? 'Wajib' : 'Opsional' }}</div>
                                @endif
                            </div>
                        </div>
                        @if($ada && $isFile)
                            <a href="{{ asset('storage/' . $toko->{$b['field']}) }}" target="_blank" style="font-size:11px; font-weight:700; color:inherit; text-decoration:none;">LIHAT →</a>
                        @endif
                    </div>
                @endforeach
            </div>

            {{-- Action Panel --}}
            <div class="card">
                <div style="font-size:14px; font-weight:800; margin-bottom:16px; display:flex; align-items:center; gap:8px;">
                    <span style="width:8px; height:8px; border-radius:50%; background:#7c3aed"></span>
                    Aksi Verifikasi
                </div>

                {{-- Approval Button --}}
                @if($totalKurang === 0)
                    <form method="POST" action="{{ route('dinas.verifikasi.setujui', $toko->id) }}" style="margin-bottom:12px;">
                        @csrf @method('PATCH')
                        <button type="submit" style="width:100%; background:linear-gradient(135deg,#059669,#047857); color:#fff; border:none; padding:12px; border-radius:12px; font-weight:700; cursor:pointer; box-shadow:0 4px 12px rgba(5,150,105,.2);">
                            {{ $toko->terverifikasi_dinas ? 'Perbarui Sertifikat' : 'Verifikasi & Terbitkan Sertifikat' }}
                        </button>
                    </form>
                @else
                    <div style="background:#f3f4f6; color:#9ca3af; padding:12px; border-radius:12px; text-align:center; font-size:12px; font-weight:700; margin-bottom:12px;">
                        🔒 Verifikasi Dikunci (Berkas Belum Lengkap)
                    </div>
                @endif

                {{-- Visit Scheduling --}}
                <form method="POST" action="{{ route('dinas.verifikasi.kunjungan', $toko->id) }}">
                    @csrf
                    <label class="form-label">Jadwalkan Kunjungan</label>
                    <div style="display:grid; grid-template-columns: 1fr 1fr; gap:8px; margin-bottom:8px;">
                        <input type="date" name="tanggal_kunjungan" required class="input-field" min="{{ date('Y-m-d', strtotime('+1 day')) }}">
                        <input type="time" name="waktu_kunjungan" class="input-field">
                    </div>
                    <button type="submit" class="input-field" style="background:#fff; border:1.5px solid #7c3aed; color:#7c3aed; font-weight:700; cursor:pointer;">Jadwalkan Kunjungan</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection