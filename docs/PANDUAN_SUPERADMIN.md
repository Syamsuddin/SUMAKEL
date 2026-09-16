# Panduan Operasional — Role Superadmin

**Aplikasi e-Surat Pemda (SUMAKEL)**
Versi 1.0

---

## Daftar Isi

1. [Pendahuluan](#1-pendahuluan)
2. [Login & Navigasi](#2-login--navigasi)
3. [Dashboard Superadmin](#3-dashboard-superadmin)
4. [Kelola OPD](#4-kelola-opd)
5. [Kelola Klasifikasi Surat](#5-kelola-klasifikasi-surat)
6. [Kelola Pengguna](#6-kelola-pengguna)
7. [Monitoring Data Persuratan](#7-monitoring-data-persuratan)
8. [Notifikasi](#8-notifikasi)
9. [Profil & Keamanan Akun](#9-profil--keamanan-akun)
10. [Prosedur Operasional Standar](#10-prosedur-operasional-standar)
11. [Troubleshooting](#11-troubleshooting)

---

## 1. Pendahuluan

### 1.1 Tentang Role Superadmin

Superadmin adalah peran tertinggi dalam sistem e-Surat Pemda. Role ini beroperasi di **tingkat pemerintah daerah** (bukan per OPD) dan memiliki akses penuh ke seluruh data lintas-OPD.

### 1.2 Tanggung Jawab Utama

| No | Tanggung Jawab | Frekuensi |
|----|----------------|-----------|
| 1 | Mengelola daftar OPD dan format penomoran surat | Saat ada perubahan organisasi |
| 2 | Mengelola klasifikasi surat (kode arsip) | Saat ada perubahan kebijakan kearsipan |
| 3 | Membuat akun Admin TU pertama untuk setiap OPD | Saat OPD baru ditambahkan |
| 4 | Memantau volume persuratan seluruh OPD | Harian / mingguan |
| 5 | Menonaktifkan OPD atau pengguna yang sudah tidak berlaku | Sesuai kebutuhan |

### 1.3 Hak Akses Khusus Superadmin

- Melihat data **seluruh OPD** (tidak dibatasi oleh isolasi data per OPD)
- Satu-satunya role yang dapat **mengelola OPD** dan **Klasifikasi**
- Dapat membuat pengguna untuk **OPD manapun** dan memberikan **semua role** termasuk superadmin
- Dashboard menampilkan **rekap lintas-OPD**

### 1.4 Yang Tidak Dilakukan Superadmin

- Tidak mencatat surat masuk/keluar (tugas Admin TU)
- Tidak melakukan disposisi (tugas Admin TU / Pimpinan)
- Tidak mencatat tindak lanjut (tugas Staf)

---

## 2. Login & Navigasi

### 2.1 Login ke Sistem

1. Buka browser dan akses alamat aplikasi (contoh: `https://esurat.pemda.go.id`)
2. Masukkan **Email** dan **Password** pada halaman login
3. Klik tombol **Login**
4. Sistem akan mengarahkan Anda ke **Dashboard Superadmin**

> **Catatan:** Tidak ada fitur registrasi mandiri. Semua akun dibuat oleh Superadmin atau Admin TU.

### 2.2 Menu Sidebar

Setelah login, sidebar kiri menampilkan menu berikut:

```
e-Surat
├── Dashboard
│
├── MASTER DATA
│   ├── OPD
│   ├── Klasifikasi
│   └── Pengguna
```

> Menu **Persuratan** (Surat Masuk, Surat Keluar, Agenda) tidak tampil untuk Superadmin karena bukan bagian dari fungsi operasional role ini.

### 2.3 Navbar Atas

| Elemen | Fungsi |
|--------|--------|
| Logo **e-Surat** | Kembali ke Dashboard |
| Lonceng (ikon) | Lihat notifikasi terbaru |
| Nama Anda (dropdown) | Profil, Keluar |

### 2.4 Logout

1. Klik nama Anda di pojok kanan atas
2. Pilih **Keluar**
3. Anda akan diarahkan kembali ke halaman login

---

## 3. Dashboard Superadmin

Dashboard Superadmin menampilkan dua komponen utama:

### 3.1 Tabel Rekap Per OPD

Menampilkan ringkasan seluruh OPD aktif:

| Kolom | Keterangan |
|-------|------------|
| **OPD** | Nama OPD |
| **Pengguna** | Jumlah total pengguna terdaftar di OPD tersebut |
| **Surat Masuk** | Total surat masuk yang tercatat di OPD tersebut |
| **Surat Keluar** | Total surat keluar yang tercatat di OPD tersebut |

Gunakan tabel ini untuk:
- Mengidentifikasi OPD yang belum aktif menggunakan sistem (volume surat rendah)
- Memantau distribusi beban kerja persuratan antar-OPD

### 3.2 Grafik Volume Surat 12 Bulan

Grafik garis yang menampilkan tren volume **Surat Masuk** (garis biru) dan **Surat Keluar** (garis hijau) selama 12 bulan terakhir secara **kumulatif seluruh OPD**.

Gunakan grafik ini untuk:
- Melihat tren peningkatan atau penurunan aktivitas persuratan
- Bahan pelaporan ke pimpinan daerah

---

## 4. Kelola OPD

Menu: **Sidebar > Master Data > OPD**

### 4.1 Melihat Daftar OPD

Halaman ini menampilkan seluruh OPD yang terdaftar dalam sistem.

| Kolom | Keterangan |
|-------|------------|
| **Kode** | Kode singkat OPD (contoh: `SETDA`, `DISKOMINFO`) |
| **Nama** | Nama lengkap OPD |
| **Format Nomor** | Pola penomoran surat keluar OPD tersebut |
| **Status** | Aktif atau Nonaktif |
| **Aksi** | Tombol Edit |

### 4.2 Menambah OPD Baru

1. Klik tombol **Tambah OPD** di pojok kanan atas
2. Isi formulir:

| Field | Keterangan | Wajib |
|-------|------------|-------|
| **Kode** | Kode singkat unik, contoh: `BAPPEDA`. Akan digunakan dalam nomor surat. | Ya |
| **Nama** | Nama lengkap OPD, contoh: `Badan Perencanaan Pembangunan Daerah` | Ya |
| **Format Nomor** | Pola penomoran surat keluar. Kosongkan untuk menggunakan format default. | Tidak |

3. Klik **Simpan**

#### Token Format Nomor

Format nomor menggunakan token yang akan diganti secara otomatis saat surat diterbitkan:

| Token | Diganti Menjadi | Contoh |
|-------|-----------------|--------|
| `{klasifikasi}` | Kode klasifikasi surat | `005` |
| `{nomor}` | Nomor urut (3 digit, zero-padded) | `001` |
| `{kode_opd}` | Kode OPD | `SETDA` |
| `{bulan_romawi}` | Bulan dalam angka Romawi | `VI` |
| `{tahun}` | Tahun penerbitan | `2026` |

**Format default:** `{klasifikasi}/{nomor}/{kode_opd}/{bulan_romawi}/{tahun}`
**Hasil contoh:** `005/001/SETDA/VI/2026`

**Contoh format lain:**
- `{nomor}/{klasifikasi}/{kode_opd}/{tahun}` menghasilkan `001/005/BAPPEDA/2026`
- `{kode_opd}.{klasifikasi}/{nomor}/{bulan_romawi}/{tahun}` menghasilkan `SETDA.005/001/VI/2026`

> **Penting:** Pastikan format nomor sudah benar sebelum OPD mulai menerbitkan surat. Nomor yang sudah terbit tidak dapat diubah.

### 4.3 Mengedit OPD

1. Klik tombol **Edit** pada baris OPD yang ingin diubah
2. Ubah data yang diperlukan (Kode, Nama, Format Nomor, Status Aktif)
3. Klik **Simpan**

### 4.4 Menonaktifkan OPD

1. Klik **Edit** pada OPD yang ingin dinonaktifkan
2. Hilangkan centang pada **Aktif**
3. Klik **Simpan**

Atau klik **Hapus** pada daftar OPD — sistem akan menonaktifkan (bukan menghapus permanen) OPD tersebut.

**Dampak menonaktifkan OPD:**
- OPD tidak muncul di dropdown pilihan tujuan surat internal
- OPD tidak muncul di dropdown saat membuat pengguna baru
- Data surat yang sudah ada **tetap tersimpan** dan dapat diakses

> **Perhatian:** Jangan menonaktifkan OPD yang masih memiliki surat aktif (belum diarsipkan). Pastikan seluruh proses disposisi sudah selesai terlebih dahulu.

---

## 5. Kelola Klasifikasi Surat

Menu: **Sidebar > Master Data > Klasifikasi**

### 5.1 Tentang Klasifikasi

Klasifikasi adalah kode arsip yang mengelompokkan surat berdasarkan jenisnya. Klasifikasi bersifat **global** — berlaku untuk semua OPD.

### 5.2 Melihat Daftar Klasifikasi

| Kolom | Keterangan |
|-------|------------|
| **Kode** | Kode numerik klasifikasi (contoh: `005`) |
| **Nama** | Deskripsi klasifikasi (contoh: `Undangan`) |
| **Aksi** | Edit, Hapus |

### 5.3 Menambah Klasifikasi

1. Klik **Tambah Klasifikasi**
2. Isi:
   - **Kode** — Kode unik, sesuai pedoman kearsipan (contoh: `005`, `010`, `020`)
   - **Nama** — Nama deskriptif (contoh: `Undangan`, `Laporan`, `Pemberitahuan`)
3. Klik **Simpan**

### 5.4 Mengedit Klasifikasi

1. Klik **Edit** pada klasifikasi yang ingin diubah
2. Ubah Kode atau Nama
3. Klik **Simpan**

> **Perhatian:** Mengubah kode klasifikasi tidak mengubah nomor surat yang sudah terbit. Pengaruhnya hanya pada surat yang diterbitkan setelah perubahan.

### 5.5 Menghapus Klasifikasi

1. Klik **Hapus** pada klasifikasi
2. Konfirmasi penghapusan

> **Perhatian:** Klasifikasi yang sudah digunakan oleh surat masuk atau surat keluar **tidak boleh dihapus** karena akan menyebabkan error. Pastikan klasifikasi benar-benar belum pernah dipakai sebelum menghapus.

---

## 6. Kelola Pengguna

Menu: **Sidebar > Master Data > Pengguna**

### 6.1 Melihat Daftar Pengguna

Superadmin melihat **seluruh pengguna dari semua OPD** dalam satu tabel.

| Kolom | Keterangan |
|-------|------------|
| **Nama** | Nama lengkap pengguna |
| **Email** | Alamat email (digunakan untuk login) |
| **Role** | Peran pengguna dalam sistem |
| **OPD** | OPD tempat pengguna ditugaskan (kolom ini hanya terlihat oleh Superadmin) |
| **Status** | Aktif atau Nonaktif |
| **Aksi** | Edit |

### 6.2 Menambah Pengguna

1. Klik **Tambah Pengguna**
2. Isi formulir:

| Field | Keterangan | Wajib |
|-------|------------|-------|
| **Nama** | Nama lengkap pengguna | Ya |
| **Email** | Harus unik, digunakan untuk login | Ya |
| **Password** | Minimal 8 karakter | Ya |
| **Role** | Pilih salah satu: Superadmin, Admin TU, Pimpinan, Staf | Ya |
| **OPD** | Pilih OPD (kosongkan untuk Superadmin) | Tergantung role |

3. Klik **Simpan**

#### Panduan Pemilihan Role

| Siapa | Role yang Diberikan | OPD |
|-------|---------------------|-----|
| Admin pemda (Anda sendiri atau rekan) | `Superadmin` | Kosongkan |
| Petugas TU / operator persuratan OPD | `Admin TU` | Pilih OPD-nya |
| Kepala OPD / Sekretaris / Kabid | `Pimpinan` | Pilih OPD-nya |
| Staf pelaksana yang menerima disposisi | `Staf` | Pilih OPD-nya |

> **Penting:** Setelah OPD baru dibuat, langkah pertama adalah membuat **minimal 1 akun Admin TU** untuk OPD tersebut. Admin TU kemudian dapat membuat sendiri akun Pimpinan dan Staf di OPD-nya.

### 6.3 Mengedit Pengguna

1. Klik **Edit** pada pengguna
2. Ubah data yang diperlukan:
   - Nama, Email, Role, OPD
   - Password: kosongkan jika tidak ingin mengubah
   - Status Aktif: centang/hilangkan centang
3. Klik **Simpan**

### 6.4 Menonaktifkan Pengguna

1. Klik **Edit** pada pengguna
2. Hilangkan centang **Aktif**
3. Klik **Simpan**

**Dampak menonaktifkan pengguna:**
- Pengguna tidak dapat login ke sistem
- Data surat dan disposisi yang sudah tercatat **tetap ada**
- Disposisi yang sedang aktif ke pengguna tersebut tetap tercatat (perlu ditangani manual oleh Admin TU)

### 6.5 Mengubah Role Pengguna

1. Klik **Edit** pada pengguna
2. Ubah pilihan **Role**
3. Klik **Simpan**

> **Perhatian:** Mengubah role akan langsung mengubah hak akses pengguna. Pastikan pengguna tidak sedang dalam proses disposisi aktif yang memerlukan role lamanya.

### 6.6 Memindahkan Pengguna ke OPD Lain

1. Klik **Edit** pada pengguna
2. Ubah pilihan **OPD**
3. Klik **Simpan**

> **Perhatian:** Data surat dan disposisi lama pengguna di OPD sebelumnya **tetap tercatat** di OPD asal. Pengguna akan melihat data OPD barunya setelah login ulang.

### 6.7 Reset Password Pengguna

1. Klik **Edit** pada pengguna
2. Isi field **Password** dengan password baru
3. Klik **Simpan**
4. Informasikan password baru kepada pengguna secara aman

---

## 7. Monitoring Data Persuratan

Sebagai Superadmin, Anda memiliki visibilitas penuh terhadap data persuratan seluruh OPD melalui Dashboard.

### 7.1 Indikator yang Perlu Dipantau

| Indikator | Lokasi | Tindakan Jika Anomali |
|-----------|--------|----------------------|
| OPD dengan 0 surat | Tabel Rekap di Dashboard | Hubungi Admin TU OPD untuk memastikan sistem sudah digunakan |
| OPD dengan 0 pengguna | Tabel Rekap di Dashboard | Buat akun Admin TU untuk OPD tersebut |
| Volume surat menurun drastis | Grafik 12 bulan | Verifikasi apakah ada kendala teknis atau perubahan prosedur |
| Lonjakan volume tidak wajar | Grafik 12 bulan | Periksa apakah ada duplikasi data atau penggunaan tidak sesuai SOP |

### 7.2 Akses Data Detail OPD

Superadmin dapat mengakses halaman **Surat Masuk** dan **Surat Keluar** seluruh OPD secara langsung jika URL diketahui. Namun menu sidebar tidak menampilkan link ini karena fungsi operasional persuratan bukan tanggung jawab Superadmin.

---

## 8. Notifikasi

### 8.1 Lonceng Notifikasi

Ikon lonceng di navbar menampilkan jumlah notifikasi yang belum dibaca. Klik lonceng untuk melihat 10 notifikasi terbaru.

### 8.2 Halaman Semua Notifikasi

Klik **Lihat semua** di dropdown lonceng untuk membuka halaman daftar seluruh notifikasi.

Aksi yang tersedia:
- Klik notifikasi untuk menandai dibaca dan menuju halaman terkait
- Klik **Tandai Semua Dibaca** untuk menandai semua sebagai sudah dibaca

### 8.3 Notifikasi Telegram (Opsional)

Jika bot Telegram sudah dikonfigurasi oleh tim teknis:
1. Buka **Profil** (klik nama Anda > Profil)
2. Isi **Telegram Chat ID**
3. Simpan

Anda akan menerima notifikasi di Telegram untuk setiap aktivitas yang relevan.

---

## 9. Profil & Keamanan Akun

Menu: **Klik nama Anda (pojok kanan atas) > Profil**

### 9.1 Mengubah Nama

1. Buka halaman Profil
2. Ubah field **Nama**
3. Klik **Simpan**

### 9.2 Mengubah Password

1. Buka halaman Profil
2. Isi **Password Saat Ini** (password lama Anda)
3. Isi **Password Baru**
4. Isi **Konfirmasi Password Baru** (harus sama persis)
5. Klik **Simpan**

> **Rekomendasi keamanan:**
> - Ubah password default (`password`) segera setelah pertama kali login
> - Gunakan minimal 8 karakter dengan kombinasi huruf dan angka
> - Jangan gunakan password yang sama dengan akun lain
> - Ubah password secara berkala (disarankan setiap 3 bulan)

### 9.3 Mengisi Telegram Chat ID

1. Buka halaman Profil
2. Isi field **Telegram Chat ID** dengan Chat ID Anda
3. Klik **Simpan**

Cara mendapatkan Chat ID:
1. Cari bot **@userinfobot** di Telegram
2. Kirim pesan `/start`
3. Bot akan membalas dengan informasi Anda, termasuk **Id** (angka). Itulah Chat ID Anda.

---

## 10. Prosedur Operasional Standar

### 10.1 SOP: Menambahkan OPD Baru ke Sistem

```
Langkah 1  →  Buat data OPD baru (Kode, Nama, Format Nomor)
Langkah 2  →  Buat 1 akun Admin TU untuk OPD baru
Langkah 3  →  Informasikan email dan password ke Admin TU
Langkah 4  →  Admin TU login dan membuat akun Pimpinan & Staf
Langkah 5  →  OPD siap menggunakan sistem
```

### 10.2 SOP: Menonaktifkan OPD (Reorganisasi)

```
Langkah 1  →  Koordinasi dengan Admin TU OPD terkait
Langkah 2  →  Pastikan semua surat aktif sudah diarsipkan
Langkah 3  →  Pastikan semua disposisi sudah selesai
Langkah 4  →  Nonaktifkan seluruh pengguna OPD
Langkah 5  →  Nonaktifkan OPD
```

### 10.3 SOP: Pengguna Lupa Password

```
Langkah 1  →  Pengguna menghubungi Admin TU OPD-nya
Langkah 2a →  Admin TU reset password (untuk user OPD-nya sendiri)
Langkah 2b →  Jika Admin TU yang lupa, Superadmin reset password-nya
Langkah 3  →  Informasikan password baru secara aman
Langkah 4  →  Pengguna segera mengubah password di halaman Profil
```

### 10.4 SOP: Menambah Klasifikasi Baru

```
Langkah 1  →  Verifikasi kode klasifikasi sesuai pedoman kearsipan
Langkah 2  →  Pastikan kode belum pernah dipakai (cek daftar klasifikasi)
Langkah 3  →  Tambah klasifikasi baru
Langkah 4  →  Informasikan ke seluruh Admin TU bahwa klasifikasi baru tersedia
```

### 10.5 SOP: Pemantauan Rutin (Mingguan)

```
Langkah 1  →  Login ke sistem
Langkah 2  →  Periksa Dashboard: apakah ada OPD dengan 0 pengguna?
Langkah 3  →  Periksa Dashboard: apakah ada OPD dengan volume surat tidak wajar?
Langkah 4  →  Periksa daftar Pengguna: apakah ada akun nonaktif yang perlu dihapus?
Langkah 5  →  Catat temuan jika ada, koordinasi dengan Admin TU terkait
```

---

## 11. Troubleshooting

### Masalah Umum dan Solusinya

| No | Masalah | Kemungkinan Penyebab | Solusi |
|----|---------|---------------------|--------|
| 1 | Tidak bisa login | Password salah atau akun nonaktif | Reset password via Superadmin lain atau hubungi tim teknis |
| 2 | OPD baru tidak muncul di dropdown | OPD berstatus Nonaktif | Edit OPD, centang Aktif |
| 3 | Admin TU tidak bisa membuat pengguna | Akun Admin TU nonaktif | Aktifkan kembali akun Admin TU |
| 4 | Pengguna muncul di OPD yang salah | OPD salah dipilih saat membuat akun | Edit pengguna, ubah OPD-nya |
| 5 | Klasifikasi tidak bisa dihapus | Sudah digunakan oleh surat | Jangan hapus; biarkan tetap ada atau hubungi tim teknis |
| 6 | Format nomor surat tidak sesuai | Token format salah atau typo | Edit OPD, perbaiki format nomor. Nomor yang sudah terbit tidak berubah. |
| 7 | Grafik dashboard kosong | Belum ada data surat di sistem | Normal untuk sistem baru; grafik terisi seiring penggunaan |
| 8 | Notifikasi Telegram tidak terkirim | Bot token belum dikonfigurasi atau Chat ID kosong | Hubungi tim teknis untuk konfigurasi bot; isi Chat ID di Profil |

### Kapan Menghubungi Tim Teknis

Hubungi tim teknis (administrator server) jika mengalami:
- Halaman error (layar putih atau pesan error)
- Sistem sangat lambat secara keseluruhan
- Data hilang atau tidak konsisten
- Perlu konfigurasi bot Telegram
- Perlu backup atau restore data

---

*Panduan ini disusun untuk pengguna dengan role Superadmin pada aplikasi e-Surat Pemda (SUMAKEL) versi 1.0.*
