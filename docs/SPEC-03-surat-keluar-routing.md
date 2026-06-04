# SPEC-03: Modul Surat Keluar & Routing Antar-OPD
> Induk: `docs/BLUEPRINT.md` — baca dulu. Bergantung pada: SPEC-01, SPEC-02.

## Kontrak dengan Modul Lain
| Arah | Antarmuka | Bentuk |
|---|---|---|
| Menyediakan | Model `SuratKeluar` | dirujuk `surat_masuks.surat_keluar_id` |
| Menyediakan | Event `SuratAntarOpdTerkirim($suratKeluar, $suratMasukTujuan)` | didengarkan modul 04 |
| Mengonsumsi | `PenomoranService` (jenis `surat_keluar`), `Opd.format_nomor` | dari SPEC-01 |
| Mengonsumsi | Model `SuratMasuk`, `Lampiran` | dari SPEC-02 |

## Model Data
| Entitas | Field krusial | Relasi |
|---|---|---|
| `surat_keluars` | `opd_id`, `klasifikasi_id`, `nomor` nullable, `nomor_urut` nullable, `tanggal_surat`, `jenis_tujuan` enum(`eksternal`,`internal`), `tujuan_eksternal` nullable, `tujuan_opd_id` FK nullable, `perihal`, `sifat`, `status` enum(`draft`,`terbit`,`diarsip`) | n—1 `Opd` tujuan; 0..1—1 `SuratMasuk` |

Keputusan penomoran: nomor HANYA digenerate saat aksi **Terbitkan** (draft → terbit) agar draft yang batal tidak membolongi urutan. `app/Services/NomorSuratService::terbitkan(SuratKeluar)`: transaksi → `PenomoranService::next()` → render `Opd.format_nomor` dengan token `{klasifikasi} {nomor} {kode_opd} {bulan_romawi} {tahun}` → simpan `nomor` + `nomor_urut`.

## Fase Pengerjaan
### Fase 1 — CRUD draft & penerbitan eksternal
- Form surat keluar (default `eksternal`): klasifikasi, tujuan, perihal, sifat, tanggal; upload PDF final via `Lampiran`. Draft dapat diedit; aksi Terbitkan memanggil `NomorSuratService` lalu mengunci edit.
- Index + filter seperti surat masuk; detail menampilkan nomor dan lampiran.
- File: migration, `app/Models/SuratKeluar.php`, `SuratKeluarController`, `app/Services/NomorSuratService.php`, `StoreSuratKeluarRequest`, views `surat-keluar/*`.
- VERIFIKASI: `php artisan test --filter=SuratKeluarTest` → penerbitan menghasilkan nomor sesuai pola OPD (uji 2 OPD berpola beda, bulan Romawi benar); dua penerbitan berurutan → `nomor_urut` berurutan; draft tidak bernomor; `pint --test` bersih.

### Fase 2 — Routing antar-OPD
- `jenis_tujuan=internal` + pilih `tujuan_opd_id` (dropdown OPD aktif selain OPD sendiri). Saat Terbitkan, `app/Services/RoutingSuratService::kirim()` dalam transaksi yang sama: generate nomor → buat `SuratMasuk` di OPD tujuan (pakai `withoutGlobalScope`) berisi `nomor_surat` = nomor SK, `asal_surat` = nama OPD pengirim, `tanggal_terima` = hari ini, `sifat` ikut, `surat_keluar_id` terisi, `nomor_agenda` dari counter OPD tujuan → duplikasi baris `lampirans` (path file sama, file fisik tidak digandakan) → fire `SuratAntarOpdTerkirim`.
- Detail SK internal menampilkan status tautan: "Diterima sebagai agenda #N di {OPD}".
- File: `app/Services/RoutingSuratService.php`, `app/Events/SuratAntarOpdTerkirim.php`.
- VERIFIKASI: feature test: terbitkan internal → `surat_masuks` OPD tujuan bertambah 1 dengan field benar + lampiran ikut; TU OPD tujuan melihatnya di daftar; event terpancar (`Event::fake`); transaksi atomik — paksa gagal di tengah → tidak ada record yatim.

### Fase 3 — Pengerasan
- Validasi: internal wajib `tujuan_opd_id`, eksternal wajib `tujuan_eksternal`; cegah Terbitkan tanpa lampiran PDF; aksi arsipkan.
- VERIFIKASI: seluruh suite hijau; `pint --test` bersih; `migrate:fresh --seed` tanpa error.

## Out-of-Scope Modul
- Pembatalan/penarikan surat yang sudah terbit (nomor hangus permanen); revisi bernomor; tujuan internal ganda (multi-OPD sekali kirim); template/generator isi surat.

## Verifikasi Akhir (end-to-end)
TU DISKOMINFO membuat draft internal ke DINKES + PDF → Terbitkan → nomor sesuai pola DISKOMINFO; login TU DINKES → surat muncul di Surat Masuk dengan asal "DISKOMINFO" → dapat didisposisikan (alur modul 02 tetap berjalan).
