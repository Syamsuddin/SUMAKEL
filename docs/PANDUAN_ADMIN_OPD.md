# Panduan Operasional — Role Admin TU OPD

**Aplikasi e-Surat Pemda (SUMAKEL)**
Versi 1.0

---

## Daftar Isi

1. [Pendahuluan](#1-pendahuluan)
2. [Login & Navigasi](#2-login--navigasi)
3. [Dashboard Admin TU](#3-dashboard-admin-tu)
4. [Surat Masuk](#4-surat-masuk)
5. [Disposisi Surat Masuk](#5-disposisi-surat-masuk)
6. [Surat Keluar](#6-surat-keluar)
7. [Buku Agenda & Cetak PDF](#7-buku-agenda--cetak-pdf)
8. [Kelola Pengguna OPD](#8-kelola-pengguna-opd)
9. [Notifikasi](#9-notifikasi)
10. [Profil & Keamanan Akun](#10-profil--keamanan-akun)
11. [Prosedur Operasional Standar](#11-prosedur-operasional-standar)
12. [Troubleshooting](#12-troubleshooting)

---

## 1. Pendahuluan

### 1.1 Tentang Role Admin TU

Admin TU (Tata Usaha) adalah peran utama dalam operasional persuratan di tingkat OPD. Anda adalah **gerbang masuk dan keluar** seluruh surat di OPD Anda.

### 1.2 Tanggung Jawab Utama

| No | Tanggung Jawab | Frekuensi |
|----|----------------|-----------|
| 1 | Mencatat setiap surat masuk yang diterima OPD | Harian |
| 2 | Mendisposisikan surat masuk ke Pimpinan | Harian |
| 3 | Membuat dan menerbitkan surat keluar | Sesuai kebutuhan |
| 4 | Mengirim surat ke OPD lain (routing internal) | Sesuai kebutuhan |
| 5 | Mengarsipkan surat yang sudah selesai | Berkala |
| 6 | Memantau status disposisi dan tindak lanjut | Harian |
| 7 | Mengelola akun pengguna di OPD sendiri | Sesuai kebutuhan |
| 8 | Mencetak buku agenda | Bulanan / sesuai permintaan |

### 1.3 Ringkasan Hak Akses

| Fitur | Hak Admin TU |
|-------|--------------|
| Surat Masuk | Lihat semua (termasuk rahasia), Tambah, Edit, Hapus (status Baru), Arsipkan |
| Disposisi | Membuat disposisi awal ke Pimpinan |
| Surat Keluar | Lihat, Tambah draft, Edit draft, Hapus draft, Terbitkan, Arsipkan |
| Agenda | Lihat, Filter, Cetak PDF |
| Pengguna | Lihat, Tambah, Edit, Nonaktifkan (hanya user di OPD sendiri) |
| Lampiran | Upload dan unduh |
| Dashboard | Widget statistik + grafik volume surat OPD |

### 1.4 Batasan

- Hanya dapat melihat dan mengelola data **OPD sendiri** (tidak bisa akses OPD lain)
- Tidak dapat mengelola OPD atau Klasifikasi (tugas Superadmin)
- Tidak dapat memberikan role `superadmin` kepada pengguna baru

---

## 2. Login & Navigasi

### 2.1 Login

1. Buka alamat aplikasi di browser (contoh: `https://esurat.pemda.go.id`)
2. Masukkan **Email** dan **Password**
3. Klik **Login**
4. Anda akan diarahkan ke Dashboard Admin TU

### 2.2 Menu Sidebar

```
e-Surat
├── Dashboard
│
├── PERSURATAN
│   ├── Surat Masuk
│   ├── Surat Keluar
│   └── Agenda
│
├── MASTER DATA
│   └── Pengguna
```

### 2.3 Navbar Atas

| Elemen | Fungsi |
|--------|--------|
| Logo **e-Surat** | Kembali ke Dashboard |
| Lonceng | Notifikasi (surat antar-OPD masuk, dll.) |
| Nama + Role + OPD | Identitas Anda; klik untuk Profil atau Keluar |

---

## 3. Dashboard Admin TU

Dashboard menampilkan tiga widget statistik dan satu grafik.

### 3.1 Widget Statistik

| Widget | Warna | Keterangan |
|--------|-------|------------|
| **Surat Masuk Bulan Ini** | Biru | Jumlah surat masuk yang diterima OPD di bulan berjalan |
| **Surat Keluar Bulan Ini** | Hijau | Jumlah surat keluar yang dibuat OPD di bulan berjalan |
| **SM Belum Disposisi** | Kuning | Jumlah surat masuk berstatus "Baru" yang belum didisposisikan |

> **Perhatian:** Jika angka **SM Belum Disposisi** tinggi, segera disposisikan surat-surat tersebut ke Pimpinan agar tidak terjadi penumpukan.

### 3.2 Grafik Volume 12 Bulan

Grafik garis yang menampilkan tren Surat Masuk (biru) dan Surat Keluar (hijau) OPD Anda selama 12 bulan terakhir.

---

## 4. Surat Masuk

Menu: **Sidebar > Persuratan > Surat Masuk**

### 4.1 Melihat Daftar Surat Masuk

Halaman ini menampilkan seluruh surat masuk OPD Anda dengan kolom:

| Kolom | Keterangan |
|-------|------------|
| **No. Agenda** | Nomor urut pencatatan (otomatis per OPD per tahun) |
| **Nomor Surat** | Nomor surat dari pengirim |
| **Asal** | Instansi/pihak pengirim |
| **Perihal** | Ringkasan isi surat |
| **Tanggal Terima** | Tanggal surat diterima OPD |
| **Sifat** | Biasa / Penting / Rahasia |
| **Status** | Baru / Didisposisi / Selesai / Diarsip |

### 4.2 Filter & Pencarian

Gunakan panel filter di atas tabel:

| Filter | Fungsi |
|--------|--------|
| **Dari — Sampai** | Filter berdasarkan rentang tanggal terima |
| **Klasifikasi** | Filter berdasarkan kode klasifikasi (contoh: 005 - Undangan) |
| **Sifat** | Filter: Biasa, Penting, atau Rahasia |
| **Status** | Filter: Baru, Didisposisi, Selesai, atau Diarsip |
| **Cari** | Pencarian teks di kolom Perihal, Asal Surat, dan Nomor Surat |

Klik **Filter** untuk menerapkan. Klik **Reset** untuk menghapus semua filter.

### 4.3 Mencatat Surat Masuk Baru

1. Klik tombol **Catat Surat Masuk** di pojok kanan atas
2. Isi formulir:

| Field | Keterangan | Wajib |
|-------|------------|-------|
| **Nomor Surat** | Nomor surat sesuai tertera di dokumen | Ya |
| **Asal Surat** | Nama instansi/pihak pengirim | Ya |
| **Klasifikasi** | Pilih kode klasifikasi yang sesuai | Ya |
| **Tanggal Surat** | Tanggal yang tertera pada surat | Ya |
| **Tanggal Terima** | Tanggal surat diterima di OPD (default: hari ini) | Ya |
| **Perihal** | Ringkasan isi/perihal surat | Ya |
| **Sifat** | Biasa, Penting, atau Rahasia | Ya |
| **Lampiran** | File PDF/JPG/PNG, maksimal 10 MB per file. Bisa lebih dari satu file. | Tidak |

3. Klik **Simpan**

Sistem akan otomatis:
- Memberikan **Nomor Agenda** berurutan (per OPD per tahun)
- Menyimpan file lampiran ke server
- Mengarahkan Anda ke halaman detail surat

> **Tips:** Scan surat fisik ke PDF dan lampirkan saat pencatatan agar arsip digital lengkap.

### 4.4 Melihat Detail Surat Masuk

Klik **Detail** pada surat di daftar. Halaman detail menampilkan:

- **Informasi surat** — Nomor surat, asal, klasifikasi, tanggal, perihal, sifat, status, nomor agenda
- **Lampiran** — Daftar file yang dapat diunduh
- **Timeline** — Riwayat kronologis disposisi dan tindak lanjut
- **Form Disposisi** — Panel di sisi kanan untuk mendisposisikan surat (lihat Bab 5)

### 4.5 Mengedit Surat Masuk

1. Buka halaman Detail surat
2. Klik **Edit**
3. Ubah data yang perlu diperbaiki
4. Untuk menambah lampiran baru, pilih file di field Lampiran (lampiran lama tidak terhapus)
5. Klik **Simpan**

> **Catatan:** Surat yang sudah berstatus **Diarsip** tidak dapat diedit.

### 4.6 Menghapus Surat Masuk

Surat hanya dapat dihapus jika masih berstatus **Baru** (belum ada disposisi).

1. Buka halaman Detail surat
2. Gunakan aksi hapus
3. Konfirmasi penghapusan

> **Peringatan:** Penghapusan bersifat permanen. File lampiran juga akan ikut terhapus.

### 4.7 Mengarsipkan Surat Masuk

Surat yang sudah berstatus **Selesai** (seluruh disposisi telah ditindaklanjuti) dapat diarsipkan.

1. Buka halaman Detail surat
2. Klik **Arsipkan**
3. Konfirmasi

Setelah diarsipkan:
- Status berubah menjadi **Diarsip**
- Surat menjadi read-only (tidak dapat diedit atau didisposisikan lagi)
- Surat tetap tampil di daftar dan bisa dicari

---

## 5. Disposisi Surat Masuk

Disposisi adalah instruksi dari atasan kepada bawahan untuk menindaklanjuti surat masuk. Sebagai Admin TU, Anda memulai rantai disposisi.

### 5.1 Alur Disposisi

```
Admin TU → Pimpinan → Staf
              │
              ├── Staf A → tindak lanjut → selesai
              └── Staf B → tindak lanjut → selesai
```

- **Admin TU** membuat disposisi awal ke **Pimpinan** (disposisi akar)
- **Pimpinan** meneruskan ke **Staf** (disposisi anak)
- **Staf** mencatat tindak lanjut dan menandai selesai
- Sistem otomatis mengubah status surat:
  - Ada disposisi → **Didisposisi**
  - Semua disposisi selesai → **Selesai**

### 5.2 Membuat Disposisi Awal

Syarat: Surat harus berstatus **Baru**.

1. Buka halaman Detail surat masuk
2. Di panel kanan, cari bagian **Disposisikan**
3. Isi formulir:

| Field | Keterangan | Wajib |
|-------|------------|-------|
| **Kepada** | Pilih Pimpinan atau staf se-OPD yang akan menerima disposisi | Ya |
| **Instruksi** | Instruksi atau catatan untuk penerima (contoh: "Mohon arahan untuk ditindaklanjuti") | Ya |
| **Batas Waktu** | Tanggal tenggat penyelesaian | Tidak |

4. Klik **Kirim Disposisi**

Setelah disposisi terkirim:
- Status surat otomatis berubah menjadi **Didisposisi**
- Penerima menerima **notifikasi** (in-app + Telegram jika aktif)
- Disposisi tercatat di **Timeline** halaman detail surat

### 5.3 Memantau Status Disposisi

Buka halaman Detail surat. Bagian **Timeline** menampilkan:

| Informasi | Keterangan |
|-----------|------------|
| Nama pengirim → penerima | Siapa mendisposisi ke siapa |
| Instruksi | Isi instruksi disposisi |
| Status disposisi | Terkirim / Dibaca / Diproses / Selesai |
| Batas waktu | Jika diisi, ditampilkan dalam warna merah |
| Tindak lanjut | Catatan dan lampiran dari penerima |
| Waktu | Tanggal dan jam setiap aktivitas |

#### Arti Status Disposisi

| Status | Keterangan |
|--------|------------|
| **Terkirim** | Disposisi sudah dikirim, penerima belum membuka |
| **Dibaca** | Penerima sudah membuka halaman detail surat |
| **Diproses** | Penerima meneruskan disposisi ke orang lain |
| **Selesai** | Penerima sudah mencatat tindak lanjut dan menandai selesai |

### 5.4 Surat Masuk Antar-OPD

Jika OPD lain mengirim surat internal ke OPD Anda, surat tersebut otomatis muncul di daftar Surat Masuk dengan:

- **Asal Surat** = nama OPD pengirim
- **Nomor Surat** = nomor surat keluar OPD pengirim
- Lampiran otomatis terduplikasi

Anda menerima **notifikasi** saat surat antar-OPD masuk. Perlakukan seperti surat masuk biasa: periksa, disposisikan.

---

## 6. Surat Keluar

Menu: **Sidebar > Persuratan > Surat Keluar**

### 6.1 Konsep Draft dan Terbitkan

Surat keluar menggunakan sistem **dua tahap**:

```
Tahap 1: DRAFT → belum bernomor, masih bisa diedit/hapus
Tahap 2: TERBIT → nomor otomatis digenerate, tidak bisa diedit lagi
```

> **Alasan:** Agar draft yang dibatalkan tidak membolongi urutan nomor surat.

### 6.2 Melihat Daftar Surat Keluar

| Kolom | Keterangan |
|-------|------------|
| **Nomor** | Nomor surat (kosong jika masih draft) |
| **Perihal** | Ringkasan isi surat |
| **Tujuan** | Nama instansi/OPD tujuan |
| **Tanggal** | Tanggal surat |
| **Sifat** | Biasa / Penting / Rahasia |
| **Status** | Draft / Terbit / Diarsip |

Filter tersedia: Dari-Sampai, Status, Cari.

### 6.3 Membuat Draft Surat Keluar

1. Klik **Buat Surat Keluar**
2. Isi formulir:

| Field | Keterangan | Wajib |
|-------|------------|-------|
| **Klasifikasi** | Kode klasifikasi surat | Ya |
| **Tanggal Surat** | Tanggal surat (default: hari ini) | Ya |
| **Sifat** | Biasa, Penting, atau Rahasia | Ya |
| **Perihal** | Ringkasan isi surat | Ya |
| **Jenis Tujuan** | Eksternal atau Internal (Antar-OPD) | Ya |
| **Tujuan Eksternal** | Nama instansi tujuan (jika Eksternal) | Kondisional |
| **Tujuan OPD** | Pilih OPD tujuan (jika Internal) | Kondisional |
| **Lampiran PDF** | File PDF surat. Bisa lebih dari satu. | Tidak |

3. Klik **Simpan Draft**

> **Tips:** Pilih **Jenis Tujuan** terlebih dahulu — form akan menyesuaikan field tujuan secara otomatis.

### 6.4 Mengedit Draft

1. Buka halaman Detail surat keluar
2. Klik **Edit**
3. Ubah data yang perlu diperbaiki
4. Klik **Simpan**

> Hanya surat berstatus **Draft** yang bisa diedit.

### 6.5 Menghapus Draft

1. Buka daftar Surat Keluar atau halaman Detail
2. Hapus draft yang tidak jadi digunakan
3. Konfirmasi

> Hanya surat berstatus **Draft** yang bisa dihapus.

### 6.6 Menerbitkan Surat Keluar

**Syarat:** Surat harus berstatus Draft **dan** sudah memiliki minimal satu lampiran PDF.

1. Buka halaman Detail surat keluar
2. Klik tombol **Terbitkan**
3. Baca konfirmasi: *"Terbitkan surat ini? Nomor akan digenerate dan tidak dapat diubah."*
4. Konfirmasi

Setelah diterbitkan:
- Sistem **generate nomor otomatis** sesuai format OPD (contoh: `005/001/SETDA/VI/2026`)
- Status berubah menjadi **Terbit**
- Surat tidak dapat diedit atau dihapus lagi

> **Penting:** Pastikan lampiran PDF sudah benar sebelum menerbitkan. Setelah terbit, nomor surat tidak bisa diubah atau dibatalkan.

### 6.7 Surat Keluar Internal (Antar-OPD)

Jika jenis tujuan adalah **Internal** dan Anda memilih OPD tujuan:

Saat diterbitkan, sistem secara otomatis:
1. Generate nomor surat
2. Membuat **Surat Masuk** di OPD tujuan berisi:
   - Nomor surat = nomor surat keluar Anda
   - Asal surat = nama OPD Anda
   - Lampiran terduplikasi
   - Nomor agenda otomatis di OPD tujuan
3. Mengirim **notifikasi** ke seluruh Admin TU di OPD tujuan
4. Menampilkan status tautan: *"Diterima sebagai agenda #N di {OPD Tujuan}"*

Semua proses ini terjadi dalam satu transaksi atomik — jika ada yang gagal, semuanya dibatalkan.

### 6.8 Mengarsipkan Surat Keluar

Surat yang sudah berstatus **Terbit** dapat diarsipkan.

1. Buka halaman Detail surat keluar
2. Klik **Arsipkan**
3. Konfirmasi

---

## 7. Buku Agenda & Cetak PDF

Menu: **Sidebar > Persuratan > Agenda**

Buku Agenda menampilkan gabungan surat masuk dan surat keluar dalam satu tabel kronologis.

### 7.1 Melihat Agenda

1. Buka halaman Agenda
2. Isi filter (wajib):
   - **Dari** — Tanggal awal periode
   - **Sampai** — Tanggal akhir periode
3. Filter opsional:
   - **Jenis** — Semua / Masuk saja / Keluar saja
   - **Klasifikasi** — Filter berdasarkan kode klasifikasi
4. Klik **Filter**

Tabel menampilkan:

| Kolom | Keterangan |
|-------|------------|
| **No** | Nomor urut dalam daftar |
| **Jenis** | Masuk (badge biru) atau Keluar (badge hijau) |
| **Nomor** | Nomor surat |
| **Perihal** | Ringkasan isi surat |
| **Tanggal** | Tanggal terima (masuk) atau tanggal surat (keluar) |
| **Asal/Tujuan** | Pengirim (masuk) atau penerima (keluar) |
| **Klasifikasi** | Kode klasifikasi surat |

> **Catatan:** Surat keluar berstatus Draft tidak ditampilkan di Agenda.

### 7.2 Cetak Buku Agenda PDF

1. Pastikan filter **Dari** dan **Sampai** sudah terisi
2. Klik tombol **Cetak PDF**
3. File PDF terbuka di tab baru browser
4. Gunakan fitur cetak browser (Ctrl+P) atau simpan sebagai file

Hasil cetak berisi:
- **Kop** — "PEMERINTAH DAERAH" dan nama OPD Anda
- **Judul** — "BUKU AGENDA SURAT" dengan periode
- **Tabel** — Seluruh surat dalam periode, diurutkan berdasarkan tanggal
- **Orientasi** — Landscape (melebar)

> **Tips:** Untuk laporan bulanan, gunakan filter tanggal awal dan akhir bulan tersebut.

---

## 8. Kelola Pengguna OPD

Menu: **Sidebar > Master Data > Pengguna**

Sebagai Admin TU, Anda mengelola akun pengguna di **OPD Anda sendiri**. Anda tidak dapat melihat atau mengelola pengguna OPD lain.

### 8.1 Melihat Daftar Pengguna

Tabel menampilkan seluruh pengguna di OPD Anda:

| Kolom | Keterangan |
|-------|------------|
| **Nama** | Nama lengkap pengguna |
| **Email** | Digunakan untuk login |
| **Role** | Admin TU, Pimpinan, atau Staf |
| **Status** | Aktif atau Nonaktif |

### 8.2 Menambah Pengguna Baru

1. Klik **Tambah Pengguna**
2. Isi formulir:

| Field | Keterangan | Wajib |
|-------|------------|-------|
| **Nama** | Nama lengkap | Ya |
| **Email** | Harus unik di seluruh sistem | Ya |
| **Password** | Minimal 8 karakter | Ya |
| **Role** | Pilih: Admin TU, Pimpinan, atau Staf | Ya |

3. Klik **Simpan**

> **Catatan:** Anda tidak bisa memilih OPD (otomatis OPD Anda sendiri) dan tidak bisa memberi role Superadmin.

#### Panduan Pemberian Role

| Jabatan | Role yang Tepat |
|---------|-----------------|
| Rekan sesama petugas TU | Admin TU |
| Kepala OPD / Sekretaris / Kabid / Kasubbag | Pimpinan |
| Staf pelaksana yang menerima tugas dari atasan | Staf |

### 8.3 Mengedit Pengguna

1. Klik **Edit** pada pengguna
2. Ubah data: Nama, Email, Password (kosongkan jika tidak diubah), Role, Status Aktif
3. Klik **Simpan**

### 8.4 Menonaktifkan Pengguna

1. Klik **Edit** pada pengguna
2. Hilangkan centang **Aktif**
3. Klik **Simpan**

Gunakan untuk:
- Pegawai yang mutasi keluar OPD
- Akun yang tidak digunakan lagi
- Menonaktifkan sementara (bisa diaktifkan kembali)

> **Penting:** Periksa apakah pengguna tersebut memiliki disposisi aktif sebelum menonaktifkan. Jika ada, koordinasikan penyelesaian disposisi terlebih dahulu.

### 8.5 Reset Password Pengguna

1. Klik **Edit** pada pengguna
2. Isi field **Password** dengan password baru
3. Klik **Simpan**
4. Informasikan password baru secara aman (tatap muka atau pesan pribadi)

---

## 9. Notifikasi

### 9.1 Jenis Notifikasi untuk Admin TU

| Notifikasi | Kapan Muncul |
|------------|-------------|
| **Surat Masuk Antar-OPD** | Saat OPD lain mengirim surat internal ke OPD Anda |
| **Disposisi Baru** | Jika Anda juga berperan sebagai penerima disposisi |
| **Tindak Lanjut Baru** | Saat seseorang mencatat tindak lanjut atas disposisi yang Anda kirim |

### 9.2 Menggunakan Lonceng Notifikasi

1. Klik ikon **lonceng** di navbar — muncul dropdown 10 notifikasi terbaru
2. Badge angka merah menunjukkan jumlah notifikasi belum dibaca
3. Klik salah satu notifikasi untuk:
   - Menandai sebagai dibaca
   - Langsung dibawa ke halaman surat terkait
4. Klik **Tandai semua dibaca** untuk membersihkan badge

### 9.3 Halaman Semua Notifikasi

Klik **Lihat semua** di dropdown lonceng untuk membuka daftar lengkap seluruh notifikasi.

- Notifikasi belum dibaca ditampilkan dengan **latar tebal**
- Klik untuk menandai dibaca dan menuju halaman terkait

### 9.4 Notifikasi Telegram

Jika dikonfigurasi, Anda akan menerima pesan Telegram untuk setiap notifikasi. Lihat Bab 10.3 untuk cara pengaturan.

---

## 10. Profil & Keamanan Akun

Menu: **Klik nama Anda (pojok kanan atas) > Profil**

### 10.1 Mengubah Nama Tampilan

1. Buka halaman Profil
2. Ubah field **Nama**
3. Klik **Simpan**

### 10.2 Mengubah Password

1. Buka halaman Profil
2. Isi **Password Saat Ini**
3. Isi **Password Baru** (minimal 8 karakter)
4. Isi **Konfirmasi Password Baru**
5. Klik **Simpan**

> **Rekomendasi:**
> - Segera ubah password default saat pertama kali login
> - Gunakan kombinasi huruf besar, huruf kecil, dan angka
> - Ubah password setiap 3 bulan

### 10.3 Mengaktifkan Notifikasi Telegram

1. Buka aplikasi Telegram di HP atau desktop
2. Cari bot **@userinfobot** dan kirim `/start` — catat angka **Id** yang diberikan
3. Cari bot e-Surat (nama bot sesuai yang dikonfigurasi tim teknis) dan kirim `/start`
4. Kembali ke aplikasi e-Surat, buka **Profil**
5. Isi field **Telegram Chat ID** dengan angka Id tadi
6. Klik **Simpan**

Setelah diatur, Anda akan menerima pesan Telegram setiap ada notifikasi baru.

---

## 11. Prosedur Operasional Standar

### 11.1 SOP: Menerima dan Mencatat Surat Masuk Harian

```
Langkah 1  →  Terima surat fisik / email
Langkah 2  →  Scan dokumen ke PDF (jika fisik)
Langkah 3  →  Login ke e-Surat, buka Surat Masuk > Catat Surat Masuk
Langkah 4  →  Isi seluruh field, upload lampiran PDF
Langkah 5  →  Simpan — sistem memberikan Nomor Agenda otomatis
Langkah 6  →  Buka halaman Detail, disposisikan ke Pimpinan
Langkah 7  →  Selesai — pantau timeline untuk perkembangan
```

### 11.2 SOP: Membuat dan Mengirim Surat Keluar Eksternal

```
Langkah 1  →  Siapkan dokumen surat (di luar aplikasi, misal MS Word)
Langkah 2  →  Cetak, tandatangani, scan ke PDF
Langkah 3  →  Buka Surat Keluar > Buat Surat Keluar
Langkah 4  →  Isi data, pilih Jenis Tujuan "Eksternal", isi tujuan
Langkah 5  →  Upload file PDF, Simpan Draft
Langkah 6  →  Periksa ulang di halaman Detail
Langkah 7  →  Klik Terbitkan — nomor otomatis digenerate
Langkah 8  →  Catat nomor surat untuk ditulis/cap di dokumen fisik
```

### 11.3 SOP: Mengirim Surat ke OPD Lain (Internal)

```
Langkah 1  →  Buat surat keluar seperti biasa
Langkah 2  →  Pilih Jenis Tujuan "Internal (Antar-OPD)"
Langkah 3  →  Pilih OPD tujuan dari dropdown
Langkah 4  →  Upload PDF, Simpan Draft
Langkah 5  →  Terbitkan — surat otomatis masuk ke OPD tujuan
Langkah 6  →  Periksa status: "Diterima sebagai agenda #N di {OPD}"
```

> **Keuntungan:** Admin TU OPD tujuan tidak perlu mencatat ulang — surat langsung muncul di daftar Surat Masuk mereka.

### 11.4 SOP: Mencetak Buku Agenda Bulanan

```
Langkah 1  →  Buka Agenda
Langkah 2  →  Isi Dari = tanggal 1 bulan yang dimaksud
Langkah 3  →  Isi Sampai = tanggal terakhir bulan tersebut
Langkah 4  →  Klik Filter — periksa data di layar
Langkah 5  →  Klik Cetak PDF
Langkah 6  →  Cetak atau simpan file PDF
```

### 11.5 SOP: Menambahkan Pengguna Baru di OPD

```
Langkah 1  →  Terima permintaan dari atasan/pegawai baru
Langkah 2  →  Buka Pengguna > Tambah Pengguna
Langkah 3  →  Isi nama, email, password awal, pilih role yang sesuai
Langkah 4  →  Simpan
Langkah 5  →  Informasikan email dan password ke pengguna secara aman
Langkah 6  →  Instruksikan pengguna untuk segera mengganti password
              dan mengisi Telegram Chat ID di halaman Profil
```

### 11.6 SOP: Arsip Akhir Bulan

```
Langkah 1  →  Buka Surat Masuk, filter Status = "Selesai"
Langkah 2  →  Untuk setiap surat yang sudah selesai:
              Buka Detail → klik Arsipkan → Konfirmasi
Langkah 3  →  Buka Surat Keluar, filter Status = "Terbit"
Langkah 4  →  Untuk setiap surat yang sudah tidak aktif:
              Buka Detail → klik Arsipkan → Konfirmasi
Langkah 5  →  Cetak Buku Agenda periode bulan tersebut (lihat SOP 11.4)
```

### 11.7 SOP: Pegawai Lupa Password

```
Langkah 1  →  Pegawai melapor ke Anda
Langkah 2  →  Buka Pengguna > Edit pengguna tersebut
Langkah 3  →  Isi field Password dengan password baru
Langkah 4  →  Simpan
Langkah 5  →  Informasikan password baru secara langsung
Langkah 6  →  Instruksikan pegawai untuk segera mengganti password
              di halaman Profil
```

---

## 12. Troubleshooting

### Masalah Umum dan Solusinya

| No | Masalah | Penyebab | Solusi |
|----|---------|----------|--------|
| 1 | Tombol "Catat Surat Masuk" tidak muncul | Anda bukan Admin TU | Pastikan login dengan akun Admin TU |
| 2 | Tombol "Disposisikan" tidak muncul | Surat bukan berstatus "Baru", atau sudah diarsipkan | Periksa status surat; hanya surat Baru yang bisa didisposisikan oleh Admin TU |
| 3 | Tombol "Terbitkan" tidak muncul | Draft belum ada lampiran PDF | Upload minimal 1 file PDF ke surat keluar terlebih dahulu |
| 4 | Upload lampiran gagal | File terlalu besar (> 10 MB) atau format tidak didukung | Kompres file atau ubah ke format PDF/JPG/PNG |
| 5 | Nomor agenda tidak berurutan | Surat dihapus sebelumnya | Normal — nomor yang sudah digenerate tidak bisa dipakai ulang |
| 6 | Surat dari OPD lain tidak muncul | Belum di-refresh atau filter aktif | Refresh halaman dan reset filter |
| 7 | Tidak bisa edit surat keluar | Surat sudah diterbitkan | Surat yang sudah Terbit tidak bisa diedit lagi (by design) |
| 8 | Tidak bisa arsipkan surat masuk | Masih ada disposisi yang belum selesai | Pastikan semua disposisi berstatus Selesai terlebih dahulu |
| 9 | Pengguna yang saya buat tidak bisa login | Password salah atau akun nonaktif | Edit pengguna, reset password, pastikan status Aktif |
| 10 | Notifikasi Telegram tidak masuk | Chat ID belum diisi atau bot tidak dikonfigurasi | Isi Chat ID di Profil; hubungi tim teknis jika bot belum aktif |
| 11 | Halaman error / layar putih | Masalah server | Hubungi Superadmin atau tim teknis |
| 12 | Data surat tidak tampil | Filter aktif yang menyembunyikan data | Klik Reset pada panel filter |

### Kapan Menghubungi Superadmin

Hubungi Superadmin jika:
- Anda membutuhkan **klasifikasi surat baru** yang belum tersedia
- Ada masalah dengan **format penomoran surat** OPD Anda
- Anda lupa password dan **tidak ada Admin TU lain** yang bisa mereset
- Perlu **menambahkan OPD** baru ke dalam sistem

### Kapan Menghubungi Tim Teknis

Hubungi tim teknis jika:
- Aplikasi menampilkan **halaman error** atau tidak bisa diakses
- Terjadi **kehilangan data** yang tidak wajar
- Perlu konfigurasi **bot Telegram**
- Aplikasi **sangat lambat** secara keseluruhan

---

*Panduan ini disusun untuk pengguna dengan role Admin TU pada aplikasi e-Surat Pemda (SUMAKEL) versi 1.0.*
