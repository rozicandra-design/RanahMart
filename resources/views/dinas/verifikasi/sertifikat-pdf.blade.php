<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<style>
    @page { margin: 0; size: A4 portrait; }
    * { margin: 0; padding: 0; box-sizing: border-box; }
    
    body {
        width: 210mm; height: 297mm;
        font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
        background: #f0f0f0;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .page {
        width: 210mm; height: 297mm;
        background: #fff;
        border: 12px solid #1a3568;
        display: flex;
        flex-direction: column;
        position: relative;
        overflow: hidden;
    }

    /* Decorative Inner Border */
    .border-inner {
        position: absolute;
        inset: 15px;
        border: 2px solid #b8962e;
        pointer-events: none;
        z-index: 10;
    }

    /* Corner Ornaments */
    .corner { position: absolute; width: 45px; height: 45px; z-index: 11; }
    .co-tl { top: 10px; left: 10px; }
    .co-tr { top: 10px; right: 10px; transform: scaleX(-1); }
    .co-bl { bottom: 10px; left: 10px; transform: scaleY(-1); }
    .co-br { bottom: 10px; right: 10px; transform: scale(-1); }

    /* Watermark */
    .watermark {
        position: absolute;
        top: 55%; left: 50%;
        transform: translate(-50%, -50%);
        opacity: 0.05;
        pointer-events: none;
        text-align: center;
        z-index: 0;
    }

    /* Header Section */
    .header {
        background: #1a3568;
        padding: 35px 40px 25px;
        text-align: center;
        color: white;
    }
    .logo-circle {
        width: 65px; height: 65px;
        background: #b8962e;
        border-radius: 50%;
        margin: 0 auto 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 28px;
        font-weight: bold;
        color: #1a3568;
        font-family: 'Georgia', serif;
        box-shadow: 0 4px 8px rgba(0,0,0,0.2);
    }
    .gov-text { font-size: 10px; letter-spacing: 4px; text-transform: uppercase; color: #b8962e; font-weight: 700; }
    .dept-text { font-family: 'Georgia', serif; font-size: 18px; margin-top: 5px; font-weight: 700; }

    /* Content Body */
    .content {
        flex: 1;
        display: flex;
        flex-direction: column;
        align-items: center;
        padding: 40px 60px;
        text-align: center;
        z-index: 1;
    }

    .cert-title {
        font-size: 32px;
        font-weight: 700;
        color: #1a3568;
        font-family: 'Georgia', serif;
        margin-bottom: 5px;
        text-transform: uppercase;
    }
    
    .divider {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 80%;
        margin: 20px 0;
    }
    .line { flex: 1; height: 1.5px; background: linear-gradient(to right, transparent, #b8962e, transparent); }
    .diamond { width: 10px; height: 10px; background: #b8962e; transform: rotate(45deg); margin: 0 15px; }

    .recipient-label { font-size: 12px; color: #666; font-style: italic; margin-bottom: 15px; }
    .store-name {
        font-size: 36px;
        font-weight: 800;
        color: #1a3568;
        font-family: 'Georgia', serif;
        margin-bottom: 10px;
        border-bottom: 3px double #b8962e;
        display: inline-block;
        padding-bottom: 5px;
    }

    .info-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 15px;
        width: 100%;
        margin-top: 30px;
    }
    .info-card {
        background: #fdfaf3;
        border-left: 4px solid #b8962e;
        padding: 12px 20px;
        text-align: left;
        border-radius: 4px;
    }
    .info-label { font-size: 9px; text-transform: uppercase; color: #888; letter-spacing: 1px; margin-bottom: 4px; }
    .info-value { font-size: 13px; font-weight: 700; color: #1a3568; }

    /* Footer Section */
    .footer {
        padding: 0 60px 50px;
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
    }
    .sign-box { text-align: center; width: 220px; }
    .sign-line { border-top: 1.5px solid #1a3568; margin-bottom: 8px; }
    
    .stamp-area {
        position: relative;
        display: flex;
        flex-direction: column;
        align-items: center;
    }
    .stamp-outer {
        width: 80px; height: 80px;
        border: 2px dashed #1a3568;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 5px;
    }
    .stamp-inner {
        width: 65px; height: 65px;
        border: 2px solid #b8962e;
        border-radius: 50%;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        color: #b8962e;
        font-size: 8px;
        font-weight: bold;
        line-height: 1;
    }

    .qr-code {
        width: 70px; height: 70px;
        padding: 5px;
        background: #1a3568;
        border-radius: 4px;
    }
</style>
</head>
<body>

<div class="page">
    <div class="border-inner"></div>

    <svg class="corner co-tl" viewBox="0 0 38 38" fill="none"><rect x="0" y="0" width="12" height="12" fill="#b8962e"/><rect x="14" y="0" width="24" height="3" fill="#b8962e"/><rect x="0" y="14" width="3" height="24" fill="#b8962e"/><rect x="5" y="5" width="7" height="7" fill="#1a3568"/></svg>
    <svg class="corner co-tr" viewBox="0 0 38 38" fill="none"><rect x="0" y="0" width="12" height="12" fill="#b8962e"/><rect x="14" y="0" width="24" height="3" fill="#b8962e"/><rect x="0" y="14" width="3" height="24" fill="#b8962e"/><rect x="5" y="5" width="7" height="7" fill="#1a3568"/></svg>
    <svg class="corner co-bl" viewBox="0 0 38 38" fill="none"><rect x="0" y="0" width="12" height="12" fill="#b8962e"/><rect x="14" y="0" width="24" height="3" fill="#b8962e"/><rect x="0" y="14" width="3" height="24" fill="#b8962e"/><rect x="5" y="5" width="7" height="7" fill="#1a3568"/></svg>
    <svg class="corner co-br" viewBox="0 0 38 38" fill="none"><rect x="0" y="0" width="12" height="12" fill="#b8962e"/><rect x="14" y="0" width="24" height="3" fill="#b8962e"/><rect x="0" y="14" width="3" height="24" fill="#b8962e"/><rect x="5" y="5" width="7" height="7" fill="#1a3568"/></svg>

    <div class="watermark">
        <svg width="250" height="250" viewBox="0 0 24 24" fill="#1a3568"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/><polyline points="9 22 9 12 15 12 15 22" fill="#1a3568"/></svg>
        <div style="font-size:24px; font-weight:900; color:#1a3568; font-family:Georgia,serif; margin-top:10px;">RANAH MART</div>
    </div>

    <div class="header">
        <div class="logo-circle">R</div>
        <div class="gov-text">Pemerintah Kota Padang</div>
        <div class="dept-text">Dinas Koperasi & Usaha Mikro Kecil Menengah</div>
        <div style="font-size:11px; opacity:0.8; margin-top:5px;">Jl. Khatib Sulaiman No. 1, Padang &mdash; Sumatera Barat</div>
        
        <div style="margin-top:20px; font-size:10px; letter-spacing:1px; background: rgba(184,150,46,0.2); display:inline-block; padding:4px 15px; border-radius:20px; border:1px solid #b8962e;">
            Sertifikat No: {{ $toko->no_sertifikat ?? 'SK/UMKM/'.date('Y').'/0001' }}
        </div>
    </div>

    <div class="content">
        <div style="font-size:10px; letter-spacing:5px; color:#b8962e; font-weight:700; margin-bottom:10px;">SERTIFIKAT VERIFIKASI</div>
        <h1 class="cert-title">PELAKU USAHA UMKM</h1>
        
        <div class="divider">
            <div class="line"></div>
            <div class="diamond"></div>
            <div class="line"></div>
        </div>

        <p class="recipient-label">Sertifikat ini diberikan sebagai bentuk validasi kepada:</p>
        <h2 class="store-name">{{ $toko->nama_toko ?? 'NAMA TOKO ANDA' }}</h2>
        
        <div style="margin-top:10px;">
            <p style="font-size:14px; color:#1a3568;">Pemilik: <strong>{{ $toko->user->nama_lengkap ?? 'Nama Pemilik' }}</strong></p>
            <p style="font-size:12px; color:#666; margin-top:5px;">NIB: {{ $toko->nib ?? '000000000000' }}</p>
        </div>

        <div class="info-grid">
            <div class="info-card">
                <div class="info-label">Kategori Usaha</div>
                <div class="info-value">{{ $toko->kategori_friendly ?? 'Kuliner / Kerajinan' }}</div>
            </div>
            <div class="info-card">
                <div class="info-label">Wilayah Kerja</div>
                <div class="info-value">Kec. {{ $toko->kecamatan ?? 'Padang Barat' }}</div>
            </div>
            <div class="info-card">
                <div class="info-label">Tanggal Terbit</div>
                <div class="info-value">{{ date('d M Y') }}</div>
            </div>
            <div class="info-card">
                <div class="info-label">Masa Berlaku</div>
                <div class="info-value">{{ date('d M Y', strtotime('+2 years')) }}</div>
            </div>
        </div>
        
        <p style="margin-top:30px; font-size:11px; color:#888; line-height:1.6; max-width: 80%;">
            Telah terdaftar dan terverifikasi secara resmi pada Platform RanahMart sebagai unit Usaha Mikro Kecil Menengah yang aktif di Kota Padang.
        </p>
    </div>

    <div class="footer">
        <div class="sign-box">
            <div style="font-size:11px; margin-bottom:60px; color:#555;">Mengetahui,</div>
            <div class="sign-line"></div>
            <div style="font-size:12px; font-weight:700; color:#1a3568;">Ir. H. Ahmad Fauzi, M.Si</div>
            <div style="font-size:10px; color:#888;">NIP. 19750812 200003 1 002</div>
        </div>

        <div class="stamp-area">
            <div class="stamp-outer">
                <div class="stamp-inner">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor" style="margin-bottom:3px;"><path d="M12 2L15.09 8.26L22 9.27L17 14.14L18.18 21.02L12 17.77L5.82 21.02L7 14.14L2 9.27L8.91 8.26L12 2Z"/></svg>
                    <span>RESMI</span>
                    <span>VERIFIED</span>
                </div>
            </div>
            <div style="font-size:9px; color:#aaa; font-style:italic;">Stempel Digital</div>
        </div>

        <div class="sign-box">
            <div style="font-size:10px; color:#888; margin-bottom:8px;">Verifikasi Keaslian</div>
            <div style="display: flex; justify-content: center;">
                <div class="qr-code">
                    <div style="display:grid; grid-template-columns: repeat(4,1fr); gap:2px; height:100%;">
                        <div style="background:#fff;"></div><div style="background:#1a3568;"></div><div style="background:#fff;"></div><div style="background:#fff;"></div>
                        <div style="background:#1a3568;"></div><div style="background:#fff;"></div><div style="background:#1a3568;"></div><div style="background:#1a3568;"></div>
                        <div style="background:#fff;"></div><div style="background:#1a3568;"></div><div style="background:#fff;"></div><div style="background:#fff;"></div>
                        <div style="background:#1a3568;"></div><div style="background:#1a3568;"></div><div style="background:#fff;"></div><div style="background:#1a3568;"></div>
                    </div>
                </div>
            </div>
            <div style="font-size:9px; color:#aaa; margin-top:5px;">Scan QR Code</div>
        </div>
    </div>
</div>

</body>
</html>