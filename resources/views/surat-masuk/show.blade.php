@extends('layouts.app')

@section('title', 'Detail Surat Masuk')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4>Surat Masuk #{{ $suratMasuk->nomor_agenda }}</h4>
    <div>
        @can('update', $suratMasuk)
            <a href="{{ route('surat-masuk.edit', $suratMasuk) }}" class="btn btn-sm btn-outline-primary">Edit</a>
        @endcan
        @can('arsipkan', $suratMasuk)
            <form action="{{ route('surat-masuk.arsipkan', $suratMasuk) }}" method="POST" class="d-inline">
                @csrf
                <button class="btn btn-sm btn-outline-secondary" onclick="return confirm('Arsipkan surat ini?')">Arsipkan</button>
            </form>
        @endcan
        <a href="{{ route('surat-masuk.index') }}" class="btn btn-sm btn-secondary">Kembali</a>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card mb-3">
            <div class="card-body">
                <table class="table table-borderless mb-0">
                    <tr><th width="180">Nomor Surat</th><td>{{ $suratMasuk->nomor_surat }}</td></tr>
                    <tr><th>Asal Surat</th><td>{{ $suratMasuk->asal_surat }}</td></tr>
                    <tr><th>Klasifikasi</th><td>{{ $suratMasuk->klasifikasi->kode }} - {{ $suratMasuk->klasifikasi->nama }}</td></tr>
                    <tr><th>Tanggal Surat</th><td>{{ $suratMasuk->tanggal_surat->format('d-m-Y') }}</td></tr>
                    <tr><th>Tanggal Terima</th><td>{{ $suratMasuk->tanggal_terima->format('d-m-Y') }}</td></tr>
                    <tr><th>Perihal</th><td>{{ $suratMasuk->perihal }}</td></tr>
                    <tr>
                        <th>Sifat</th>
                        <td>
                            <span class="badge bg-{{ $suratMasuk->sifat === 'rahasia' ? 'danger' : ($suratMasuk->sifat === 'penting' ? 'warning' : 'info') }}">
                                {{ ucfirst($suratMasuk->sifat) }}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td>
                            <span class="badge bg-{{ $suratMasuk->status === 'selesai' ? 'success' : ($suratMasuk->status === 'diarsip' ? 'secondary' : 'primary') }}">
                                {{ ucfirst($suratMasuk->status) }}
                            </span>
                        </td>
                    </tr>
                    <tr><th>No. Agenda</th><td>{{ $suratMasuk->nomor_agenda }}</td></tr>
                </table>
            </div>
        </div>

        @if($suratMasuk->lampirans->isNotEmpty())
            <div class="card mb-3">
                <div class="card-header">Lampiran</div>
                <div class="list-group list-group-flush">
                    @foreach($suratMasuk->lampirans as $lampiran)
                        <a href="{{ route('lampiran.download', $lampiran) }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            {{ $lampiran->nama_asli }}
                            <span class="badge bg-secondary">{{ number_format($lampiran->ukuran / 1024, 1) }} KB</span>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif

        @include('surat-masuk._timeline', ['suratMasuk' => $suratMasuk])
    </div>

    <div class="col-md-4">
        @if($suratMasuk->status !== 'diarsip')
            @include('surat-masuk._form_disposisi', ['suratMasuk' => $suratMasuk, 'usersSeOpd' => $usersSeOpd])
        @endif
    </div>
</div>
@endsection
