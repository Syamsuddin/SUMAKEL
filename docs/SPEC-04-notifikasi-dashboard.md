# SPEC-04: Modul Notifikasi, Dashboard & Agenda
> Induk: `docs/BLUEPRINT.md` — baca dulu. Bergantung pada: SPEC-02, SPEC-03.

## Kontrak dengan Modul Lain
| Arah | Antarmuka | Bentuk |
|---|---|---|
| Mengonsumsi | `DisposisiDibuat`, `TindakLanjutDicatat` (02); `SuratAntarOpdTerkirim` (03) | listener → Notification |
| Mengonsumsi | `users.telegram_chat_id`, slot layout `@stack('navbar-extra')` | dari SPEC-01 |
| Menyediakan | Lonceng notifikasi navbar, `/notifikasi`, `/dashboard` final, `/agenda` + cetak PDF | UI lintas modul |

## Pemetaan Notifikasi
| Event | Notification | Penerima |
|---|---|---|
| `DisposisiDibuat` | `DisposisiBaru` | `kepada_user` |
| `TindakLanjutDicatat` | `TindakLanjutBaru` | `dari_user` disposisi terkait |
| `SuratAntarOpdTerkirim` | `SuratAntarOpdMasuk` | semua `admin_tu` OPD tujuan |

## Fase Pengerjaan
### Fase 1 — Notifikasi in-app
- `php artisan notifications:table`; tiga kelas Notification (channel `database`) berisi judul, ringkasan, URL tujuan; tiga listener didaftarkan di `EventServiceProvider`.
- Lonceng navbar mengisi `@stack('navbar-extra')`: badge jumlah belum dibaca, dropdown 10 terbaru, klik → tandai dibaca → redirect ke URL; halaman `/notifikasi` (semua notifikasi + tombol "tandai semua dibaca").
- File: `app/Notifications/{DisposisiBaru,TindakLanjutBaru,SuratAntarOpdMasuk}.php`, `app/Listeners/*`, `NotifikasiController`, partial `layouts/_notifikasi.blade.php`.
- VERIFIKASI: `php artisan test --filter=NotifikasiTest` — dengan `Notification::fake()` tiap event mengirim ke penerima yang tepat; lonceng menampilkan badge sesuai jumlah; `pint --test` bersih.

### Fase 2 — Channel Telegram
- `app/Notifications/Channels/TelegramChannel.php`: `Http::post` ke `https://api.telegram.org/bot{token}/sendMessage`; token di `config/services.php` ← env `TELEGRAM_BOT_TOKEN`. Method `via()` menambah channel ini bila `telegram_chat_id` terisi; semua Notification `implements ShouldQueue` (queue `database`); gagal kirim → `Log::warning` via try-catch, jangan gagalkan antrean.
- Halaman profil (SPEC-01) ditambah tombol "Kirim tes Telegram".
- VERIFIKASI: test dengan `Http::fake()` → payload `chat_id` & `text` benar; `Queue::fake()` memastikan notifikasi diantrekan; user tanpa `chat_id` tidak memicu HTTP request.

### Fase 3 — Dashboard, agenda, cetak PDF
- `/dashboard` final per role: admin_tu (SM/SK bulan ini, SM belum disposisi), pimpinan (disposisi menunggu), staf (tugas disposisi + batas waktu terdekat), superadmin (rekap per OPD); grafik garis chart.js volume SM/SK 12 bulan terakhir.
- `/agenda`: tabel gabungan SM+SK dengan filter (periode wajib, jenis, klasifikasi) → tombol "Cetak PDF" (dompdf, view `agenda/cetak.blade.php`, kop sederhana nama pemda + OPD, orientasi landscape).
- File: `DashboardController` (final), `AgendaController`, views `dashboard/*`, `agenda/*`.
- VERIFIKASI: dashboard 200 per role dengan angka widget sesuai seeder (assert nilai); `/agenda/cetak?dari=&sampai=` → header `Content-Type: application/pdf`; seluruh suite hijau; `pint --test` bersih.

## Out-of-Scope Modul
- WhatsApp & email; pengingat terjadwal batas waktu (scheduler); ekspor Excel; preferensi notifikasi per user; pembaruan badge real-time (polling/websocket).

## Verifikasi Akhir (end-to-end)
Dengan `php artisan queue:work --once` aktif: pimpinan mendisposisi ke staf ber-`telegram_chat_id` → staf melihat badge lonceng +1 dan menerima pesan Telegram (bot asli saat uji manual; `Http::fake` di test); buka `/agenda`, cetak PDF periode bulan berjalan → file PDF berisi surat dari seeder.
