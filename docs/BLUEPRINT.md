# BLUEPRINT: e-Surat Pemda — Surat Masuk–Keluar Multi-OPD

## Visi & Konteks
Satu platform tata persuratan untuk seluruh OPD dalam satu pemda: pencatatan surat masuk/keluar, disposisi berjenjang dengan pelacakan tindak lanjut, penomoran otomatis per OPD, dan pengiriman surat antar-OPD di dalam sistem (surat keluar OPD A otomatis menjadi surat masuk OPD B). Pengguna: admin TU, pimpinan, dan staf tiap OPD, plus superadmin pemda. MVP sukses bila satu surat dapat ditelusuri penuh — diterima → didisposisi → ditindaklanjuti → selesai — dan surat antar-OPD mengalir tanpa input ulang.

## Stack & Keputusan Arsitektur
| Aspek | Keputusan | Alasan singkat |
|---|---|---|
| Backend | Laravel 11, PHP 8.3 | versi stabil yang dipakai tim |
| Frontend | Blade + Bootstrap 5.3 via Vite; chart.js | dashboard admin klasik, tanpa SPA |
| Database | MySQL 8 (InnoDB) | standar tim |
| Auth & RBAC | `laravel/ui` preset bootstrap + `spatie/laravel-permission` | scaffolding cepat selaras Bootstrap |
| Multi-OPD | Shared DB; kolom `opd_id` + global scope trait `BelongsToOpd`; superadmin bypass | tanpa paket tenancy; akses lintas-OPD tetap mungkin secara terkontrol |
| Penomoran | Tabel `nomor_counters` + `app/Services/PenomoranService.php` (`lockForUpdate`) | bebas duplikat saat akses bersamaan; dipakai modul 02 & 03 |
| Notifikasi | Laravel Notifications: channel `database` + custom `TelegramChannel` (HTTP ke api.telegram.org) | tanpa package tambahan; WhatsApp tinggal menambah channel |
| Lampiran | Tabel polymorphic `lampirans`; file di disk private `storage/app/private/lampiran` | satu mekanisme untuk SM/SK/tindak lanjut; unduh lewat controller ber-policy |
| PDF | `barryvdh/laravel-dompdf` | cetak buku agenda |
| Antar-modul | Laravel Events: modul 02/03 memancarkan, modul 04 mendengarkan | notifikasi tidak membebani modul inti |
| Deploy | VPS Ubuntu 22.04 + Nginx + PHP-FPM; `QUEUE_CONNECTION=database` | queue untuk pengiriman Telegram |

## Peta Modul & Urutan
| # | Modul | SPEC | Bergantung pada | Status |
|---|---|---|---|---|
| 01 | Fondasi: auth, RBAC, master data, layout, penomoran | `docs/SPEC-01-fondasi.md` | — | selesai |
| 02 | Surat masuk, disposisi, tindak lanjut | `docs/SPEC-02-surat-masuk-disposisi.md` | 01 | selesai |
| 03 | Surat keluar, format nomor, routing antar-OPD | `docs/SPEC-03-surat-keluar-routing.md` | 01, 02 | selesai |
| 04 | Notifikasi (in-app + Telegram), dashboard, agenda | `docs/SPEC-04-notifikasi-dashboard.md` | 02, 03 | selesai |

## Entitas Global Bersama
| Entitas | Field krusial | Catatan |
|---|---|---|
| `Opd` | `kode` unique, `nama`, `format_nomor` (pola token), `is_aktif` | master oleh superadmin |
| `User` | `opd_id` FK nullable (null = superadmin), `telegram_chat_id` nullable, `is_aktif` | role via spatie |
| `Klasifikasi` | `kode` unique (mis. `005`), `nama` | global, tanpa `opd_id` |

Role: `superadmin`, `admin_tu`, `pimpinan`, `staf`. Sifat surat (string enum, dipakai modul 02 & 03): `biasa | penting | rahasia`.

## Konvensi Lintas-Modul
- Nama model/tabel memakai istilah domain Indonesia (`SuratMasuk` → `surat_masuks`); method, variabel, dan komentar kode Bahasa Inggris.
- Route kebab-case: `/surat-masuk`, nama route `surat-masuk.index`; resource controller standar.
- Validasi selalu lewat FormRequest; otorisasi lewat Policy per model; logika lintas-model di `app/Services/`.
- Setiap tabel transaksional punya `opd_id` ber-index dan memakai trait `App\Models\Concerns\BelongsToOpd` (global scope + isi otomatis saat create).
- `APP_LOCALE=id`; tanggal tampil `d-m-Y`; seluruh UI Bahasa Indonesia.
- Test Pest + factory tiap model; `./vendor/bin/pint` sebelum commit; conventional commits; satu branch per SPEC (`feat/spec-02-surat-masuk`).

## Out-of-Scope Sistem
- Tanda tangan elektronik (TTE/BSrE) dan validasi legalitas dokumen.
- Generator isi surat (.docx/template); aplikasi hanya mengarsip PDF final.
- Integrasi SRIKANDI/SIKD atau sistem kearsipan eksternal.
- WhatsApp gateway (arsitektur channel sudah siap; implementasi ditunda).
- Aplikasi mobile; ekspor Excel; pemodelan struktur organisasi/eselon formal.

## Aturan Eksekusi
- Satu sesi agen = satu SPEC. Baca BLUEPRINT ini + SPEC terkait saja.
- Selesaikan blok VERIFIKASI tiap fase sebelum lanjut; setelah SPEC terverifikasi, perbarui kolom Status di atas dan `docs/dosir-blueprint.json`.
