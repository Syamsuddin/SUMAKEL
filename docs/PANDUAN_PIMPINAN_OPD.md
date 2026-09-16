# Panduan Operasional — Role Pimpinan OPD

**Aplikasi e-Surat Pemda (SUMAKEL)**
Versi 1.0

---

## Daftar Isi

1. [Pendahuluan](#1-pendahuluan)
2. [Login & Navigasi](#2-login--navigasi)
3. [Dashboard Pimpinan](#3-dashboard-pimpinan)
4. [Surat Masuk](#4-surat-masuk)
5. [Menerima & Meneruskan Disposisi](#5-menerima--meneruskan-disposisi)
6. [Mencatat Tindak Lanjut](#6-mencatat-tindak-lanjut)
7. [Surat Keluar](#7-surat-keluar)
8. [Buku Agenda](#8-buku-agenda)
9. [Notifikasi](#9-notifikasi)
10. [Profil & Keamanan Akun](#10-profil--keamanan-akun)
11. [Prosedur Operasional Standar](#11-prosedur-operasional-standar)
12. [Troubleshooting](#12-troubleshooting)

---

## 1. Pendahuluan

### 1.1 Tentang Role Pimpinan

Pimpinan adalah role yang mewakili pejabat pengambil keputusan di OPD — Kepala OPD, Sekretaris, Kepala Bidang, atau Kepala Sub Bagian. Anda menerima disposisi dari Admin TU, memberikan arahan, dan meneruskan tugas ke Staf.

### 1.2 Tanggung Jawab Utama

| No | Tanggung Jawab | Frekuensi |
|----|----------------|-----------|
| 1 | Memeriksa disposisi masuk dari Admin TU | Harian |
| 2 | Memberikan arahan dan meneruskan disposisi ke Staf | Harian |
| 3 | Memantau progres tindak lanjut dari Staf | Harian |
| 4 | Menandai disposisi selesai setelah tindak lanjut tuntas | Sesuai kebutuhan |
| 5 | Melihat seluruh surat masuk dan keluar OPD | Sesuai kebutuhan |

### 1.3 Ringkasan Hak Akses

| Fitur | Hak Pimpinan |
|-------|-------------|
| Surat Masuk | Lihat semua surat OPD (termasuk sifat rahasia) |
| Disposisi | Menerima dari Admin TU, meneruskan ke Staf/rekan se-OPD |
| Tindak Lanjut | Mencatat tindak lanjut dan menandai disposisi selesai |
| Surat Keluar | Lihat seluruh surat keluar OPD |
| Agenda | Lihat dan cetak buku agenda PDF |
| Lampiran | Mengunduh semua lampiran surat OPD |
| Dashboard | Daftar disposisi yang menunggu keputusan |

### 1.4 Batasan

- **Tidak dapat** mencatat surat masuk baru (tugas Admin TU)
- **Tidak dapat** membuat atau menerbitkan surat keluar (tugas Admin TU)
- **Tidak dapat** mengedit, menghapus, atau mengarsipkan surat (tugas Admin TU)
- **Tidak dapat** mengelola pengguna, OPD, atau klasifikasi
- Hanya melihat data **OPD sendiri**

---

## 2. Login & Navigasi

### 2.1 Login

1. Buka alamat aplikasi di browser
2. Masukkan **Email** dan **Password**
3. Klik **Login**
4. Anda diarahkan ke Dashboard Pimpinan

### 2.2 Menu Sidebar

```
e-Surat
├── Dashboard
│
├── PERSURATAN
│   ├── Surat Masuk
│   ├── Surat Keluar
│   └── Agenda
```

> **Catatan:** Menu "Master Data" tidak tampil karena bukan bagian dari fungsi Pimpinan.

### 2.3 Navbar Atas

| Elemen | Fungsi |
|--------|--------|
| Logo **e-Surat** | Kembali ke Dashboard |
| Lonceng | Notifikasi — disposisi baru, tindak lanjut baru |
| Nama + Role + OPD | Identitas Anda; klik untuk Profil atau Keluar |

### 2.4 Logout

Klik nama Anda di pojok kanan atas → **Keluar**.

---

## 3. Dashboard Pimpinan

Dashboard Pimpinan dirancang agar Anda langsung melihat apa yang perlu diputuskan hari ini.

### 3.1 Daftar Disposisi Menunggu

Menampilkan hingga 10 disposisi terbaru yang ditujukan kepada Anda dengan status **Terkirim** atau **Dibaca** (belum Anda tindak lanjuti atau teruskan).

Setiap item menampilkan:

| Informasi | Keterangan |
|-----------|------------|
| **Perihal surat** | Judul/perihal surat masuk terkait |
| **Instruksi** | Instruksi dari pengirim disposisi |
| **Badge status** | Merah = Terkirim (belum dibuka), Biru = Dibaca |

**Klik item** untuk langsung membuka halaman Detail Surat Masuk dan menindaklanjuti.

### 3.2 Menggunakan Dashboard Secara Efektif

Jadikan Dashboard sebagai **halaman pertama** yang Anda periksa setiap hari kerja:

1. Buka Dashboard
2. Periksa apakah ada disposisi berstatus **Terkirim** (merah) — ini yang paling urgent
3. Klik item untuk membuka detail surat
4. Baca surat dan lampiran
5. Putuskan: teruskan ke Staf, atau tindak lanjuti sendiri

---

## 4. Surat Masuk

Menu: **Sidebar > Persuratan > Surat Masuk**

### 4.1 Melihat Daftar Surat Masuk

Anda dapat melihat **seluruh surat masuk OPD**, termasuk surat bersifat **rahasia**.

| Kolom | Keterangan |
|-------|------------|
| **No. Agenda** | Nomor urut pencatatan |
| **Nomor Surat** | Nomor surat dari pengirim |
| **Asal** | Instansi/pihak pengirim |
| **Perihal** | Ringkasan isi surat |
| **Tanggal Terima** | Tanggal surat diterima OPD |
| **Sifat** | Biasa / Penting / Rahasia |
| **Status** | Baru / Didisposisi / Selesai / Diarsip |

### 4.2 Filter & Pencarian

| Filter | Fungsi |
|--------|--------|
| **Dari — Sampai** | Rentang tanggal terima |
| **Klasifikasi** | Jenis surat berdasarkan kode arsip |
| **Sifat** | Biasa, Penting, atau Rahasia |
| **Status** | Baru, Didisposisi, Selesai, atau Diarsip |
| **Cari** | Pencarian teks di Perihal, Asal, dan Nomor Surat |

> **Tips:** Filter **Status = Didisposisi** untuk melihat surat yang sedang dalam proses tindak lanjut. Filter **Sifat = Rahasia** untuk memeriksa surat rahasia.

### 4.3 Melihat Detail Surat

Klik **Detail** pada surat di daftar. Halaman ini menampilkan:

**Kolom kiri (8/12):**
- **Informasi lengkap surat** — nomor, asal, klasifikasi, tanggal, perihal, sifat, status, nomor agenda
- **Lampiran** — daftar file yang dapat diunduh (klik untuk download)
- **Timeline** — riwayat kronologis seluruh disposisi dan tindak lanjut

**Kolom kanan (4/12):**
- **Form Disposisi** — untuk meneruskan disposisi ke Staf (muncul jika Anda memiliki disposisi aktif)
- **Form Tindak Lanjut** — untuk menyelesaikan disposisi sendiri (muncul jika Anda memiliki disposisi aktif)

### 4.4 Mengunduh Lampiran

Klik nama file di bagian **Lampiran** — file langsung terunduh ke perangkat Anda.

Lampiran juga tersedia di tindak lanjut (jika Staf menyertakan dokumen pendukung).

---

## 5. Menerima & Meneruskan Disposisi

Ini adalah **fungsi utama** Anda dalam sistem.

### 5.1 Alur Disposisi dari Sudut Pandang Pimpinan

```
Admin TU mengirim disposisi ke ANDA
        |
  ANDA menerima notifikasi
        |
  ANDA membuka detail surat → status disposisi: "Dibaca"
        |
  Pilihan:
  ├── Teruskan ke Staf (disposisi anak)
  ├── Tindak lanjuti sendiri
  └── Teruskan + tindak lanjut sendiri
        |
  Staf mencatat tindak lanjut → ANDA menerima notifikasi
        |
  Jika semua disposisi selesai → status surat: "Selesai"
```

### 5.2 Meneruskan Disposisi ke Staf

Ketika Anda menerima disposisi dan ingin meneruskan ke bawahan:

1. Buka halaman Detail Surat Masuk (dari Dashboard atau daftar Surat Masuk)
2. Di panel kanan, cari bagian **Disposisikan**
3. Isi formulir:

| Field | Keterangan | Wajib |
|-------|------------|-------|
| **Kepada** | Pilih Staf atau rekan se-OPD. Dropdown menampilkan nama dan role. | Ya |
| **Instruksi** | Arahan Anda untuk penerima. Tulis dengan jelas dan spesifik. | Ya |
| **Batas Waktu** | Tanggal tenggat penyelesaian (opsional tapi disarankan) | Tidak |

4. Klik **Kirim Disposisi**

Setelah dikirim:
- Status disposisi Anda otomatis berubah menjadi **Diproses**
- Disposisi anak tercatat di Timeline
- Penerima mendapat **notifikasi** (in-app + Telegram)

### 5.3 Meneruskan ke Beberapa Staf

Anda dapat membuat **lebih dari satu disposisi anak**:

1. Teruskan ke Staf A dengan instruksi pertama
2. Kembali ke halaman detail surat yang sama
3. Teruskan ke Staf B dengan instruksi berbeda

Setiap disposisi berdiri sendiri — masing-masing harus diselesaikan oleh penerimanya.

### 5.4 Kapan Form Disposisi Muncul

Form "Disposisikan" hanya muncul jika **semua syarat** terpenuhi:
- Anda memiliki disposisi aktif (berstatus Terkirim, Dibaca, atau Diproses) untuk surat tersebut
- Surat belum berstatus Diarsip

Jika form tidak muncul, periksa:
- Apakah disposisi sudah Anda tandai Selesai?
- Apakah surat sudah diarsipkan oleh Admin TU?

### 5.5 Memahami Status Disposisi

| Status | Arti | Siapa yang Mengubah |
|--------|------|---------------------|
| **Terkirim** | Disposisi sudah dikirim, penerima belum membuka | Otomatis saat disposisi dibuat |
| **Dibaca** | Penerima sudah membuka halaman detail surat | Otomatis saat penerima membuka |
| **Diproses** | Penerima meneruskan disposisi ke orang lain | Otomatis saat penerima meneruskan |
| **Selesai** | Penerima mencatat tindak lanjut dan menandai selesai | Manual oleh penerima |

---

## 6. Mencatat Tindak Lanjut

Selain meneruskan disposisi, Anda juga dapat **menindaklanjuti langsung** dan menandai disposisi selesai.

### 6.1 Kapan Tindak Lanjut Sendiri

Gunakan tindak lanjut sendiri ketika:
- Anda langsung menangani perihal surat tanpa perlu melibatkan staf
- Disposisi sudah Anda teruskan, lalu Anda juga ingin mencatat catatan penutup
- Seluruh staf sudah menyelesaikan tugas dan Anda ingin menutup disposisi akar

### 6.2 Cara Mencatat Tindak Lanjut

1. Buka halaman Detail Surat Masuk
2. Di panel kanan, cari bagian **Tindak Lanjut**
3. Isi formulir:

| Field | Keterangan | Wajib |
|-------|------------|-------|
| **Catatan** | Uraian tindakan yang sudah dilakukan | Ya |
| **Lampiran** | File pendukung (PDF/JPG/PNG, maks 10 MB). Bisa lebih dari satu. | Tidak |
| **Tandai disposisi selesai** | Centang jika disposisi sudah tuntas | Tidak |

4. Klik **Simpan Tindak Lanjut**

Setelah disimpan:
- Tindak lanjut tercatat di **Timeline** surat
- Pengirim disposisi (Admin TU) menerima **notifikasi**
- Jika dicentang selesai, status disposisi berubah menjadi **Selesai**
- Jika **semua** disposisi pada surat tersebut berstatus Selesai, status surat otomatis berubah menjadi **Selesai**

### 6.3 Tindak Lanjut Tanpa Menyelesaikan

Anda boleh mencatat tindak lanjut **tanpa mencentang** "Tandai disposisi selesai". Ini berguna untuk:
- Memberikan catatan progres sementara ("Sudah dijadwalkan rapat koordinasi")
- Menambahkan lampiran pendukung sambil menunggu proses selesai

Anda tetap bisa mencatat tindak lanjut berikutnya dan menandai selesai nanti.

---

## 7. Surat Keluar

Menu: **Sidebar > Persuratan > Surat Keluar**

Sebagai Pimpinan, Anda dapat **melihat** seluruh surat keluar OPD namun **tidak dapat** membuat, mengedit, atau menerbitkan. Fitur tersebut merupakan tanggung jawab Admin TU.

### 7.1 Melihat Daftar Surat Keluar

| Kolom | Keterangan |
|-------|------------|
| **Nomor** | Nomor surat (kosong jika masih draft) |
| **Perihal** | Ringkasan isi surat |
| **Tujuan** | Nama instansi/OPD tujuan |
| **Tanggal** | Tanggal surat |
| **Sifat** | Biasa / Penting / Rahasia |
| **Status** | Draft / Terbit / Diarsip |

### 7.2 Melihat Detail Surat Keluar

Klik **Detail** untuk melihat informasi lengkap surat keluar, termasuk:
- Data surat (klasifikasi, tanggal, tujuan, nomor jika sudah terbit)
- Lampiran PDF
- Status tautan antar-OPD (jika surat internal)

### 7.3 Manfaat bagi Pimpinan

- Memantau volume surat keluar OPD
- Memeriksa apakah surat keluar sudah diterbitkan dan bernomor
- Melihat status surat internal yang dikirim ke OPD lain

---

## 8. Buku Agenda

Menu: **Sidebar > Persuratan > Agenda**

Buku Agenda menampilkan gabungan surat masuk dan surat keluar OPD dalam satu tabel kronologis.

### 8.1 Cara Menggunakan

1. Buka halaman Agenda
2. Isi **Dari** dan **Sampai** (periode wajib diisi)
3. Filter opsional: Jenis (Masuk/Keluar/Semua), Klasifikasi
4. Klik **Filter**

| Kolom | Keterangan |
|-------|------------|
| **No** | Nomor urut |
| **Jenis** | Masuk (biru) atau Keluar (hijau) |
| **Nomor** | Nomor surat |
| **Perihal** | Ringkasan isi |
| **Tanggal** | Tanggal terima/surat |
| **Asal/Tujuan** | Pengirim atau penerima |
| **Klasifikasi** | Kode arsip |

### 8.2 Cetak PDF

1. Pastikan filter Dari dan Sampai sudah terisi
2. Klik **Cetak PDF**
3. PDF terbuka di tab baru (orientasi landscape, berkop OPD)

---

## 9. Notifikasi

### 9.1 Jenis Notifikasi untuk Pimpinan

| Notifikasi | Kapan Muncul |
|------------|-------------|
| **Disposisi Baru** | Saat Admin TU atau Pimpinan lain mendisposisikan surat kepada Anda |
| **Tindak Lanjut Baru** | Saat Staf mencatat tindak lanjut atas disposisi yang Anda kirim |

### 9.2 Lonceng Notifikasi

| Elemen | Fungsi |
|--------|--------|
| Badge angka merah | Jumlah notifikasi belum dibaca |
| Dropdown (klik lonceng) | 10 notifikasi terbaru |
| Klik notifikasi | Tandai dibaca + buka halaman surat terkait |
| Lihat semua | Halaman daftar seluruh notifikasi |
| Tandai semua dibaca | Bersihkan badge sekaligus |

### 9.3 Notifikasi Telegram

Jika Telegram Chat ID sudah diisi di Profil, Anda menerima pesan instan untuk setiap notifikasi. Format pesan:

**Disposisi Baru:**
```
📨 Disposisi Baru
[Nama pengirim] mendisposisikan surat kepada Anda.
Instruksi: [isi instruksi]
```

**Tindak Lanjut Baru:**
```
✅ Tindak Lanjut Baru
[Nama staf] mencatat tindak lanjut.
Catatan: [isi catatan]
```

> **Tips:** Aktifkan notifikasi Telegram agar tidak ada disposisi yang terlewat, terutama jika Anda sering berada di luar kantor.

---

## 10. Profil & Keamanan Akun

Menu: **Klik nama Anda (pojok kanan atas) > Profil**

### 10.1 Informasi yang Ditampilkan

| Field | Keterangan | Dapat Diubah |
|-------|------------|-------------|
| **Nama** | Nama tampilan Anda di sistem | Ya |
| **Email** | Alamat email untuk login | Tidak (hubungi Admin TU) |
| **Telegram Chat ID** | ID untuk notifikasi Telegram | Ya |

### 10.2 Mengubah Password

1. Buka Profil
2. Isi **Password Saat Ini** (password lama)
3. Isi **Password Baru** (minimal 8 karakter)
4. Isi **Konfirmasi Password Baru**
5. Klik **Simpan**

> **Rekomendasi:**
> - Ubah password default segera setelah pertama kali login
> - Gunakan kombinasi huruf besar, huruf kecil, dan angka
> - Jangan gunakan password yang sama dengan akun lain
> - Ubah secara berkala (setiap 3 bulan)

### 10.3 Mengaktifkan Notifikasi Telegram

1. Buka Telegram, cari **@userinfobot**, kirim `/start` — catat angka **Id**
2. Cari bot e-Surat pemda Anda di Telegram, kirim `/start`
3. Di aplikasi e-Surat, buka **Profil**
4. Isi **Telegram Chat ID** dengan angka Id
5. Klik **Simpan**

---

## 11. Prosedur Operasional Standar

### 11.1 SOP: Rutinitas Harian Pimpinan

```
Pagi hari:
Langkah 1  →  Login ke e-Surat
Langkah 2  →  Periksa Dashboard — ada disposisi menunggu?
Langkah 3  →  Untuk setiap item berstatus "Terkirim" (merah):
              Klik → baca surat & lampiran → putuskan tindakan

Siang / sore:
Langkah 4  →  Periksa Dashboard lagi — ada disposisi baru?
Langkah 5  →  Periksa notifikasi — ada tindak lanjut dari Staf?
Langkah 6  →  Jika tindak lanjut sudah sesuai, pastikan disposisi
              ditandai selesai oleh Staf
```

### 11.2 SOP: Menerima dan Meneruskan Disposisi

```
Langkah 1  →  Buka disposisi dari Dashboard atau notifikasi
Langkah 2  →  Baca isi surat dan lampiran secara saksama
Langkah 3  →  Tentukan siapa yang menindaklanjuti:
              - Satu staf? → Teruskan ke staf tersebut
              - Beberapa staf? → Teruskan satu per satu
              - Anda sendiri? → Langsung catat tindak lanjut
Langkah 4  →  Isi instruksi yang jelas dan spesifik
              Contoh BAIK: "Siapkan surat balasan undangan, koordinasi
              dengan Kabid Perencanaan, selesaikan sebelum 10 Juni"
              Contoh KURANG: "Tolong ditindaklanjuti"
Langkah 5  →  Isi batas waktu jika ada tenggat
Langkah 6  →  Kirim disposisi
```

### 11.3 SOP: Menutup Disposisi yang Sudah Selesai

```
Langkah 1  →  Terima notifikasi tindak lanjut dari Staf
Langkah 2  →  Buka halaman Detail surat
Langkah 3  →  Baca tindak lanjut di Timeline — apakah sudah tuntas?
Langkah 4a →  Jika sudah tuntas dan disposisi Staf sudah "Selesai":
              Catat tindak lanjut Anda sendiri (misal: "Selesai, sudah
              dikoordinasikan") + centang "Tandai disposisi selesai"
Langkah 4b →  Jika belum tuntas:
              Tidak perlu tindakan — disposisi tetap aktif
```

### 11.4 SOP: Memantau Surat Rahasia

```
Langkah 1  →  Buka Surat Masuk, filter Sifat = "Rahasia"
Langkah 2  →  Periksa setiap surat — sudah didisposisikan?
Langkah 3  →  Pastikan disposisi hanya diteruskan ke staf
              yang relevan (surat rahasia hanya terlihat oleh
              Admin TU, Pimpinan, dan staf dalam rantai disposisi)
```

### 11.5 SOP: Meninjau Kinerja Tindak Lanjut

```
Langkah 1  →  Buka Surat Masuk, filter Status = "Didisposisi"
Langkah 2  →  Untuk setiap surat, buka Detail dan periksa Timeline:
              - Apakah ada disposisi yang sudah melewati batas waktu?
              - Apakah ada staf yang belum merespons?
Langkah 3  →  Jika ada yang terlambat, koordinasikan secara langsung
              dengan staf terkait
```

---

## 12. Troubleshooting

### Masalah Umum dan Solusinya

| No | Masalah | Penyebab | Solusi |
|----|---------|----------|--------|
| 1 | Dashboard kosong (tidak ada disposisi) | Belum ada disposisi yang ditujukan ke Anda, atau semua sudah selesai | Normal — periksa kembali nanti atau tanyakan ke Admin TU |
| 2 | Form "Disposisikan" tidak muncul di detail surat | Anda tidak memiliki disposisi aktif untuk surat tersebut | Form hanya muncul jika Admin TU sudah mendisposisikan ke Anda dan disposisi belum selesai |
| 3 | Form "Tindak Lanjut" tidak muncul | Disposisi Anda sudah berstatus Selesai, atau surat sudah diarsipkan | Hubungi Admin TU jika perlu dibuka kembali |
| 4 | Tidak bisa melihat surat tertentu | Filter aktif yang menyembunyikan data | Klik **Reset** pada panel filter |
| 5 | Staf tidak muncul di dropdown disposisi | Staf belum terdaftar atau akun nonaktif | Hubungi Admin TU untuk menambah/mengaktifkan akun staf |
| 6 | Notifikasi Telegram tidak masuk | Chat ID belum diisi atau bot belum dikonfigurasi | Isi Chat ID di Profil; hubungi Admin TU/tim teknis |
| 7 | Surat rahasia tidak terlihat oleh staf yang saya disposisi | Normal — staf hanya melihat surat rahasia jika berada dalam rantai disposisi | Pastikan Anda meneruskan disposisi ke staf tersebut terlebih dahulu |
| 8 | Tombol "Catat Surat Masuk" tidak ada | Anda bukan Admin TU | Fungsi pencatatan surat hanya untuk Admin TU. Minta Admin TU mencatat surat. |
| 9 | Tidak bisa mengedit atau menghapus surat | Anda bukan Admin TU | Fungsi edit/hapus hanya untuk Admin TU |
| 10 | Lupa password | — | Hubungi Admin TU OPD Anda untuk reset password |

### Eskalasi

| Kebutuhan | Hubungi |
|-----------|---------|
| Reset password | Admin TU OPD Anda |
| Surat belum dicatat di sistem | Admin TU OPD Anda |
| Akun staf baru / nonaktifkan | Admin TU OPD Anda |
| Klasifikasi surat baru | Superadmin (via Admin TU) |
| Masalah teknis / error aplikasi | Tim teknis (via Admin TU) |

---

*Panduan ini disusun untuk pengguna dengan role Pimpinan pada aplikasi e-Surat Pemda (SUMAKEL) versi 1.0.*
