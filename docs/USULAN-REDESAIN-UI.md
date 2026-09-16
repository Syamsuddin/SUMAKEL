# Usulan Perombakan Total UI/UX SUMAKEL — Tema "Institusional Pemerintah"

Disusun dengan metode UI/UX Pro Max (design-system generator + 99 pedoman UX).
Sumber kebenaran token desain: `design-system/sumakel-e-surat-pemda/MASTER.md`.

---

## 1. Ringkasan Eksekutif

SUMAKEL saat ini memakai tampilan bawaan `laravel/ui` + Bootstrap 5 tanpa penyesuaian: sidebar `bg-dark`, font Nunito, kartu statistik warna primer/sukses/warning polos, ikon emoji, halaman login standar. Fungsinya lengkap, tetapi tampilannya **tidak mencerminkan identitas lembaga pemerintah** dan beberapa pedoman aksesibilitas dasar belum terpenuhi.

Usulan ini merombak **lapisan presentasi saja** (Blade, SCSS, komponen) tanpa menyentuh controller, service, model, atau routing. Arah visual yang diusulkan:

> **"Institusional Modern"** — navy tua + biru aksen berkontras tinggi, tipografi tegas dan mudah dibaca, tata letak padat-tapi-lapang ala sistem administrasi negara, aksen emas tipis sebagai penanda "resmi" (kop, nomor, stempel status). Tanpa gradien, tanpa ornamen, WCAG AA minimum.

---

## 2. Audit Kondisi Saat Ini

| # | Temuan | Lokasi | Dampak | Prioritas |
|---|--------|--------|--------|-----------|
| 1 | Ikon lonceng memakai emoji `🔔` | `resources/views/layouts/_notifikasi.blade.php:8` | Tidak konsisten antar OS, tidak bisa di-theme, melanggar aturan *no-emoji-icons* | Tinggi |
| 2 | Navigasi sidebar tanpa ikon, hanya teks; label seksi memakai `text-secondary` di atas `bg-dark` (kontras rendah) | `layouts/_sidebar_content.blade.php` | Discoverability rendah, kontras < 4.5:1 | Tinggi |
| 3 | Tidak ada identitas Pemda/OPD (logo, nama daerah, nama OPD aktif) di shell aplikasi | `layouts/app.blade.php`, `sidebar.blade.php` | Terasa seperti template generik, bukan sistem resmi | Tinggi |
| 4 | Login = kartu default `laravel/ui` di dalam layout aplikasi (sidebar/navbar tersembunyi tapi struktur tetap) | `auth/login.blade.php` | Kesan pertama lemah; label masih bahasa Inggris (`Login`, `Email Address`) | Tinggi |
| 5 | Kartu statistik `text-bg-primary/success/warning` — angka di `h5`, tanpa ikon, tanpa konteks perubahan | `dashboard/admin_tu.blade.php` | Hierarki visual lemah; warna kuning-putih (warning) kontras rendah | Sedang |
| 6 | Badge status/sifat hanya membedakan lewat warna (`bg-danger/warning/info`) tanpa ikon | `surat-masuk/index.blade.php`, `show.blade.php` | Melanggar *color-not-only* | Sedang |
| 7 | Tabel tidak punya mode kartu untuk mobile; 8 kolom dipaksa `table-responsive` | `surat-masuk/index.blade.php` | Sulit dipakai di HP | Sedang |
| 8 | Timeline disposisi = `list-group` datar, tidak terlihat sebagai rantai/alur | `surat-masuk/_timeline.blade.php` | Rantai disposisi (fitur inti) tidak "terbaca" secara visual | Sedang |
| 9 | Tidak ada breadcrumb / page header konsisten; setiap halaman memakai `<h4>` bebas | Semua view | Orientasi pengguna lemah di hierarki 3 level | Sedang |
| 10 | Tidak ada empty-state dengan aksi (hanya teks abu-abu) | index & dashboard | Tidak memandu pengguna baru | Rendah |
| 11 | `chart.js` dimuat dari CDN padahal ada di `package.json`; `resources/css/app.css` (Tailwind) & `welcome.blade.php` (72 KB) tidak dipakai | `dashboard/_chart.blade.php`, `resources/css/app.css` | Beban ganda, aset mati | Rendah |
| 12 | Tidak ada `prefers-reduced-motion`, skip-link, focus ring kustom | global | Aksesibilitas dasar | Sedang |

---

## 3. Arah Desain

### 3.1 Prinsip

1. **Wibawa, bukan hiasan.** Warna gelap-netral, garis tipis, ruang putih terukur. Hindari gradien, glassmorphism, bayangan tebal.
2. **Dokumen adalah pusat.** Halaman detail surat harus terasa seperti membaca berkas resmi: kop, nomor, perihal, rantai disposisi yang jelas.
3. **Terbaca oleh siapa pun.** Kontras ≥ 4.5:1, teks dasar 16px, target sentuh ≥ 44px, navigasi keyboard penuh.
4. **Satu bahasa visual.** Satu set ikon (Bootstrap Icons — sudah selaras BS5), satu skala spasi 4/8px, satu skala radius.

### 3.2 Palet Warna (dari MASTER.md, disesuaikan konteks Pemda)

| Peran | Hex | Token SCSS / CSS | Penggunaan |
|-------|-----|------------------|------------|
| Primary (Navy) | `#0F172A` | `$primary` / `--bs-primary` | Sidebar, topbar, judul utama, tombol utama |
| Secondary (Slate) | `#334155` | `$secondary` | Teks sekunder, tombol sekunder |
| Accent (Biru Instansi) | `#0369A1` | `$info` → dipakai sebagai `--sk-accent` | Link, tombol aksi, state aktif nav, fokus |
| Emas Resmi | `#B45309` | `--sk-gold` | Garis tipis kop surat, nomor agenda, penanda "Rahasia"/"Terbit" — **maks. 5% area** |
| Background | `#F8FAFC` | `$body-bg` | Latar halaman |
| Surface | `#FFFFFF` | `--sk-surface` | Kartu, tabel |
| Muted | `#E8ECF1` | `--sk-muted` | Header tabel, filter bar |
| Border | `#E2E8F0` | `$border-color` | Semua garis pemisah |
| Foreground | `#020617` | `$body-color` | Teks utama |
| Muted FG | `#64748B` | `$text-muted` | Metadata, timestamp |
| Success | `#15803D` | `$success` | Selesai, terbit |
| Warning | `#B45309` | `$warning` | Penting, mendekati tenggat |
| Danger | `#DC2626` | `$danger` | Rahasia, lewat tenggat |

Semua pasangan teks/latar di atas diverifikasi ≥ 4.5:1. **Warning diganti dari kuning BS (`#ffc107`) ke amber tua** karena teks putih di atas kuning BS hanya 1.6:1.

### 3.3 Tipografi

Rekomendasi generator: *Legal Professional* (EB Garamond + Lato). Untuk aplikasi padat tabel dan form, serif pada heading mengganggu keterbacaan angka/nomor surat, jadi diusulkan pasangan **"Corporate Trust"** (peringkat 2 di generator, diberi tag *government + accessibility*):

| Peran | Font | Bobot | Ukuran |
|-------|------|-------|--------|
| Heading (h1–h4, judul kartu) | **Lexend** | 600 | 28 / 22 / 18 / 16 px |
| Body, tabel, form | **Source Sans 3** | 400 / 600 | 16 px, line-height 1.5 |
| Angka (nomor agenda, statistik) | Source Sans 3 `font-variant-numeric: tabular-nums` | 600–700 | 32–40 px di kartu statistik |
| Cetak PDF (agenda, kop surat) | **EB Garamond** (opsional) | 400/600 | 12 pt |

Skala: 12 · 14 · 16 · 18 · 22 · 28 · 36.
Font dimuat lewat `fonts.bunny.net` (sudah dipakai proyek, privacy-friendly) dengan `display=swap`.

### 3.4 Ikon

**Bootstrap Icons** (`bootstrap-icons` npm, SVG sprite/webfont). Alasan: gaya stroke seragam dengan komponen BS5, > 2.000 ikon, tanpa dependensi JS. Ukuran token: `--icon-sm: 16px`, `--icon-md: 20px`, `--icon-lg: 24px`.

Peta ikon utama:

| Konteks | Ikon |
|---------|------|
| Dashboard | `bi-speedometer2` |
| Surat Masuk | `bi-envelope-arrow-down` |
| Surat Keluar | `bi-envelope-arrow-up` |
| Agenda | `bi-journal-text` |
| OPD | `bi-building` |
| Klasifikasi | `bi-tags` |
| Pengguna | `bi-people` |
| Notifikasi | `bi-bell` |
| Disposisi | `bi-diagram-3` |
| Tindak lanjut | `bi-check2-square` |
| Lampiran | `bi-paperclip` |
| Rahasia | `bi-shield-lock` |
| Penting | `bi-exclamation-triangle` |
| Biasa | `bi-file-text` |
| Terbit / Selesai | `bi-patch-check` |
| Arsip | `bi-archive` |

### 3.5 Efek & Gerak

- Radius: `4px` (input/badge), `8px` (kartu), `12px` (modal). Tidak ada pill kecuali badge status.
- Bayangan: hanya satu level `0 1px 2px rgba(15,23,42,.06)` untuk kartu; topbar `0 1px 0 var(--sk-border)`.
- Transisi: `150ms ease-out` untuk hover/focus; `200ms` untuk offcanvas. Dibungkus `@media (prefers-reduced-motion: no-preference)`.
- Focus ring: `0 0 0 3px rgba(3,105,161,.35)` di semua elemen interaktif.

---

## 4. Arsitektur Layout (App Shell)

```
┌──────────────┬────────────────────────────────────────────────────────┐
│  LOGO PEMDA  │ ☰  Beranda › Surat Masuk › #0123        🔍   🔔 3   👤 │  ← Topbar 56px
│  SUMAKEL     ├────────────────────────────────────────────────────────┤
│  e-Surat     │  Surat Masuk                          [+ Catat Surat]  │  ← Page header
│──────────────│  Daftar surat masuk OPD Anda                           │
│ OPD:         │────────────────────────────────────────────────────────│
│ Dinas Kominfo│  ┌ Filter bar (sticky) ────────────────────────────┐   │
│──────────────│  └─────────────────────────────────────────────────┘   │
│ ▣ Dashboard  │  ┌ Tabel / Kartu ───────────────────────────────────┐  │
│ PERSURATAN   │  │ ...                                              │  │
│ ✉ Surat Masuk│  └──────────────────────────────────────────────────┘  │
│ ✉ Surat Kelu.│                                                        │
│ ▤ Agenda     │                                                        │
│ MASTER DATA  │                                                        │
│ ⌂ OPD        │                                                        │
│──────────────│                                                        │
│ 👤 Nama      │                                                        │
│    admin_tu  │                                                        │
└──────────────┴────────────────────────────────────────────────────────┘
   260px navy                       konten, max-width 1400px, padding 24px
```

**Sidebar (260px, `#0F172A`)**
- Blok identitas: logo Pemda (dari `Opd`/config, fallback lambang generik SVG), nama aplikasi, nama OPD aktif pengguna (superadmin: "Seluruh OPD").
- Item nav: ikon 20px + label; state aktif = latar `rgba(255,255,255,.08)` + garis kiri 3px `#0369A1` + teks putih 600. Label seksi `#94A3B8` uppercase 12px tracking 0.05em (kontras 5.9:1 di navy).
- Footer sidebar: avatar inisial, nama, chip peran; tombol keluar dipisah dengan garis.
- Mobile: offcanvas yang sama, lebar 280px.

**Topbar (56px, putih, border-bottom)**
- Breadcrumb otomatis dari `route name` (Beranda › Surat Masuk › Detail).
- Pencarian global (Ctrl+K) — fase 3, opsional.
- Lonceng SVG + badge merah; dropdown notifikasi dengan ikon per jenis event.
- Menu profil.

**Page Header (komponen `x-page-header`)**
- Judul (Lexend 28px), subjudul abu-abu, slot aksi kanan (satu tombol utama navy + tombol sekunder outline). Aturan: **satu CTA utama per halaman**.

**Skip link** `#konten-utama` untuk keyboard/screen reader.

---

## 5. Rancangan per Halaman

### 5.1 Login (`auth/login.blade.php`) — layout terpisah `layouts/guest`
- Split screen: kiri (45%) panel navy dengan lambang Pemda, nama daerah, tagline "Sistem Tata Persuratan Elektronik Pemerintah Daerah", garis emas tipis; kanan form putih.
- Mobile: panel navy jadi header 160px.
- Label Indonesia: "Alamat Email", "Kata Sandi", "Ingat saya", "Masuk". Toggle lihat sandi. Error inline di bawah field.
- Footer kecil: versi aplikasi + "© 2026 Pemerintah Daerah …".

### 5.2 Dashboard per peran
**Admin TU** — 4 kartu statistik (Surat Masuk bulan ini, Surat Keluar bulan ini, Belum Disposisi, Lewat Tenggat) dengan ikon di kiri, angka besar tabular, delta vs bulan lalu (opsional). Bawah: grafik garis 12 bulan (warna navy vs biru aksen, garis putus untuk seri kedua agar tidak bergantung warna) + tabel "5 Surat Masuk Terbaru".
**Pimpinan** — Kartu "Disposisi Menunggu" berbentuk daftar tugas dengan ikon sifat, tenggat berwarna, tombol "Buka". Ringkasan angka di atas: Menunggu / Diproses / Selesai bulan ini.
**Staf** — Sama, judul "Tugas Saya", disortir tenggat terdekat, penanda "Lewat tenggat" merah + ikon.
**Superadmin** — Tabel OPD dengan volume surat + grafik total.

### 5.3 Surat Masuk — Index
- Filter bar dalam kartu `muted`, sticky di bawah topbar; tombol Filter = utama, Reset = link.
- Tabel desktop: kolom No. Agenda (tabular, tebal), Perihal (2 baris, tidak dipotong 40 karakter — pakai `-webkit-line-clamp: 2`), Asal, Tgl Terima, Sifat (chip ikon), Status (chip ikon), Aksi (ikon mata + label sr-only).
- Baris `sifat=rahasia`: ikon `shield-lock` merah, bukan sekadar badge.
- ≤ 768px: tabel berubah menjadi daftar kartu (`d-md-none`) — perihal, nomor, chip, tanggal.
- Empty state: ilustrasi SVG amplop + "Belum ada surat masuk" + tombol "Catat Surat Masuk".
- Paginasi Bootstrap 5 dengan info "Menampilkan 1–20 dari 134".

### 5.4 Surat Masuk — Detail (halaman paling penting)
- Header dokumen bergaya **kop**: garis atas emas 2px, nomor agenda besar kiri, chip sifat & status kanan; di bawahnya grid metadata 2 kolom (Nomor Surat, Asal, Klasifikasi, Tgl Surat, Tgl Terima).
- Perihal ditampilkan sebagai paragraf terpisah dengan heading kecil.
- Lampiran: daftar dengan ikon tipe file, ukuran, tombol unduh.
- **Timeline disposisi = vertical stepper**: garis vertikal, titik berwarna per status, kartu berisi "Dari → Kepada", instruksi, tenggat, tindak lanjut ter-nest (indent 24px, ikon `check2-square`). Status "terkirim" merah, "diproses" biru, "selesai" hijau — masing-masing dengan ikon.
- Kolom kanan (sticky): panel aksi — form Disposisi / Tindak Lanjut, dipisah dengan judul tegas; tombol utama full-width navy.
- Tombol header: Edit (outline), Arsipkan (outline + konfirmasi modal, bukan `confirm()`), Kembali (link ikon panah).

### 5.5 Surat Keluar — Index & Detail
- Index sama dengan Surat Masuk; kolom Status: Draft (abu), Terbit (hijau + `patch-check`), Diarsip.
- Detail: **stepper horizontal 3 langkah** di atas kop: Draft → Terbit → Terkirim ke OPD tujuan. Nomor surat muncul besar hanya setelah terbit; sebelum itu placeholder "Nomor diterbitkan saat surat terbit".
- Tujuan antar-OPD ditampilkan sebagai chip `building` + nama OPD.

### 5.6 Agenda
- Toolbar: rentang tanggal + jenis + tombol "Cetak PDF" (ikon printer, outline).
- Tabel dengan nomor urut tabular. Versi cetak (`agenda/cetak.blade.php`) memakai EB Garamond + kop resmi OPD.

### 5.7 Master Data (OPD, Klasifikasi, Pengguna) & Profil
- Pola seragam: page header + tabel + tombol tambah; form dalam kartu 2 kolom (`col-lg-8`), field dikelompokkan dengan `fieldset`/judul kecil.
- Pengguna: chip peran berwarna netral, OPD, status aktif.

### 5.8 Notifikasi
- Daftar dengan ikon per jenis (`diagram-3` disposisi, `check2-square` tindak lanjut, `envelope-arrow-down` surat antar-OPD), belum dibaca = latar `#EFF6FF` + titik biru; tombol "Tandai semua dibaca" di header.

---

## 6. Pustaka Komponen Blade (baru)

Semua di `resources/views/components/`:

| Komponen | Props | Fungsi |
|----------|-------|--------|
| `x-page-header` | `title`, `subtitle`, slot `actions` | Header halaman seragam |
| `x-breadcrumb` | otomatis dari route | Orientasi |
| `x-stat-card` | `icon`, `label`, `value`, `tone`, `href` | Kartu statistik dashboard |
| `x-status-chip` | `status` / `sifat` | Chip ikon + warna terpusat (satu tempat untuk peta warna, bukan ternary di tiap view) |
| `x-empty-state` | `icon`, `title`, `text`, slot `action` | Empty state |
| `x-filter-bar` | slot | Kartu filter sticky |
| `x-data-table` + `x-table-card` | slot | Tabel desktop + kartu mobile |
| `x-timeline` / `x-timeline-item` | `tone`, `time` | Stepper disposisi |
| `x-doc-header` | `nomor`, `chips`, slot meta | Kop dokumen |
| `x-confirm-modal` | `action`, `title`, `text` | Ganti `onclick=confirm()` |
| `x-icon` | `name`, `size` | Wrapper Bootstrap Icons |

Layout: `layouts/app.blade.php` (shell), `layouts/guest.blade.php` (login/reset), `layouts/print.blade.php` (PDF).

---

## 7. Implementasi Teknis

### 7.1 `resources/sass/_variables.scss` (pengganti)

```scss
// Palet institusional
$primary:   #0F172A;
$secondary: #334155;
$info:      #0369A1;
$success:   #15803D;
$warning:   #B45309;
$danger:    #DC2626;
$light:     #F8FAFC;
$dark:      #020617;

$body-bg:      #F8FAFC;
$body-color:   #020617;
$border-color: #E2E8F0;
$text-muted:   #64748B;

$font-family-sans-serif: 'Source Sans 3', system-ui, sans-serif;
$headings-font-family:   'Lexend', system-ui, sans-serif;
$headings-font-weight:   600;
$font-size-base: 1rem;      // 16px (sebelumnya 0.9rem — terlalu kecil untuk mobile)
$line-height-base: 1.5;

$border-radius:    .5rem;
$border-radius-sm: .25rem;
$border-radius-lg: .75rem;
$box-shadow-sm: 0 1px 2px rgba(15, 23, 42, .06);

$input-btn-padding-y: .625rem;  // tinggi tombol/input ≥ 44px
$input-focus-box-shadow: 0 0 0 3px rgba(3, 105, 161, .35);
$btn-focus-box-shadow: $input-focus-box-shadow;
$table-th-font-weight: 600;
$badge-font-weight: 600;
```

### 7.2 Token CSS kustom (`resources/sass/_tokens.scss`)

```scss
:root {
  --sk-sidebar-w: 260px;
  --sk-topbar-h: 56px;
  --sk-gold: #B45309;
  --sk-surface: #fff;
  --sk-muted: #E8ECF1;
  --sk-border: #E2E8F0;
  --sk-nav-fg: #CBD5E1;
  --sk-nav-active-bg: rgba(255,255,255,.08);
  --icon-sm: 16px; --icon-md: 20px; --icon-lg: 24px;
  --dur-fast: 150ms; --dur-base: 200ms;
}
@media (prefers-reduced-motion: reduce) { :root { --dur-fast: 0ms; --dur-base: 0ms; } }
```

### 7.3 Dependensi
```bash
npm i bootstrap-icons
# hapus: tailwindcss, @tailwindcss/vite (tidak dipakai) — opsional
```
Muat Chart.js dari `node_modules` lewat `resources/js/app.js` (hapus CDN). Hapus `resources/css/app.css` dan `welcome.blade.php` bawaan.

### 7.4 Struktur berkas yang disentuh
```
resources/sass/{app,_variables,_tokens,_shell,_components,_print}.scss
resources/js/app.js                       (import bootstrap, bootstrap-icons, chart.js)
resources/views/layouts/{app,guest,print,sidebar,_sidebar_content,_notifikasi,_topbar}.blade.php
resources/views/components/*.blade.php    (11 komponen §6)
resources/views/**/*.blade.php            (migrasi ke komponen)
```
Tidak ada perubahan di `app/`, `routes/`, `database/`. Test Pest yang ada harus tetap hijau (assert konten, bukan markup).

---

## 8. Checklist UX yang Ditegakkan

**Kritis**
- [x] Kontras teks ≥ 4.5:1 (khusus: label seksi sidebar, badge warning, teks muted)
- [x] Semua tombol ikon punya `aria-label`; lonceng emoji dihapus
- [x] Skip link, focus ring 3px, urutan tab = urutan visual
- [x] Target sentuh ≥ 44×44px; jarak antar target ≥ 8px — desktop: nav, tombol utama, input; kontrol `-sm` (tabel/filter) membesar ke 44px lewat `@media (pointer: coarse)`
- [x] `prefers-reduced-motion` dihormati

**Tinggi**
- [x] Tabel → kartu di ≤ 768px; tidak ada scroll horizontal halaman
- [x] Teks dasar 16px (sebelumnya 14.4px)
- [x] Nav item ikon + label; state aktif jelas; breadcrumb ≥ 3 level
- [x] Satu CTA utama per halaman

**Sedang**
- [x] Status/sifat tidak hanya warna (ikon + teks)
- [x] Empty state dengan aksi
- [x] Konfirmasi destruktif via modal, tombol merah terpisah dari aksi utama
- [x] Error form di bawah field + fokus otomatis ke field pertama yang salah
- [x] Tombol submit disable + spinner saat proses
- [x] Grafik: seri dibedakan gaya garis, legend terlihat, tooltip, `aria-label` ringkasan

**Anti-pola yang dihindari** (dari generator): ornamen, kontras rendah, efek gerak berlebihan, gradien ungu/pink "AI".

---

## 9. Roadmap Implementasi

| Fase | Cakupan | Berkas utama | Estimasi | Status |
|------|---------|--------------|----------|--------|
| **1. Fondasi visual** | Token SCSS, font, Bootstrap Icons, app shell (sidebar + topbar + page header + breadcrumb), layout guest + login baru, komponen dasar (`page-header`, `status-chip`, `empty-state`, `icon`, `confirm-modal`) | `sass/*`, `layouts/*`, `components/*`, `auth/login` | 2 hari | selesai (2026-09-16) |
| **2. Modul inti** | Surat Masuk index + detail (kop dokumen, timeline stepper, panel aksi), Surat Keluar index + detail (stepper terbit), tabel responsif | `surat-masuk/*`, `surat-keluar/*` | 2–3 hari | selesai (2026-09-16) |
| **3. Dashboard & pendukung** | 4 dashboard peran, stat card, grafik ulang, notifikasi, agenda + versi cetak, master data, profil | `dashboard/*`, `notifikasi/*`, `agenda/*`, `master/*` | 2 hari | selesai (2026-09-16) |
| **4. Polesan & QA** | Audit kontras otomatis, uji 375/768/1024/1440, reduced-motion, keyboard, `pint --test`, `php artisan test`, `graphify update .` | — | 1 hari |

Cabang: `feat/ui-redesign-institusional`. Setiap fase satu PR, commit konvensional (`feat(ui): ...`).

---

## 9a. Hasil QA Fase 4 (2026-09-17)

- **Kontras**: audit in-browser (rasio WCAG tiap elemen teks terhadap latar efektif) pada 14 halaman × 4 peran → 0 kegagalan setelah `$danger` → `#B91C1C` dan `$code-color` → `#9D174D`.
- **Nama aksesibel**: 0 tombol/tautan tanpa nama.
- **Viewport 375/768/1024/1440**: tidak ada scroll horizontal; tabel → kartu di <768; sidebar → offcanvas di <992; filter bar membungkus rapi di 1024. Perbaikan: `.table-responsive { position: relative }` (header `visually-hidden` sempat memicu overflow 417px di 375).
- **Keyboard**: Tab pertama = skip link (tampak, lompat ke `#konten-utama`), urutan tab = urutan visual, focus ring 3px di semua elemen; modal: focus trap Bootstrap + fokus kembali ke tombol pemicu saat ditutup (`app.js`).
- **Reduced motion**: `@media (prefers-reduced-motion: reduce)` menolkan durasi transisi/animasi (ada di CSS hasil build).
- **Cetak**: `_print.scss` menyembunyikan sidebar/topbar/filter/aksi saat halaman aplikasi dicetak.
- `php artisan test` 46 lulus, `pint --test` lulus, `npm run build` sukses.

## 10. Keputusan yang Perlu Dikonfirmasi

> Keputusan yang diambil pada Fase 1: (1) logo `public/images/logo-pemda.png` via `config('app.logo')`; (2) nama daerah dari `.env` `APP_PEMDA` (+ `APP_TAGLINE`, `APP_LOGO`); (3) dark mode ditunda; (4) Ctrl+K ditunda.

1. **Lambang daerah** — apakah tersedia file logo Pemda (SVG/PNG) untuk sidebar & login? Jika belum, dipakai lambang generik SVG dan diganti kemudian lewat `config('app.logo')`.
2. **Nama daerah** di login/kop — diambil dari `.env` (`APP_PEMDA="Pemerintah Kabupaten …"`) atau tabel `opds`?
3. **Dark mode** — MASTER.md mendukung penuh, tetapi untuk instansi pemerintah diusulkan **ditunda** (fase 5) agar fokus pada kontras light mode dulu.
4. **Pencarian global (Ctrl+K)** — masuk fase 3 atau ditunda?
