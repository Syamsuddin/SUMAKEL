# SUMAKEL — e-Surat Pemda

![Version](https://img.shields.io/badge/version-1.0.0-blue)
![License](https://img.shields.io/badge/license-MIT-green)
![PHP](https://img.shields.io/badge/PHP-8.3-purple)
![Laravel](https://img.shields.io/badge/Laravel-11-red)

Aplikasi tata persuratan elektronik multi-OPD untuk pemerintah daerah. Mencakup pencatatan surat masuk/keluar, disposisi berjenjang dengan pelacakan tindak lanjut, penomoran otomatis per OPD, routing surat antar-OPD, notifikasi in-app & Telegram, serta cetak buku agenda PDF.

## Tech Stack

| Layer | Teknologi |
|-------|-----------|
| Backend | Laravel 11, PHP 8.3 |
| Frontend | Blade + Bootstrap 5.3 (via Vite), Chart.js |
| Database | MySQL 8 / SQLite (dev) |
| Auth & RBAC | laravel/ui + spatie/laravel-permission |
| PDF | barryvdh/laravel-dompdf |
| Queue | Database driver |
| Notifikasi | In-app (database) + Telegram (custom channel) |

## Fitur Utama

- **Multi-OPD** — Shared database dengan isolasi data per OPD via global scope `BelongsToOpd`
- **Surat Masuk** — Pencatatan, penomoran agenda otomatis, upload lampiran (PDF/JPG/PNG), filter & pencarian
- **Disposisi Berantai** — Admin TU ke pimpinan ke staf, timeline kronologis, tindak lanjut + lampiran
- **Akses Surat Rahasia** — Hanya admin TU, pimpinan, atau staf dalam rantai disposisi yang bisa melihat
- **Surat Keluar** — Draft dan penerbitan dengan nomor otomatis sesuai format OPD (`{klasifikasi}/{nomor}/{kode_opd}/{bulan_romawi}/{tahun}`)
- **Routing Antar-OPD** — Surat keluar internal otomatis menjadi surat masuk di OPD tujuan (atomik, lampiran ikut)
- **Notifikasi** — In-app (lonceng navbar) + Telegram bot untuk disposisi baru, tindak lanjut, dan surat antar-OPD
- **Dashboard Per Role** — Widget statistik, grafik volume surat 12 bulan (Chart.js)
- **Buku Agenda** — Gabungan surat masuk & keluar, filter periode/jenis/klasifikasi, cetak PDF landscape

## Role & Hak Akses

| Role | Hak Utama |
|------|-----------|
| `superadmin` | CRUD semua OPD, klasifikasi, user; lihat data lintas-OPD; rekap global |
| `admin_tu` | Catat surat masuk/keluar; disposisi awal; kelola user OPD sendiri |
| `pimpinan` | Disposisi ke staf; lihat seluruh surat OPD |
| `staf` | Terima disposisi; catat tindak lanjut |

## Persyaratan

- PHP >= 8.3
- Composer
- Node.js >= 18 & npm
- MySQL 8 (atau SQLite untuk development)

## Instalasi

```bash
# Clone repository
git clone <repo-url> sumakel
cd sumakel

# Install dependensi
composer install
npm install

# Konfigurasi environment
cp .env.example .env
php artisan key:generate
```

Edit `.env` sesuai database lokal:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=sumakel
DB_USERNAME=root
DB_PASSWORD=
```

Atau gunakan SQLite (default):

```env
DB_CONNECTION=sqlite
```

```bash
# Jalankan migrasi & seeder
php artisan migrate:fresh --seed

# Build assets
npm run build

# Jalankan server
php artisan serve
```

Buka `http://localhost:8000` di browser.

## Akun Demo

| No | Role | Email | Password |
|----|------|-------|----------|
| 1 | Superadmin | superadmin@esurat.test | password |
| 2 | Admin TU (SETDA) | admin_tu.setda@esurat.test | password |
| 3 | Pimpinan (SETDA) | pimpinan.setda@esurat.test | password |
| 4 | Staf (SETDA) | staf.setda@esurat.test | password |
| 5 | Admin TU (DISKOMINFO) | admin_tu.diskominfo@esurat.test | password |
| 6 | Pimpinan (DISKOMINFO) | pimpinan.diskominfo@esurat.test | password |
| 7 | Staf (DISKOMINFO) | staf.diskominfo@esurat.test | password |
| 8 | Admin TU (DINKES) | admin_tu.dinkes@esurat.test | password |
| 9 | Pimpinan (DINKES) | pimpinan.dinkes@esurat.test | password |
| 10 | Staf (DINKES) | staf.dinkes@esurat.test | password |

Daftar lengkap: [USER.md](USER.md)

## Development

```bash
# Jalankan Vite dev server (hot reload)
npm run dev

# Jalankan queue worker (untuk notifikasi Telegram)
php artisan queue:work

# Jalankan test suite
php artisan test

# Jalankan test spesifik
php artisan test --filter=SuratMasukTest

# Cek code style
./vendor/bin/pint --test

# Auto-fix code style
./vendor/bin/pint
```

## Konfigurasi Telegram (Opsional)

1. Buat bot via [@BotFather](https://t.me/BotFather) di Telegram
2. Tambahkan token ke `.env`:

```env
TELEGRAM_BOT_TOKEN=your-bot-token-here
```

3. Setiap user bisa mengisi Telegram Chat ID di halaman Profil
4. Jalankan `php artisan queue:work` agar notifikasi terkirim

## Struktur Modul

```
Modul 01 — Fondasi
  Auth, RBAC (4 role), master data (OPD, Klasifikasi, User), PenomoranService

Modul 02 — Surat Masuk & Disposisi
  CRUD surat masuk, lampiran polymorphic, disposisi berantai, tindak lanjut,
  akses surat rahasia (visibleTo scope)

Modul 03 — Surat Keluar & Routing
  CRUD draft, penerbitan dengan nomor otomatis (NomorSuratService),
  routing antar-OPD (RoutingSuratService), duplikasi lampiran tanpa file fisik

Modul 04 — Notifikasi, Dashboard & Agenda
  3 Notification + 3 Listener (event-driven), TelegramChannel,
  dashboard per role + Chart.js, buku agenda + cetak PDF (dompdf)
```

## Arsitektur

Dokumentasi arsitektur dan keputusan teknis tersedia di:

- [docs/BLUEPRINT.md](docs/BLUEPRINT.md) — Visi, stack, keputusan arsitektur, peta modul
- [docs/SPEC-01-fondasi.md](docs/SPEC-01-fondasi.md) — Spesifikasi modul fondasi
- [docs/SPEC-02-surat-masuk-disposisi.md](docs/SPEC-02-surat-masuk-disposisi.md) — Spesifikasi surat masuk & disposisi
- [docs/SPEC-03-surat-keluar-routing.md](docs/SPEC-03-surat-keluar-routing.md) — Spesifikasi surat keluar & routing
- [docs/SPEC-04-notifikasi-dashboard.md](docs/SPEC-04-notifikasi-dashboard.md) — Spesifikasi notifikasi, dashboard & agenda

## Deploy (Produksi)

```
VPS Ubuntu 22.04 + Nginx + PHP-FPM
QUEUE_CONNECTION=database
php artisan queue:work --daemon
```

Pastikan:
- `storage/` dan `bootstrap/cache/` writable
- `APP_ENV=production`, `APP_DEBUG=false`
- `php artisan config:cache && php artisan route:cache && php artisan view:cache`

## Lisensi

Copyright (c) 2026 @syams_ideris

MIT License — lihat file [LICENSE](LICENSE) untuk detail lengkap.
