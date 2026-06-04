<?php

use App\Http\Controllers\AgendaController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DisposisiController;
use App\Http\Controllers\KlasifikasiController;
use App\Http\Controllers\LampiranController;
use App\Http\Controllers\NotifikasiController;
use App\Http\Controllers\OpdController;
use App\Http\Controllers\ProfilController;
use App\Http\Controllers\SuratKeluarController;
use App\Http\Controllers\SuratMasukController;
use App\Http\Controllers\TindakLanjutController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/login');

Auth::routes(['register' => false]);

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', DashboardController::class)->name('dashboard');

    Route::get('/profil', [ProfilController::class, 'edit'])->name('profil.edit');
    Route::put('/profil', [ProfilController::class, 'update'])->name('profil.update');

    Route::resource('opd', OpdController::class)->except('show');
    Route::resource('klasifikasi', KlasifikasiController::class)->except('show');
    Route::resource('user', UserController::class)->except('show');

    Route::resource('surat-masuk', SuratMasukController::class);
    Route::post('surat-masuk/{surat_masuk}/arsipkan', [SuratMasukController::class, 'arsipkan'])->name('surat-masuk.arsipkan');

    Route::resource('surat-keluar', SuratKeluarController::class);
    Route::post('surat-keluar/{surat_keluar}/terbitkan', [SuratKeluarController::class, 'terbitkan'])->name('surat-keluar.terbitkan');
    Route::post('surat-keluar/{surat_keluar}/arsipkan', [SuratKeluarController::class, 'arsipkan'])->name('surat-keluar.arsipkan');

    Route::post('surat-masuk/{surat_masuk}/disposisi', [DisposisiController::class, 'store'])->name('disposisi.store');
    Route::post('disposisi/{disposisi}/tindak-lanjut', [TindakLanjutController::class, 'store'])->name('tindak-lanjut.store');

    Route::get('notifikasi', [NotifikasiController::class, 'index'])->name('notifikasi.index');
    Route::get('notifikasi/{id}/read', [NotifikasiController::class, 'markRead'])->name('notifikasi.read');
    Route::post('notifikasi/mark-all-read', [NotifikasiController::class, 'markAllRead'])->name('notifikasi.markAllRead');

    Route::get('agenda', [AgendaController::class, 'index'])->name('agenda.index');
    Route::get('agenda/cetak', [AgendaController::class, 'cetak'])->name('agenda.cetak');

    Route::get('lampiran/{lampiran}/download', [LampiranController::class, 'download'])->name('lampiran.download');
});
