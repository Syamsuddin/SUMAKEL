@extends('layouts.app')

@section('title', 'Notifikasi')

@php
    $notifIcons = ['DisposisiBaru' => 'diagram-3', 'TindakLanjutBaru' => 'check2-square', 'SuratAntarOpdMasuk' => 'envelope-arrow-down'];
    $unread = auth()->user()->unreadNotifications()->count();
@endphp

@section('content')
<x-page-header title="Notifikasi" :subtitle="$unread > 0 ? $unread.' belum dibaca' : 'Semua notifikasi sudah dibaca'">
    <x-slot:actions>
        @if($unread > 0)
            <form action="{{ route('notifikasi.markAllRead') }}" method="POST" data-loading>
                @csrf
                <button class="btn btn-outline-primary"><x-icon name="check2-all" /> Tandai semua dibaca</button>
            </form>
        @endif
    </x-slot:actions>
</x-page-header>

<div class="card">
    @if($notifications->isEmpty())
        <x-empty-state icon="bell-slash" title="Belum ada notifikasi" text="Disposisi baru, tindak lanjut, dan surat antar-OPD akan muncul di sini." />
    @else
        <div class="list-group list-group-flush">
            @foreach($notifications as $n)
                <a href="{{ route('notifikasi.read', $n->id) }}" class="list-group-item list-group-item-action sk-notif-row {{ $n->read_at ? '' : 'is-unread' }}">
                    <span class="sk-notif-icon"><x-icon :name="$notifIcons[class_basename($n->type)] ?? 'bell'" /></span>
                    <span class="sk-notif-body">
                        <span class="sk-notif-title d-block">{{ $n->data['judul'] ?? 'Notifikasi' }}</span>
                        <span class="d-block text-muted">{{ $n->data['pesan'] ?? '' }}</span>
                    </span>
                    <span class="sk-notif-time" title="{{ $n->created_at->format('d-m-Y H:i') }}">{{ $n->created_at->diffForHumans() }}</span>
                </a>
            @endforeach
        </div>
    @endif
</div>

<x-pagination :paginator="$notifications" />
@endsection
