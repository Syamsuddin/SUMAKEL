<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Buku Agenda {{ $dari }} - {{ $sampai }}</title>
    <style>
        @page { margin: 18mm 15mm; }
        body { font-family: 'EB Garamond', 'DejaVu Serif', Georgia, serif; font-size: 11pt; color: #020617; }
        .kop { width: 100%; border-bottom: 3px double #0F172A; padding-bottom: 8px; margin-bottom: 14px; }
        .kop td { vertical-align: middle; border: 0; padding: 0; }
        .kop-logo { width: 70px; }
        .kop-logo img { width: 62px; height: auto; }
        .kop-teks { text-align: center; }
        .kop-teks .pemda { font-size: 13pt; letter-spacing: 1px; text-transform: uppercase; }
        .kop-teks .opd { font-size: 15pt; font-weight: bold; text-transform: uppercase; margin: 2px 0; }
        .kop-teks .app { font-size: 9pt; color: #64748B; }
        .gold { border-top: 1px solid #B45309; margin-top: 3px; }
        h1 { font-size: 13pt; text-align: center; text-transform: uppercase; letter-spacing: 1px; margin: 12px 0 2px; }
        .periode { text-align: center; font-size: 10pt; margin: 0 0 10px; color: #334155; }
        table.data { width: 100%; border-collapse: collapse; font-size: 9.5pt; }
        table.data th, table.data td { border: 1px solid #94A3B8; padding: 4px 5px; text-align: left; vertical-align: top; }
        table.data th { background: #E8ECF1; font-size: 9pt; text-transform: uppercase; letter-spacing: .5px; }
        table.data td.num { text-align: right; width: 28px; }
        table.data td.tgl { white-space: nowrap; }
        .footer { margin-top: 14px; font-size: 8.5pt; color: #64748B; text-align: right; }
    </style>
</head>
<body>
    <table class="kop">
        <tr>
            <td class="kop-logo"><img src="{{ public_path(config('app.logo')) }}" alt=""></td>
            <td class="kop-teks">
                <div class="pemda">{{ config('app.pemda') }}</div>
                @if($opd)
                    <div class="opd">{{ $opd->nama }}</div>
                @endif
                <div class="app">{{ config('app.name') }} &mdash; Sistem Tata Persuratan Elektronik</div>
            </td>
            <td class="kop-logo"></td>
        </tr>
    </table>
    <div class="gold"></div>

    <h1>Buku Agenda Surat</h1>
    <p class="periode">Periode {{ \Carbon\Carbon::parse($dari)->format('d-m-Y') }} s.d. {{ \Carbon\Carbon::parse($sampai)->format('d-m-Y') }}</p>

    <table class="data">
        <thead>
            <tr>
                <th>No</th>
                <th>Jenis</th>
                <th>Nomor Surat</th>
                <th>Perihal</th>
                <th>Tanggal</th>
                <th>Asal/Tujuan</th>
                <th>Klas.</th>
            </tr>
        </thead>
        <tbody>
            @forelse($items as $i => $item)
                <tr>
                    <td class="num">{{ $i + 1 }}</td>
                    <td>{{ $item['jenis'] }}</td>
                    <td>{{ $item['nomor'] ?? '-' }}</td>
                    <td>{{ $item['perihal'] }}</td>
                    <td class="tgl">{{ $item['tanggal'] }}</td>
                    <td>{{ $item['pihak'] }}</td>
                    <td>{{ $item['klasifikasi'] }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="text-align: center;">Tidak ada data.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">Dicetak {{ now()->format('d-m-Y H:i') }} oleh {{ auth()->user()->name }} &middot; {{ $items->count() }} surat</div>
</body>
</html>
