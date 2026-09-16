# Panduan Operasional — Role Staf OPD

**Aplikasi e-Surat Pemda (SUMAKEL)**
Versi 1.0

---

## Daftar Isi

1. [Pendahuluan](#1-pendahuluan)
2. [Login & Navigasi](#2-login--navigasi)
3. [Dashboard Staf](#3-dashboard-staf)
4. [Surat Masuk](#4-surat-masuk)
5. [Menerima Disposisi](#5-menerima-disposisi)
6. [Mencatat Tindak Lanjut](#6-mencatat-tindak-lanjut)
7. [Meneruskan Disposisi](#7-meneruskan-disposisi)
8. [Surat Keluar](#8-surat-keluar)
9. [Buku Agenda](#9-buku-agenda)
10. [Notifikasi](#10-notifikasi)
11. [Profil & Keamanan Akun](#11-profil--keamanan-akun)
12. [Prosedur Operasional Standar](#12-prosedur-operasional-standar)
13. [Troubleshooting](#13-troubleshooting)

---

## 1. Pendahuluan

### 1.1 Tentang Role Staf

Staf adalah role pelaksana di OPD. Anda menerima disposisi (instruksi) dari Pimpinan, menindaklanjuti tugas yang diberikan, dan melaporkan hasilnya kembali melalui sistem.

### 1.2 Tanggung Jawab Utama

| No | Tanggung Jawab | Frekuensi |
|----|----------------|-----------|
| 1 | Memeriksa disposisi masuk dari Pimpinan | Harian |
| 2 | Menindaklanjuti instruksi disposisi | Sesuai tugas |
| 3 | Mencatat hasil tindak lanjut ke dalam sistem | Setelah tugas selesai |
| 4 | Melampirkan dokumen pendukung (jika ada) | Sesuai kebutuhan |
| 5 | Menandai disposisi sebagai selesai | Setelah tugas tuntas |

### 1.3 Ringkasan Hak Akses

| Fitur | Hak Staf |
|-------|----------|
| Surat Masuk | Lihat surat bersifat Biasa dan Penting; surat Rahasia hanya jika Anda berada dalam rantai disposisi |
| Disposisi | Menerima dari Pimpinan/rekan; meneruskan ke rekan se-OPD |
| Tindak Lanjut | Mencatat catatan + lampiran, menandai disposisi selesai |
| Surat Keluar | Lihat seluruh surat keluar OPD |
| Agenda | Lihat dan cetak buku agenda PDF |
| Lampiran | Mengunduh lampiran surat yang boleh Anda akses |
| Dashboard | Daftar tugas disposisi aktif + batas waktu |

### 1.4 Batasan

- **Tidak dapat** mencatat surat masuk baru (tugas Admin TU)
- **Tidak dapat** membuat atau menerbitkan surat keluar (tugas Admin TU)
- **Tidak dapat** mengedit, menghapus, atau mengarsipkan surat
- **Tidak dapat** mengelola pengguna, OPD, atau klasifikasi
- **Tidak dapat** melihat surat bersifat rahasia kecuali Anda berada dalam rantai disposisi surat tersebut
- Hanya melihat data **OPD sendiri**

---

## 2. Login & Navigasi

### 2.1 Login

1. Buka alamat aplikasi di browser (contoh: `https://esurat.pemda.go.id`)
2. Masukkan **Email** dan **Password** yang diberikan oleh Admin TU
3. Klik **Login**
4. Anda diarahkan ke Dashboard Staf

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

### 2.3 Navbar Atas

| Elemen | Fungsi |
|--------|--------|
| Logo **e-Surat** | Kembali ke Dashboard |
| Lonceng | Notifikasi — disposisi baru dari Pimpinan |
| Nama + Role + OPD | Identitas Anda; klik untuk membuka Profil atau Keluar |

### 2.4 Logout

Klik nama Anda di pojok kanan atas → **Keluar**.

---

## 3. Dashboard Staf

Dashboard Staf dirancang agar Anda langsung melihat **tugas yang harus dikerjakan**.

### 3.1 Daftar Tugas Disposisi

Menampilkan hingga 10 disposisi aktif yang ditujukan kepada Anda, diurutkan berdasarkan **batas waktu terdekat**.

Setiap item menampilkan:

| Informasi | Keterangan |
|-----------|------------|
| **Perihal surat** | Judul/perihal surat masuk terkait |
| **Instruksi** | Instruksi dari Pimpinan yang mendisposisikan |
| **Badge status** | Merah = Terkirim (belum Anda buka), Biru = Dibaca / Diproses |
| **Batas waktu** | Tanggal tenggat — **merah** jika sudah lewat, abu-abu jika masih berlaku |

**Klik item** untuk membuka halaman Detail Surat Masuk dan menindaklanjuti.

### 3.2 Menggunakan Dashboard Secara Efektif

1. **Periksa Dashboard setiap pagi** — ini adalah "to-do list" Anda
2. Prioritaskan tugas dengan batas waktu **merah** (sudah lewat) terlebih dahulu
3. Setelah itu tangani tugas berstatus **Terkirim** (belum Anda buka)
4. Klik item untuk langsung masuk ke halaman surat dan menindaklanjuti

> **Tips:** Jika Dashboard kosong, berarti tidak ada tugas disposisi aktif. Anda tetap bisa membuka Surat Masuk dan Surat Keluar untuk melihat data persuratan OPD.

---

## 4. Surat Masuk

Menu: **Sidebar > Persuratan > Surat Masuk**

### 4.1 Melihat Daftar Surat Masuk

Anda dapat melihat surat masuk OPD dengan ketentuan:

| Sifat Surat | Akses Staf |
|-------------|------------|
| **Biasa** | Selalu terlihat |
| **Penting** | Selalu terlihat |
| **Rahasia** | Hanya terlihat jika Anda berada dalam rantai disposisi surat tersebut |

Kolom yang ditampilkan:

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

Klik **Filter** untuk menerapkan. Klik **Reset** untuk menghapus semua filter.

### 4.3 Melihat Detail Surat

Klik **Detail** pada surat. Halaman detail menampilkan:

**Kolom kiri:**
- **Informasi surat** — nomor, asal, klasifikasi, tanggal, perihal, sifat, status
- **Lampiran** — daftar file yang bisa diunduh (klik untuk download)
- **Timeline** — riwayat kronologis seluruh disposisi dan tindak lanjut

**Kolom kanan** (jika Anda memiliki disposisi aktif):
- **Form Disposisi** — untuk meneruskan ke rekan (opsional)
- **Form Tindak Lanjut** — untuk mencatat hasil kerja dan menyelesaikan tugas

### 4.4 Mengunduh Lampiran

Klik nama file di bagian **Lampiran** atau di dalam tindak lanjut — file langsung terunduh.

> **Catatan:** Anda hanya bisa mengunduh lampiran dari surat yang boleh Anda akses. Untuk surat rahasia, akses lampiran hanya tersedia jika Anda berada dalam rantai disposisi.

---

## 5. Menerima Disposisi

### 5.1 Bagaimana Disposisi Sampai ke Anda

```
Admin TU → Pimpinan → ANDA (Staf)
```

Alur umum:
1. Admin TU mencatat surat masuk dan mendisposisikan ke Pimpinan
2. Pimpinan membaca surat, lalu **meneruskan disposisi ke Anda** dengan instruksi spesifik
3. Anda menerima **notifikasi** (lonceng + Telegram jika aktif)
4. Disposisi muncul di **Dashboard** dan di halaman **Detail Surat**

### 5.2 Saat Menerima Disposisi

Ketika Anda menerima disposisi baru:

1. **Badge lonceng** di navbar bertambah
2. Item baru muncul di **Dashboard** dengan status **Terkirim** (badge merah)
3. Jika Telegram aktif, Anda menerima pesan:
   ```
   📨 Disposisi Baru
   [Nama Pimpinan] mendisposisikan surat kepada Anda.
   Instruksi: [isi instruksi]
   ```

### 5.3 Membuka Disposisi

Klik item di Dashboard atau klik notifikasi. Sistem akan:
- Membuka halaman Detail Surat Masuk
- Mengubah status disposisi dari **Terkirim** menjadi **Dibaca** secara otomatis

### 5.4 Memahami Informasi Disposisi

Di halaman Detail Surat, baca dengan teliti:

| Informasi | Di Mana |
|-----------|---------|
| Isi surat lengkap | Tabel informasi surat di kolom kiri |
| Dokumen asli | Bagian Lampiran (klik untuk unduh) |
| Siapa yang mendisposisi | Timeline — nama pengirim |
| Instruksi untuk Anda | Timeline — teks instruksi |
| Batas waktu | Timeline — tanggal merah (jika diisi) |
| Riwayat sebelumnya | Timeline — disposisi dan tindak lanjut sebelumnya |

---

## 6. Mencatat Tindak Lanjut

Ini adalah **fungsi utama** Anda dalam sistem — melaporkan hasil pekerjaan atas disposisi yang diterima.

### 6.1 Cara Mencatat Tindak Lanjut

1. Buka halaman Detail Surat Masuk (dari Dashboard atau daftar Surat Masuk)
2. Di panel kanan, cari bagian **Tindak Lanjut**
3. Isi formulir:

| Field | Keterangan | Wajib |
|-------|------------|-------|
| **Catatan** | Uraian tindakan yang sudah Anda lakukan. Tulis dengan jelas dan lengkap. | Ya |
| **Lampiran** | File pendukung — surat balasan, notulen, foto, dsb. Format: PDF/JPG/PNG, maks 10 MB per file. Bisa lebih dari satu. | Tidak |
| **Tandai disposisi selesai** | Centang jika tugas sudah **benar-benar tuntas** | Tidak |

4. Klik **Simpan Tindak Lanjut**

### 6.2 Apa yang Terjadi Setelah Disimpan

- Tindak lanjut tercatat di **Timeline** surat (terlihat oleh semua pihak terkait)
- Pimpinan yang mendisposisi menerima **notifikasi** (in-app + Telegram)
- Jika Anda mencentang "Tandai disposisi selesai":
  - Status disposisi Anda berubah menjadi **Selesai**
  - Jika **semua** disposisi pada surat tersebut sudah Selesai, status surat otomatis berubah menjadi **Selesai**

### 6.3 Tindak Lanjut Bertahap (Tanpa Menandai Selesai)

Anda **tidak wajib langsung menandai selesai**. Untuk tugas yang memerlukan beberapa tahap:

**Tahap 1 — Progres awal:**
```
Catatan : "Sudah menghubungi pihak terkait, menunggu jadwal rapat"
Selesai : ☐ (tidak dicentang)
```

**Tahap 2 — Progres lanjutan:**
```
Catatan  : "Rapat koordinasi telah dilaksanakan pada 5 Juni 2026"
Lampiran : notulen_rapat_050626.pdf
Selesai  : ☐ (tidak dicentang)
```

**Tahap 3 — Penyelesaian:**
```
Catatan  : "Surat balasan sudah disiapkan dan diserahkan ke Admin TU"
Lampiran : surat_balasan_draft.pdf
Selesai  : ☑ (dicentang)
```

> **Tips:** Mencatat progres secara bertahap memberikan visibilitas kepada Pimpinan tanpa perlu ditanyakan langsung.

### 6.4 Kapan Form Tindak Lanjut Muncul

Form hanya muncul jika **semua syarat** terpenuhi:
- Anda memiliki disposisi aktif (Terkirim / Dibaca / Diproses) untuk surat tersebut
- Surat belum diarsipkan

Jika form tidak muncul:
- Disposisi Anda sudah ditandai Selesai → tidak bisa mencatat lagi
- Surat sudah diarsipkan oleh Admin TU

### 6.5 Panduan Menulis Catatan Tindak Lanjut

| Baik | Kurang Baik |
|------|-------------|
| "Surat undangan telah disampaikan ke Kabid Perencanaan pada 3 Juni 2026. Konfirmasi kehadiran sudah dikirim via email." | "Sudah" |
| "Rapat koordinasi dilaksanakan 5 Juni 2026. Peserta: Kabid, Kasubbag, 3 staf. Hasil: disepakati jadwal pelaksanaan minggu ke-2 Juni. Notulen terlampir." | "Done" |
| "Belum bisa ditindaklanjuti — surat asli belum diterima dari pengirim. Sudah dikonfirmasi ke Admin TU." | "Pending" |

---

## 7. Meneruskan Disposisi

Selain mencatat tindak lanjut, Anda juga dapat **meneruskan disposisi ke rekan se-OPD**.

### 7.1 Kapan Meneruskan

- Tugas memerlukan bantuan atau koordinasi dengan rekan lain
- Anda diminta Pimpinan untuk mendelegasikan sebagian tugas

### 7.2 Cara Meneruskan

1. Buka halaman Detail Surat Masuk
2. Di panel kanan, cari bagian **Disposisikan**
3. Isi:

| Field | Keterangan | Wajib |
|-------|------------|-------|
| **Kepada** | Pilih rekan se-OPD dari dropdown | Ya |
| **Instruksi** | Arahan untuk penerima | Ya |
| **Batas Waktu** | Tanggal tenggat (opsional) | Tidak |

4. Klik **Kirim Disposisi**

Setelah dikirim:
- Status disposisi Anda berubah menjadi **Diproses**
- Penerima mendapat notifikasi
- Disposisi anak tercatat di Timeline

> **Catatan:** Meneruskan disposisi **tidak otomatis menyelesaikan** tugas Anda. Setelah penerima selesai, Anda tetap perlu mencatat tindak lanjut dan menandai disposisi Anda sebagai selesai.

---

## 8. Surat Keluar

Menu: **Sidebar > Persuratan > Surat Keluar**

Sebagai Staf, Anda dapat **melihat** seluruh surat keluar OPD namun **tidak dapat** membuat, mengedit, atau menerbitkan.

### 8.1 Melihat Daftar

| Kolom | Keterangan |
|-------|------------|
| **Nomor** | Nomor surat (kosong jika masih draft) |
| **Perihal** | Ringkasan isi surat |
| **Tujuan** | Nama instansi/OPD tujuan |
| **Tanggal** | Tanggal surat |
| **Sifat** | Biasa / Penting / Rahasia |
| **Status** | Draft / Terbit / Diarsip |

### 8.2 Melihat Detail

Klik **Detail** untuk melihat informasi lengkap dan mengunduh lampiran surat keluar.

---

## 9. Buku Agenda

Menu: **Sidebar > Persuratan > Agenda**

### 9.1 Cara Menggunakan

1. Buka halaman Agenda
2. Isi **Dari** dan **Sampai** (wajib)
3. Filter opsional: Jenis (Masuk/Keluar/Semua), Klasifikasi
4. Klik **Filter**

### 9.2 Cetak PDF

Setelah filter diterapkan, klik **Cetak PDF** — file PDF landscape dengan kop OPD terbuka di tab baru.

---

## 10. Notifikasi

### 10.1 Jenis Notifikasi untuk Staf

| Notifikasi | Kapan Muncul |
|------------|-------------|
| **Disposisi Baru** | Saat Pimpinan atau rekan mendisposisikan surat kepada Anda |
| **Tindak Lanjut Baru** | Saat rekan yang Anda disposisi mencatat tindak lanjut (jika Anda meneruskan disposisi) |

### 10.2 Lonceng Notifikasi

1. Klik ikon **lonceng** di navbar
2. Badge angka merah = jumlah notifikasi belum dibaca
3. Dropdown menampilkan 10 notifikasi terbaru:
   - **Judul** — jenis notifikasi
   - **Pesan** — ringkasan isi
   - **Waktu** — berapa lama yang lalu
4. Klik notifikasi untuk menandai dibaca + langsung ke halaman surat
5. Klik **Lihat semua** untuk halaman daftar lengkap
6. Klik **Tandai semua dibaca** untuk membersihkan badge

### 10.3 Notifikasi Telegram

Jika Telegram Chat ID sudah diisi di Profil, Anda menerima pesan instan:

```
📨 Disposisi Baru
[Nama Pimpinan] mendisposisikan surat kepada Anda.
Instruksi: [isi instruksi]
```

> **Sangat disarankan** untuk mengaktifkan notifikasi Telegram agar tidak ada disposisi yang terlewat, terutama jika Anda sering bertugas di lapangan.

---

## 11. Profil & Keamanan Akun

Menu: **Klik nama Anda (pojok kanan atas) > Profil**

### 11.1 Informasi Profil

| Field | Keterangan | Dapat Diubah |
|-------|------------|-------------|
| **Nama** | Nama tampilan di sistem | Ya |
| **Email** | Digunakan untuk login | Tidak (hubungi Admin TU) |
| **Telegram Chat ID** | Untuk notifikasi Telegram | Ya |

### 11.2 Mengubah Password

1. Buka Profil
2. Isi **Password Saat Ini**
3. Isi **Password Baru** (minimal 8 karakter)
4. Isi **Konfirmasi Password Baru**
5. Klik **Simpan**

> **Penting:**
> - Segera ubah password default saat pertama kali login
> - Gunakan kombinasi huruf besar, huruf kecil, dan angka
> - Jangan beritahukan password Anda ke orang lain
> - Ubah secara berkala (setiap 3 bulan)

### 11.3 Mengaktifkan Notifikasi Telegram

1. Buka Telegram, cari **@userinfobot**, kirim `/start` — catat angka **Id**
2. Cari bot e-Surat pemda Anda di Telegram, kirim `/start`
3. Di aplikasi e-Surat, buka **Profil**
4. Isi **Telegram Chat ID** dengan angka Id
5. Klik **Simpan**

---

## 12. Prosedur Operasional Standar

### 12.1 SOP: Rutinitas Harian Staf

```
Pagi hari:
Langkah 1  →  Login ke e-Surat
Langkah 2  →  Periksa Dashboard:
              - Ada tugas berstatus "Terkirim" (merah)? → Segera buka
              - Ada tugas dengan batas waktu MERAH (lewat)? → Prioritaskan
Langkah 3  →  Untuk setiap tugas:
              Klik → baca surat & instruksi → mulai kerjakan

Sepanjang hari:
Langkah 4  →  Setiap kali ada progres, catat tindak lanjut ke sistem
Langkah 5  →  Periksa notifikasi — ada disposisi baru?

Akhir hari:
Langkah 6  →  Periksa Dashboard lagi — pastikan tidak ada tugas terlewat
```

### 12.2 SOP: Menerima dan Menyelesaikan Disposisi

```
Langkah 1  →  Terima notifikasi disposisi baru
Langkah 2  →  Buka dari Dashboard atau notifikasi
Langkah 3  →  Baca isi surat dan lampiran
Langkah 4  →  Baca instruksi dari Pimpinan dengan teliti
Langkah 5  →  Kerjakan tugas sesuai instruksi
Langkah 6  →  Setelah selesai, buka halaman Detail surat
Langkah 7  →  Di panel "Tindak Lanjut", isi:
              - Catatan: uraikan apa yang sudah dilakukan
              - Lampiran: sertakan dokumen pendukung (jika ada)
              - Centang: "Tandai disposisi selesai"
Langkah 8  →  Klik "Simpan Tindak Lanjut"
Langkah 9  →  Pimpinan otomatis menerima notifikasi
```

### 12.3 SOP: Tugas yang Memerlukan Waktu Lama

```
Hari ke-1 (terima tugas):
Langkah 1  →  Buka disposisi, baca instruksi
Langkah 2  →  Catat tindak lanjut awal:
              "Disposisi diterima. Mulai dikerjakan, estimasi selesai [tanggal]."
              Selesai: ☐ (jangan centang)

Hari ke-N (ada progres):
Langkah 3  →  Catat tindak lanjut progres:
              "Progres: [uraian]. Lampiran: [jika ada]."
              Selesai: ☐ (jangan centang)

Hari selesai:
Langkah 4  →  Catat tindak lanjut final:
              "Tugas selesai. [uraian hasil]. Dokumen terlampir."
              Lampiran: [file hasil]
              Selesai: ☑ (centang)
```

### 12.4 SOP: Tidak Bisa Menindaklanjuti (Ada Kendala)

```
Langkah 1  →  Catat tindak lanjut dengan penjelasan kendala:
              "Belum dapat ditindaklanjuti karena [alasan].
               Memerlukan [apa yang dibutuhkan]."
              Selesai: ☐ (jangan centang)
Langkah 2  →  Pimpinan menerima notifikasi dan dapat menentukan
              langkah selanjutnya
Langkah 3  →  Setelah kendala teratasi, catat tindak lanjut
              berikutnya sampai tugas tuntas
```

### 12.5 SOP: Diminta Meneruskan Tugas ke Rekan

```
Langkah 1  →  Buka halaman Detail surat
Langkah 2  →  Di panel "Disposisikan", pilih rekan, tulis instruksi
Langkah 3  →  Kirim Disposisi
Langkah 4  →  Status disposisi Anda berubah menjadi "Diproses"
Langkah 5  →  Pantau: rekan Anda akan mencatat tindak lanjut
Langkah 6  →  Setelah rekan selesai, catat tindak lanjut Anda sendiri:
              "Tugas telah didelegasikan ke [nama] dan selesai dikerjakan."
              Selesai: ☑ (centang)
```

---

## 13. Troubleshooting

### Masalah Umum dan Solusinya

| No | Masalah | Penyebab | Solusi |
|----|---------|----------|--------|
| 1 | Dashboard kosong | Tidak ada disposisi aktif untuk Anda | Normal — periksa kembali nanti |
| 2 | Surat tertentu tidak terlihat di daftar | Surat bersifat rahasia dan Anda bukan bagian rantai disposisi | Normal — Anda hanya melihat surat rahasia jika didisposisikan ke Anda |
| 3 | Form "Tindak Lanjut" tidak muncul | Disposisi sudah selesai atau surat sudah diarsipkan | Hubungi Pimpinan atau Admin TU |
| 4 | Form "Disposisikan" tidak muncul | Anda tidak memiliki disposisi aktif untuk surat tersebut | Form muncul hanya jika ada disposisi aktif yang ditujukan ke Anda |
| 5 | Upload lampiran gagal | File > 10 MB atau format bukan PDF/JPG/PNG | Kompres file atau ubah format |
| 6 | Batas waktu sudah lewat tapi belum selesai | Tugas belum dikerjakan tepat waktu | Segera kerjakan dan catat tindak lanjut; komunikasikan kendala ke Pimpinan |
| 7 | Notifikasi Telegram tidak masuk | Chat ID belum diisi atau bot belum aktif | Isi Chat ID di Profil; tanyakan ke Admin TU |
| 8 | Tidak bisa mencatat surat masuk | Anda bukan Admin TU | Minta Admin TU untuk mencatat surat |
| 9 | Data surat tidak tampil | Filter aktif | Klik **Reset** pada panel filter |
| 10 | Lupa password | — | Hubungi Admin TU OPD Anda |

### Eskalasi

| Kebutuhan | Hubungi |
|-----------|---------|
| Reset password | Admin TU OPD Anda |
| Kendala menindaklanjuti disposisi | Pimpinan yang mendisposisikan |
| Akun tidak bisa login / nonaktif | Admin TU OPD Anda |
| Notifikasi Telegram bermasalah | Admin TU → tim teknis |
| Aplikasi error / tidak bisa diakses | Admin TU → tim teknis |

---

*Panduan ini disusun untuk pengguna dengan role Staf pada aplikasi e-Surat Pemda (SUMAKEL) versi 1.0.*
