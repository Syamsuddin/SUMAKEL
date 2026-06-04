@extends('layouts.app')

@section('title', 'Detail Surat Keluar')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4>Surat Keluar {{ $suratKeluar->nomor ?? '(Draft)' }}</h4>
    <div>
        @can('update', $suratKeluar)
            <a href="{{ route('surat-keluar.edit', $suratKeluar) }}" class="btn btn-sm btn-outline-primary">Edit</a>
        @endcan
        @can('terbitkan', $suratKeluar)
            <form action="{{ route('surat-keluar.terbitkan', $suratKeluar) }}" method="POST" class="d-inline">
                @csrf
                <button class="btn btn-sm btn-success" onclick="return confirm('Terbitkan surat ini? Nomor akan digenerate dan tidak dapat diubah.')">Terbitkan</button>
            </form>
        @endcan
        @can('arsipkan', $suratKeluar)
            <form action="{{ route('surat-keluar.arsipkan', $suratKeluar) }}" method="POST" class="d-inline">
                @csrf
                <button class="btn btn-sm btn-outline-secondary" onclick="return confirm('Arsipkan surat ini?')">Arsipkan</button>
            </form>
        @endcan
        <a href="{{ route('surat-keluar.index') }}" class="btn btn-sm btn-secondary">Kembali</a>
    </div>
</div>

<div class="card mb-3">
    <div class="card-body">
        <table class="table table-borderless mb-0">
            @if($suratKeluar->nomor)
                <tr><th width="180">Nomor</th><td><strong>{{ $suratKeluar->nomor }}</strong></td></tr>
            @endif
            <tr><th width="180">Klasifikasi</th><td>{{ $suratKeluar->klasifikasi->kode }} - {{ $suratKeluar->klasifikasi->nama }}</td></tr>
            <tr><th>Tanggal Surat</th><td>{{ $suratKeluar->tanggal_surat->format('d-m-Y') }}</td></tr>
            <tr><th>Perihal</th><td>{{ $suratKeluar->perihal }}</td></tr>
            <tr>
                <th>Tujuan</th>
                <td>
                    @if($suratKeluar->jenis_tujuan === 'internal')
                        <span class="badge bg-info">Internal</span> {{ $suratKeluar->tujuanOpd?->nama }}
                    @else
                        <span class="badge bg-secondary">Eksternal</span> {{ $suratKeluar->tujuan_eksternal }}
                    @endif
                </td>
            </tr>
            <tr>
                <th>Sifat</th>
                <td>
                    <span class="badge bg-{{ $suratKeluar->sifat === 'rahasia' ? 'danger' : ($suratKeluar->sifat === 'penting' ? 'warning' : 'info') }}">
                        {{ ucfirst($suratKeluar->sifat) }}
                    </span>
                </td>
            </tr>
            <tr>
                <th>Status</th>
                <td>
                    <span class="badge bg-{{ $suratKeluar->status === 'terbit' ? 'success' : ($suratKeluar->status === 'diarsip' ? 'secondary' : 'warning') }}">
                        {{ ucfirst($suratKeluar->status) }}
                    </span>
                </td>
            </tr>
        </table>
    </div>
</div>

@if($suratKeluar->suratMasukTujuan)
    <div class="alert alert-info">
        Diterima sebagai agenda #{{ $suratKeluar->suratMasukTujuan->nomor_agenda }} di {{ $suratKeluar->tujuanOpd?->nama }}.
    </div>
@endif

@if($suratKeluar->lampirans->isNotEmpty())
    <div class="card">
        <div class="card-header">Lampiran</div>
        <div class="list-group list-group-flush">
            @foreach($suratKeluar->lampirans as $lampiran)
                <a href="{{ route('lampiran.download', $lampiran) }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                    {{ $lampiran->nama_asli }}
                    <span class="badge bg-secondary">{{ number_format($lampiran->ukuran / 1024, 1) }} KB</span>
                </a>
            @endforeach
        </div>
    </div>
@endif
@endsection
