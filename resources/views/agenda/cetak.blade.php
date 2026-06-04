<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Agenda {{ $dari }} - {{ $sampai }}</title>
    <style>
        body { font-family: sans-serif; font-size: 11px; }
        h2, h3 { text-align: center; margin: 0; }
        h3 { margin-bottom: 10px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #333; padding: 4px 6px; text-align: left; }
        th { background: #eee; }
        .kop { text-align: center; margin-bottom: 15px; border-bottom: 2px solid #333; padding-bottom: 10px; }
    </style>
</head>
<body>
    <div class="kop">
        <h2>PEMERINTAH DAERAH</h2>
        @if($opd)
            <h3>{{ strtoupper($opd->nama) }}</h3>
        @endif
    </div>

    <h3>BUKU AGENDA SURAT</h3>
    <p style="text-align: center;">Periode: {{ $dari }} s/d {{ $sampai }}</p>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Jenis</th>
                <th>Nomor Surat</th>
                <th>Perihal</th>
                <th>Tanggal</th>
                <th>Asal/Tujuan</th>
                <th>Klasifikasi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($items as $i => $item)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $item['jenis'] }}</td>
                    <td>{{ $item['nomor'] }}</td>
                    <td>{{ $item['perihal'] }}</td>
                    <td>{{ $item['tanggal'] }}</td>
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
</body>
</html>
