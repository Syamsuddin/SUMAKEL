# Changelog

Semua perubahan penting pada SUMAKEL dicatat di berkas ini.
Format mengikuti [Keep a Changelog](https://keepachangelog.com/id/1.1.0/) dan penomoran mengikuti [Semantic Versioning](https://semver.org/lang/id/).

## [Unreleased]

## [1.0.0] — 2026-09-17

Rilis pertama SUMAKEL (e-Surat Pemda): platform tata persuratan multi-OPD untuk satu pemerintah daerah.

### Ditambahkan
- **Fondasi** — autentikasi, 4 peran (`superadmin`, `admin_tu`, `pimpinan`, `staf`) via spatie/laravel-permission, isolasi multi-OPD lewat `opd_id` + global scope `BelongsToOpd`, master data OPD/Klasifikasi/Pengguna, `PenomoranService` aman-konkurensi (`lockForUpdate`).
- **Surat masuk** — pencatatan dengan nomor agenda otomatis, lampiran polimorfik (PDF/JPG/PNG, unduh ber-policy), disposisi berantai dengan batas waktu, tindak lanjut berlampiran, arsip; surat `rahasia` hanya terlihat oleh admin TU, pimpinan se-OPD, dan rantai disposisi.
- **Surat keluar** — draft → terbit dengan nomor diformat per OPD (`{klasifikasi}/{nomor}/{kode_opd}/{bulan_romawi}/{tahun}`), routing antar-OPD: surat terbit ke OPD lain otomatis menjadi surat masuk di OPD tujuan (`RoutingSuratService`, transaksi atomik, lampiran dirujuk tanpa duplikasi file).
- **Notifikasi & dashboard** — notifikasi in-app + Telegram (channel kustom, via queue) untuk disposisi baru, tindak lanjut, dan surat antar-OPD; dashboard per peran; buku agenda per periode dengan cetak PDF berkop.
- **Antarmuka "Institusional Modern"** — palet navy/biru instansi, Lexend + Source Sans 3, Bootstrap Icons, app shell (sidebar berlambang Pemda + nama OPD, topbar breadcrumb, notifikasi), login split-screen, kop dokumen, timeline disposisi bergaya stepper, stepper terbit surat keluar, tabel → kartu di layar sempit, empty state beraksi, modal konfirmasi, kartu statistik.
- **Aksesibilitas** — kontras teks ≥ 4.5:1 (audit otomatis), skip link, focus ring, target sentuh ≥ 44 px di perangkat sentuh, `prefers-reduced-motion`, fokus kembali ke pemicu setelah modal ditutup, stylesheet cetak.
- **Identitas Pemda** dapat dikonfigurasi lewat `APP_PEMDA`, `APP_TAGLINE`, `APP_LOGO`.
- **Dokumentasi** — panduan operasional per peran, panduan instalasi VPS (Ubuntu 22.04, Nginx, PHP-FPM, queue, Telegram), daftar akun demo.
- **Pengujian** — 46 tes Pest (108 assertion) dan smoke test end-to-end 85 langkah lewat browser (`scripts/smoke-test.sh`).

### Diperbaiki
- Notifikasi terkirim dua kali per event karena listener terdaftar manual sekaligus lewat auto-discovery Laravel 11.
- Relasi `SuratKeluar::suratMasukTujuan` terfilter scope OPD pengirim sehingga informasi "diterima sebagai agenda" dan langkah "Terkirim ke OPD" tidak pernah tampil.
- Paginasi memakai view Bootstrap 5 (view bawaan Tailwind tidak lagi ber-CSS).

### Dihapus
- Tailwind CSS, `resources/css/app.css`, `welcome.blade.php`, dan view `register`/`verify` yang tidak dipakai; Chart.js dimuat dari bundle, bukan CDN.

[Unreleased]: https://github.com/Syamsuddin/SUMAKEL/compare/v1.0.0...HEAD
[1.0.0]: https://github.com/Syamsuddin/SUMAKEL/releases/tag/v1.0.0
