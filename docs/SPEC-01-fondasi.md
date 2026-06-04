# SPEC-01: Modul Fondasi
> Induk: `docs/BLUEPRINT.md` — baca dulu. Bergantung pada: —.

## Kontrak dengan Modul Lain
| Arah | Antarmuka | Bentuk |
|---|---|---|
| Menyediakan | Auth session + 4 role spatie | login web, middleware `role:` |
| Menyediakan | Trait `App\Models\Concerns\BelongsToOpd` | global scope `opd_id` + auto-fill; superadmin bypass |
| Menyediakan | `App\Services\PenomoranService::next(int $opdId, string $jenis, int $tahun): int` | counter aman-konkurensi |
| Menyediakan | Layout `resources/views/layouts/app.blade.php` | sidebar + navbar (slot `@stack('navbar-extra')` untuk lonceng modul 04) + `@yield('content')` |
| Menyediakan | Master `Opd`, `Klasifikasi`, `User` + seeder demo | dipakai semua modul |

## Model Data
| Entitas | Field krusial | Relasi |
|---|---|---|
| `opds` | `kode` unique, `nama`, `format_nomor` default `{klasifikasi}/{nomor}/{kode_opd}/{bulan_romawi}/{tahun}`, `is_aktif` | 1—n `users` |
| `users` (alter) | `opd_id` FK nullable, `telegram_chat_id` nullable, `is_aktif` | role via spatie |
| `klasifikasis` | `kode` unique, `nama` | global |
| `nomor_counters` | `opd_id`, `jenis`, `tahun`, `nilai`; unique(`opd_id`,`jenis`,`tahun`) | dikunci `lockForUpdate` |

## Role & Hak
| Role | Hak utama |
|---|---|
| superadmin | CRUD OPD, klasifikasi, semua user; lihat data lintas-OPD |
| admin_tu | CRUD user OPD-nya; operasional surat (modul 02/03) |
| pimpinan | disposisi & melihat seluruh surat OPD-nya |
| staf | menerima disposisi, mencatat tindak lanjut |

## Fase Pengerjaan
### Fase 1 — Skeleton, auth, layout dashboard
- `composer create-project laravel/laravel`; pasang `laravel/ui` (`php artisan ui bootstrap --auth`) dan `spatie/laravel-permission`; `npm i bootstrap@5.3 chart.js`. Registrasi publik DIMATIKAN — user dibuat oleh admin.
- Migration `opds`, `klasifikasis`, alter `users`; `RoleSeeder` + `DemoSeeder`: 3 OPD (SETDA, DISKOMINFO, DINKES), 1 superadmin, 1 user per role per OPD (password seragam `password`).
- Layout dashboard: `layouts/app.blade.php` + partial `layouts/sidebar.blade.php` (menu per role via `@role`), navbar (dropdown profil, slot lonceng), breadcrumb sederhana. Halaman `/dashboard` placeholder menyapa nama + role.
- File: `routes/web.php`, `app/Http/Controllers/DashboardController.php`, `database/seeders/*`.
- VERIFIKASI: `php artisan migrate:fresh --seed` tanpa error; `php artisan test --filter=AuthTest` → login tiap role mendarat di `/dashboard` (200); `./vendor/bin/pint --test` bersih.

### Fase 2 — Scope multi-OPD, master data, penomoran
- Trait `BelongsToOpd`: global scope `where opd_id = auth user` kecuali superadmin; auto-fill `opd_id` pada event `creating`.
- CRUD `Opd` & `Klasifikasi` (superadmin); CRUD `User` (admin_tu untuk OPD-nya; superadmin bebas memilih OPD; pilihan role dibatasi non-superadmin). FormRequest + Policy tiap model.
- `PenomoranService::next()`: transaksi → `firstOrCreate` baris counter → `lockForUpdate` → increment → kembalikan nilai baru.
- File: `app/Models/{Opd,Klasifikasi}.php`, `app/Http/Controllers/{OpdController,KlasifikasiController,UserController}.php`, `app/Services/PenomoranService.php`, views `master/*`.
- VERIFIKASI: `php artisan test` hijau, termasuk: admin_tu OPD A tidak melihat user OPD B (404), superadmin melihat semua; `PenomoranServiceTest` memanggil `next()` 50× → hasil 1..50 unik berurutan.

### Fase 3 — Pengerasan
- Validasi seluruh form + pesan Bahasa Indonesia (`lang/id`), empty state tabel, halaman profil (ganti password, isi `telegram_chat_id`).
- VERIFIKASI: seluruh suite hijau; `pint --test` bersih; `migrate:fresh --seed` ulang tanpa error.

## Out-of-Scope Modul
- Fitur surat apa pun (modul 02/03); reset password via email; foto profil; manajemen menu dinamis.

## Verifikasi Akhir (end-to-end)
Login superadmin → buat OPD baru + 1 admin_tu → logout → login admin_tu baru → buat 1 staf → daftar user hanya berisi OPD-nya sendiri.
