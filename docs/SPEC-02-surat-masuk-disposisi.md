# SPEC-02: Modul Surat Masuk & Disposisi
> Induk: `docs/BLUEPRINT.md` — baca dulu. Bergantung pada: SPEC-01.

## Kontrak dengan Modul Lain
| Arah | Antarmuka | Bentuk |
|---|---|---|
| Menyediakan | Model `SuratMasuk` (+ kolom `surat_keluar_id` nullable) | diisi modul 03 saat routing antar-OPD |
| Menyediakan | Model `Lampiran` polymorphic + `LampiranController@download` | dipakai juga oleh modul 03 |
| Menyediakan | Event `DisposisiDibuat`, `TindakLanjutDicatat` | didengarkan modul 04 |
| Mengonsumsi | Auth/role/scope, `Klasifikasi`, `PenomoranService` (jenis `agenda_masuk`) | dari SPEC-01 |

## Model Data
| Entitas | Field krusial | Relasi |
|---|---|---|
| `surat_masuks` | `opd_id`, `klasifikasi_id`, `surat_keluar_id` nullable, `nomor_agenda` int, `nomor_surat`, `asal_surat`, `tanggal_surat`, `tanggal_terima`, `perihal`, `sifat`, `status` enum(`baru`,`didisposisi`,`selesai`,`diarsip`) | 1—n `disposisis` |
| `disposisis` | `surat_masuk_id`, `parent_id` self-FK nullable (berantai), `dari_user_id`, `kepada_user_id`, `instruksi`, `batas_waktu` nullable, `status` enum(`terkirim`,`dibaca`,`diproses`,`selesai`) | 1—n `tindak_lanjuts` |
| `tindak_lanjuts` | `disposisi_id`, `user_id`, `catatan` | lampiran opsional |
| `lampirans` | `lampiranable_type/id`, `path`, `nama_asli`, `mime`, `ukuran` | polymorphic: SuratMasuk, SuratKeluar, TindakLanjut |

Aturan akses sifat `rahasia` — scope `SuratMasuk::visibleTo(User $u)`: tampil bila `sifat != rahasia`, ATAU role `admin_tu`/`pimpinan` se-OPD, ATAU `$u` berada dalam rantai disposisi surat tersebut. Dipakai di index dan Policy `view`.

## Fase Pengerjaan
### Fase 1 — Pencatatan & arsip
- CRUD surat masuk: `nomor_agenda` otomatis dari `PenomoranService` (per OPD per tahun); upload lampiran multiple (PDF/JPG/PNG ≤ 10 MB) ke disk private; index dengan pagination + filter (periode, klasifikasi, sifat, status) + pencarian perihal/asal; halaman detail.
- Unduh lampiran hanya via `LampiranController@download` (policy mengikuti induknya).
- File: migrations, `app/Models/{SuratMasuk,Disposisi,TindakLanjut,Lampiran}.php`, `SuratMasukController`, `StoreSuratMasukRequest`, views `surat-masuk/*`.
- VERIFIKASI: `php artisan test --filter=SuratMasukTest` → CRUD jalan; user OPD lain mendapat 404; unduh lampiran 200 (berhak) / 403 (tidak); `pint --test` bersih.

### Fase 2 — Disposisi berantai & tindak lanjut
- Dari detail surat: admin_tu mengajukan ke pimpinan (disposisi akar); pimpinan mendisposisi ke staf; penerima dapat meneruskan (anak, `parent_id`) ke user se-OPD; status disposisi berubah saat dibaca/diproses; penerima mencatat tindak lanjut (+lampiran) lalu menandai selesai.
- Status surat otomatis: ada disposisi → `didisposisi`; semua cabang selesai → `selesai`. Timeline kronologis (disposisi + tindak lanjut) di halaman detail.
- Fire `DisposisiDibuat` dan `TindakLanjutDicatat` (tanpa listener di modul ini).
- File: `DisposisiController`, `TindakLanjutController`, partial `surat-masuk/_timeline.blade.php`.
- VERIFIKASI: feature test alur penuh TU → pimpinan → staf → tindak lanjut → selesai (status surat `selesai`); `Event::fake()` memastikan kedua event terpancar.

### Fase 3 — Akses rahasia & pengerasan
- Terapkan `visibleTo` + Policy: staf tanpa disposisi tidak melihat surat `rahasia` (404), termasuk lampirannya; aksi arsipkan (`diarsip`, read-only).
- VERIFIKASI: seluruh suite hijau; test akses rahasia 3 kasus (TU, staf berdisposisi, staf tanpa disposisi) lulus; `pint --test` bersih.

## Out-of-Scope Modul
- Surat keluar & routing (modul 03); notifikasi (modul 04); OCR/pratinjau lampiran dalam browser; pengingat otomatis batas waktu; edit/hapus disposisi yang sudah terkirim.

## Verifikasi Akhir (end-to-end)
Dengan seeder demo: login TU → catat surat sifat `rahasia` + lampiran → ajukan ke pimpinan → pimpinan disposisi ke staf A → staf A tindak lanjut + selesai → status surat `selesai`; staf B tidak menemukan surat tersebut di daftar.
