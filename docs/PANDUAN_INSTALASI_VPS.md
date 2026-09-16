# Panduan Instalasi VPS — Ubuntu Server 22.04

**Aplikasi e-Surat Pemda (SUMAKEL)**
Versi 1.0

Panduan ini mencakup seluruh proses dari server kosong (fresh install Ubuntu 22.04) hingga aplikasi SUMAKEL siap diakses oleh pengguna, termasuk integrasi notifikasi Telegram.

---

## Daftar Isi

1. [Kebutuhan & Persiapan](#1-kebutuhan--persiapan)
2. [Update Sistem & Konfigurasi Dasar](#2-update-sistem--konfigurasi-dasar)
3. [Instalasi PHP 8.3](#3-instalasi-php-83)
4. [Instalasi MySQL 8](#4-instalasi-mysql-8)
5. [Instalasi Nginx](#5-instalasi-nginx)
6. [Instalasi Composer](#6-instalasi-composer)
7. [Instalasi Node.js & npm](#7-instalasi-nodejs--npm)
8. [Deploy Aplikasi SUMAKEL](#8-deploy-aplikasi-sumakel)
9. [Konfigurasi Nginx Virtual Host](#9-konfigurasi-nginx-virtual-host)
10. [SSL/HTTPS dengan Let's Encrypt](#10-sslhttps-dengan-lets-encrypt)
11. [Konfigurasi Queue Worker (Systemd)](#11-konfigurasi-queue-worker-systemd)
12. [Integrasi Telegram Bot](#12-integrasi-telegram-bot)
13. [Firewall & Keamanan](#13-firewall--keamanan)
14. [Verifikasi Akhir](#14-verifikasi-akhir)
15. [Pemeliharaan & Backup](#15-pemeliharaan--backup)
16. [Update Aplikasi](#16-update-aplikasi)
17. [Troubleshooting](#17-troubleshooting)

---

## 1. Kebutuhan & Persiapan

### 1.1 Spesifikasi Server Minimum

| Komponen | Minimum | Rekomendasi |
|----------|---------|-------------|
| CPU | 1 vCPU | 2 vCPU |
| RAM | 2 GB | 4 GB |
| Storage | 20 GB SSD | 40 GB SSD |
| OS | Ubuntu Server 22.04 LTS | Ubuntu Server 22.04 LTS |
| Bandwidth | 1 TB/bulan | Unlimited |

### 1.2 Persiapan Sebelum Mulai

- [ ] VPS sudah aktif dengan Ubuntu 22.04 terinstall
- [ ] Akses SSH sebagai root atau user dengan sudo
- [ ] Domain sudah disiapkan (contoh: `esurat.pemda.go.id`)
- [ ] DNS A record sudah mengarah ke IP VPS
- [ ] Source code SUMAKEL tersedia (Git repository atau arsip ZIP)

### 1.3 Konvensi Panduan Ini

```
$  = perintah dijalankan sebagai user biasa (sudo jika perlu)
#  = perintah dijalankan sebagai root

Ganti variabel berikut sesuai konfigurasi Anda:
  DOMAIN       = esurat.pemda.go.id   (domain aplikasi)
  DB_NAME      = sumakel              (nama database)
  DB_USER      = sumakel              (user database)
  DB_PASS      = GantiDenganPasswordKuat  (password database)
```

---

## 2. Update Sistem & Konfigurasi Dasar

### 2.1 Login SSH & Update

```bash
ssh root@IP_SERVER

apt update && apt upgrade -y
```

### 2.2 Buat User Deploy (Jangan Jalankan Aplikasi sebagai Root)

```bash
adduser deploy
usermod -aG sudo deploy
```

### 2.3 Atur Timezone

```bash
timedatectl set-timezone Asia/Makassar
```

> Sesuaikan zona waktu dengan lokasi pemda Anda. Pilihan umum:
> - `Asia/Jakarta` (WIB)
> - `Asia/Makassar` (WITA)
> - `Asia/Jayapura` (WIT)

### 2.4 Install Utilitas Dasar

```bash
apt install -y curl wget git unzip software-properties-common
```

---

## 3. Instalasi PHP 8.3

### 3.1 Tambah Repository PHP

```bash
add-apt-repository ppa:ondrej/php -y
apt update
```

### 3.2 Install PHP 8.3 & Ekstensi

```bash
apt install -y php8.3-fpm php8.3-cli php8.3-mysql php8.3-mbstring \
  php8.3-xml php8.3-bcmath php8.3-curl php8.3-zip php8.3-gd \
  php8.3-intl php8.3-readline php8.3-dom
```

### 3.3 Verifikasi

```bash
php -v
# Output: PHP 8.3.x ...

php -m | grep -i "pdo_mysql\|mbstring\|xml\|curl\|gd\|zip\|bcmath\|intl"
# Pastikan semua ekstensi muncul
```

### 3.4 Konfigurasi PHP-FPM

```bash
nano /etc/php/8.3/fpm/php.ini
```

Ubah nilai berikut:

```ini
upload_max_filesize = 20M
post_max_size = 25M
memory_limit = 256M
max_execution_time = 60
```

Restart PHP-FPM:

```bash
systemctl restart php8.3-fpm
```

---

## 4. Instalasi MySQL 8

### 4.1 Install

```bash
apt install -y mysql-server
```

### 4.2 Amankan Instalasi

```bash
mysql_secure_installation
```

Jawab pertanyaan:
- VALIDATE PASSWORD component → **Y**, pilih level **MEDIUM**
- Set root password → masukkan password root MySQL
- Remove anonymous users → **Y**
- Disallow root login remotely → **Y**
- Remove test database → **Y**
- Reload privilege tables → **Y**

### 4.3 Buat Database & User Aplikasi

```bash
mysql -u root -p
```

```sql
CREATE DATABASE sumakel CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'sumakel'@'localhost' IDENTIFIED BY 'GantiDenganPasswordKuat';
GRANT ALL PRIVILEGES ON sumakel.* TO 'sumakel'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

> **Penting:** Ganti `GantiDenganPasswordKuat` dengan password yang kuat dan unik. Simpan password ini — akan digunakan di file `.env`.

### 4.4 Verifikasi

```bash
mysql -u sumakel -p -e "SHOW DATABASES;"
# Pastikan 'sumakel' muncul di daftar
```

---

## 5. Instalasi Nginx

### 5.1 Install

```bash
apt install -y nginx
```

### 5.2 Verifikasi

```bash
systemctl status nginx
# Active: active (running)

curl -I http://localhost
# HTTP/1.1 200 OK
```

---

## 6. Instalasi Composer

```bash
cd /tmp
curl -sS https://getcomposer.org/installer | php
mv composer.phar /usr/local/bin/composer
chmod +x /usr/local/bin/composer
```

Verifikasi:

```bash
composer --version
# Composer version 2.x.x
```

---

## 7. Instalasi Node.js & npm

### 7.1 Install via NodeSource (Node.js 20 LTS)

```bash
curl -fsSL https://deb.nodesource.com/setup_20.x | bash -
apt install -y nodejs
```

### 7.2 Verifikasi

```bash
node -v
# v20.x.x

npm -v
# 10.x.x
```

---

## 8. Deploy Aplikasi SUMAKEL

### 8.1 Buat Direktori Aplikasi

```bash
mkdir -p /var/www/sumakel
chown deploy:deploy /var/www/sumakel
```

### 8.2 Upload Source Code

**Opsi A — Dari Git Repository:**

```bash
su - deploy
cd /var/www/sumakel
git clone REPO_URL .
```

**Opsi B — Dari Arsip ZIP:**

Upload file ZIP ke server, lalu:

```bash
su - deploy
cd /var/www/sumakel
unzip /tmp/sumakel.zip -d .
```

### 8.3 Install Dependensi PHP

```bash
cd /var/www/sumakel
composer install --no-dev --optimize-autoloader
```

### 8.4 Install Dependensi Node.js & Build Assets

```bash
npm ci
npm run build
```

> Setelah build selesai, folder `public/build/` akan berisi file CSS dan JS yang sudah dikompilasi. Node.js **tidak diperlukan** lagi saat runtime.

### 8.5 Konfigurasi Environment

```bash
cp .env.example .env
nano .env
```

Ubah konfigurasi berikut:

```env
APP_NAME="e-Surat Pemda"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://esurat.pemda.go.id

APP_LOCALE=id
APP_FALLBACK_LOCALE=id
APP_FAKER_LOCALE=id_ID

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=sumakel
DB_USERNAME=sumakel
DB_PASSWORD=GantiDenganPasswordKuat

QUEUE_CONNECTION=database

SESSION_DRIVER=database
CACHE_STORE=database

TELEGRAM_BOT_TOKEN=
```

> **Penting:**
> - `APP_DEBUG=false` di production — jangan pernah `true`
> - `APP_URL` harus sesuai domain dengan `https://`
> - `TELEGRAM_BOT_TOKEN` diisi nanti di Bab 12

### 8.6 Generate Application Key

```bash
php artisan key:generate
```

### 8.7 Jalankan Migrasi & Seeder

```bash
php artisan migrate --force
php artisan db:seed --force
```

> Seeder akan membuat data demo (3 OPD, 10 user, 5 klasifikasi). Untuk production tanpa data demo, jalankan hanya `RoleSeeder`:
>
> ```bash
> php artisan migrate --force
> php artisan db:seed --class=RoleSeeder --force
> ```
>
> Lalu buat Superadmin secara manual via Tinker:
>
> ```bash
> php artisan tinker
> ```
> ```php
> $user = \App\Models\User::create([
>     'name' => 'Super Admin',
>     'email' => 'admin@pemda.go.id',
>     'password' => bcrypt('PasswordKuatAnda'),
>     'is_aktif' => true,
> ]);
> $user->assignRole('superadmin');
> exit;
> ```

### 8.8 Optimasi Laravel untuk Production

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache
```

### 8.9 Atur Permissions

```bash
sudo chown -R deploy:www-data /var/www/sumakel
sudo chmod -R 755 /var/www/sumakel
sudo chmod -R 775 /var/www/sumakel/storage
sudo chmod -R 775 /var/www/sumakel/bootstrap/cache
```

### 8.10 Buat Symbolic Link Storage

```bash
php artisan storage:link
```

---

## 9. Konfigurasi Nginx Virtual Host

### 9.1 Buat File Konfigurasi

```bash
sudo nano /etc/nginx/sites-available/sumakel
```

Isi dengan:

```nginx
server {
    listen 80;
    listen [::]:80;

    server_name esurat.pemda.go.id;
    root /var/www/sumakel/public;

    index index.php;

    charset utf-8;

    client_max_body_size 25M;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.3-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

> Ganti `esurat.pemda.go.id` dengan domain Anda.

### 9.2 Aktifkan Site & Test

```bash
sudo ln -s /etc/nginx/sites-available/sumakel /etc/nginx/sites-enabled/
sudo rm -f /etc/nginx/sites-enabled/default
sudo nginx -t
sudo systemctl reload nginx
```

### 9.3 Verifikasi

Buka `http://esurat.pemda.go.id` di browser — halaman login harus muncul.

---

## 10. SSL/HTTPS dengan Let's Encrypt

### 10.1 Install Certbot

```bash
sudo apt install -y certbot python3-certbot-nginx
```

### 10.2 Generate Sertifikat SSL

```bash
sudo certbot --nginx -d esurat.pemda.go.id
```

Ikuti petunjuk:
- Masukkan email administrator
- Setuju Terms of Service
- Pilih redirect HTTP ke HTTPS (**Yes**)

### 10.3 Verifikasi Auto-Renewal

```bash
sudo certbot renew --dry-run
```

### 10.4 Verifikasi HTTPS

Buka `https://esurat.pemda.go.id` — gembok hijau harus muncul dan halaman login tampil.

---

## 11. Konfigurasi Queue Worker (Systemd)

Queue worker diperlukan untuk mengirim notifikasi Telegram secara asinkron.

### 11.1 Buat Service File

```bash
sudo nano /etc/systemd/system/sumakel-queue.service
```

Isi dengan:

```ini
[Unit]
Description=SUMAKEL Queue Worker
After=network.target mysql.service

[Service]
User=deploy
Group=www-data
Restart=always
RestartSec=5
WorkingDirectory=/var/www/sumakel
ExecStart=/usr/bin/php artisan queue:work database --sleep=3 --tries=3 --max-time=3600

# Logging
StandardOutput=append:/var/www/sumakel/storage/logs/queue-worker.log
StandardError=append:/var/www/sumakel/storage/logs/queue-worker.log

[Install]
WantedBy=multi-user.target
```

### 11.2 Aktifkan & Jalankan

```bash
sudo systemctl daemon-reload
sudo systemctl enable sumakel-queue
sudo systemctl start sumakel-queue
```

### 11.3 Verifikasi

```bash
sudo systemctl status sumakel-queue
# Active: active (running)
```

### 11.4 Restart Queue Setelah Deploy

Setiap kali ada update kode, restart queue worker:

```bash
sudo systemctl restart sumakel-queue
```

Atau gunakan perintah Laravel (graceful restart):

```bash
cd /var/www/sumakel
php artisan queue:restart
```

---

## 12. Integrasi Telegram Bot

### 12.1 Membuat Bot Telegram

1. Buka Telegram, cari **@BotFather**
2. Kirim `/newbot`
3. Ikuti petunjuk:
   - Masukkan **nama bot** (contoh: `e-Surat Pemda Kab XYZ`)
   - Masukkan **username bot** (contoh: `esurat_kabxyz_bot`) — harus unik dan diakhiri `bot`
4. BotFather akan memberikan **API Token**, contoh:
   ```
   7123456789:AAH1bGciOiJSUzI1NiIsInR5cCI6Ikp
   ```
5. **Simpan token ini** — akan dimasukkan ke konfigurasi server

### 12.2 Konfigurasi Token di Server

```bash
su - deploy
cd /var/www/sumakel
nano .env
```

Isi baris `TELEGRAM_BOT_TOKEN`:

```env
TELEGRAM_BOT_TOKEN=7123456789:AAH1bGciOiJSUzI1NiIsInR5cCI6Ikp
```

Refresh cache konfigurasi:

```bash
php artisan config:cache
```

Restart queue worker (agar membaca token baru):

```bash
sudo systemctl restart sumakel-queue
```

### 12.3 Verifikasi Bot Aktif

Test dari server bahwa bot bisa berkomunikasi:

```bash
curl -s "https://api.telegram.org/bot7123456789:AAH1bGciOiJSUzI1NiIsInR5cCI6Ikp/getMe"
```

Respons yang benar:

```json
{"ok":true,"result":{"id":7123456789,"is_bot":true,"first_name":"e-Surat Pemda","username":"esurat_kabxyz_bot"...}}
```

> Ganti token pada URL di atas dengan token bot Anda yang sebenarnya.

### 12.4 Mendapatkan Chat ID Pengguna

Setiap pengguna yang ingin menerima notifikasi Telegram harus mengetahui **Chat ID** mereka.

**Cara untuk pengguna:**

1. Buka Telegram, cari **@userinfobot**
2. Kirim `/start`
3. Bot membalas dengan informasi, termasuk **Id** (angka). Contoh: `123456789`
4. Cari bot e-Surat pemda (username yang dibuat di langkah 12.1), kirim `/start`
5. Login ke aplikasi e-Surat → buka **Profil** → isi **Telegram Chat ID** dengan angka Id → **Simpan**

### 12.5 Test Pengiriman Notifikasi

Untuk memastikan integrasi berfungsi end-to-end:

1. Pastikan ada user dengan **Telegram Chat ID** terisi
2. Pastikan queue worker berjalan (`sudo systemctl status sumakel-queue`)
3. Login sebagai Admin TU → catat surat masuk → disposisikan ke Pimpinan yang memiliki Chat ID
4. Pimpinan harus menerima:
   - Notifikasi in-app (lonceng di navbar)
   - Pesan Telegram dari bot

Jika pesan Telegram tidak terkirim, periksa log:

```bash
tail -50 /var/www/sumakel/storage/logs/laravel.log | grep -i telegram
tail -50 /var/www/sumakel/storage/logs/queue-worker.log
```

### 12.6 Kustomisasi Bot (Opsional)

Kembali ke **@BotFather** di Telegram:

| Perintah | Fungsi |
|----------|--------|
| `/setdescription` | Mengatur deskripsi bot |
| `/setabouttext` | Mengatur teks "About" |
| `/setuserpic` | Mengatur foto profil bot (upload logo pemda) |

---

## 13. Firewall & Keamanan

### 13.1 Konfigurasi UFW

```bash
sudo ufw default deny incoming
sudo ufw default allow outgoing
sudo ufw allow ssh
sudo ufw allow 'Nginx Full'
sudo ufw enable
```

### 13.2 Verifikasi

```bash
sudo ufw status
```

Output yang diharapkan:

```
Status: active

To                         Action      From
--                         ------      ----
22/tcp                     ALLOW       Anywhere
Nginx Full                 ALLOW       Anywhere
```

### 13.3 Keamanan Tambahan (Disarankan)

**Nonaktifkan login root via SSH:**

```bash
sudo nano /etc/ssh/sshd_config
```

Ubah:

```
PermitRootLogin no
```

```bash
sudo systemctl restart sshd
```

**Install Fail2Ban (Anti Brute Force):**

```bash
sudo apt install -y fail2ban
sudo systemctl enable fail2ban
sudo systemctl start fail2ban
```

**Pastikan file .env tidak bisa diakses dari web:**

Nginx sudah dikonfigurasi menolak akses ke file dotfile (`location ~ /\.`). Verifikasi:

```bash
curl -I https://esurat.pemda.go.id/.env
# Harus 403 Forbidden atau 404
```

---

## 14. Verifikasi Akhir

Jalankan checklist berikut setelah seluruh proses selesai:

### 14.1 Checklist Layanan

```bash
# PHP-FPM aktif
sudo systemctl status php8.3-fpm | grep Active

# MySQL aktif
sudo systemctl status mysql | grep Active

# Nginx aktif
sudo systemctl status nginx | grep Active

# Queue worker aktif
sudo systemctl status sumakel-queue | grep Active
```

Semua harus menampilkan `active (running)`.

### 14.2 Checklist Aplikasi

| No | Item | Cara Verifikasi | Status |
|----|------|-----------------|--------|
| 1 | Halaman login tampil | Buka `https://DOMAIN` di browser | ☐ |
| 2 | Login berhasil | Login dengan akun superadmin | ☐ |
| 3 | Dashboard tampil tanpa error | Setelah login, dashboard muncul dengan benar | ☐ |
| 4 | HTTPS aktif | Gembok hijau di address bar browser | ☐ |
| 5 | CRUD OPD berfungsi | Superadmin: buat OPD baru | ☐ |
| 6 | CRUD User berfungsi | Superadmin: buat Admin TU baru | ☐ |
| 7 | Surat masuk bisa dicatat | Login Admin TU: catat surat + upload lampiran | ☐ |
| 8 | Upload lampiran berhasil | File PDF/gambar terupload dan bisa diunduh | ☐ |
| 9 | Disposisi berfungsi | Admin TU disposisi ke Pimpinan | ☐ |
| 10 | Notifikasi in-app muncul | Pimpinan melihat badge lonceng +1 | ☐ |
| 11 | Notifikasi Telegram terkirim | Pimpinan (ber-Chat ID) menerima pesan bot | ☐ |
| 12 | Surat keluar + penerbitan | Admin TU buat draft → upload PDF → terbitkan → nomor muncul | ☐ |
| 13 | Routing antar-OPD | Surat keluar internal → muncul di OPD tujuan | ☐ |
| 14 | Cetak agenda PDF | Buka Agenda → isi periode → Cetak PDF → file PDF terunduh | ☐ |
| 15 | .env tidak bocor | `curl https://DOMAIN/.env` → 403/404 | ☐ |

---

## 15. Pemeliharaan & Backup

### 15.1 Backup Database (Harian)

Buat script backup:

```bash
sudo nano /opt/backup-sumakel.sh
```

```bash
#!/bin/bash
BACKUP_DIR="/opt/backups/sumakel"
TIMESTAMP=$(date +%Y%m%d_%H%M%S)
mkdir -p $BACKUP_DIR

# Backup database
mysqldump -u sumakel -p'GantiDenganPasswordKuat' sumakel | gzip > "$BACKUP_DIR/db_$TIMESTAMP.sql.gz"

# Backup lampiran
tar -czf "$BACKUP_DIR/lampiran_$TIMESTAMP.tar.gz" -C /var/www/sumakel/storage/app/private lampiran 2>/dev/null

# Hapus backup lebih dari 30 hari
find $BACKUP_DIR -name "*.gz" -mtime +30 -delete

echo "Backup selesai: $TIMESTAMP"
```

```bash
sudo chmod +x /opt/backup-sumakel.sh
```

Jadwalkan via cron (setiap hari jam 02:00):

```bash
sudo crontab -e
```

Tambahkan:

```
0 2 * * * /opt/backup-sumakel.sh >> /var/log/sumakel-backup.log 2>&1
```

### 15.2 Rotasi Log Laravel

```bash
# Periksa ukuran log
du -sh /var/www/sumakel/storage/logs/

# Bersihkan jika terlalu besar
su - deploy
cd /var/www/sumakel
truncate -s 0 storage/logs/laravel.log
```

Laravel sudah menggunakan `daily` log channel secara default — file log otomatis dipisahkan per hari.

### 15.3 Update Sertifikat SSL

Certbot sudah mengatur auto-renewal. Verifikasi:

```bash
sudo certbot renew --dry-run
```

---

## 16. Update Aplikasi

Ketika ada versi baru SUMAKEL:

```bash
su - deploy
cd /var/www/sumakel

# 1. Tarik kode terbaru
git pull origin main

# 2. Install dependensi
composer install --no-dev --optimize-autoloader
npm ci && npm run build

# 3. Migrasi database (jika ada)
php artisan migrate --force

# 4. Refresh cache
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache

# 5. Restart queue worker
sudo systemctl restart sumakel-queue
```

> **Penting:** Selalu backup database **sebelum** menjalankan update. Jalankan `/opt/backup-sumakel.sh` terlebih dahulu.

---

## 17. Troubleshooting

### 17.1 Halaman Blank / Error 500

```bash
# Periksa log Laravel
tail -100 /var/www/sumakel/storage/logs/laravel.log

# Periksa log Nginx
tail -50 /var/log/nginx/error.log

# Periksa permissions
ls -la /var/www/sumakel/storage/
ls -la /var/www/sumakel/bootstrap/cache/

# Fix permissions
sudo chown -R deploy:www-data /var/www/sumakel/storage
sudo chmod -R 775 /var/www/sumakel/storage
```

### 17.2 Database Connection Refused

```bash
# Pastikan MySQL aktif
sudo systemctl status mysql

# Test koneksi
mysql -u sumakel -p -e "SELECT 1;"

# Periksa konfigurasi .env
grep DB_ /var/www/sumakel/.env
```

### 17.3 Upload Gagal (413 Request Entity Too Large)

```bash
# Periksa Nginx client_max_body_size
grep client_max_body_size /etc/nginx/sites-available/sumakel
# Harus: client_max_body_size 25M;

# Periksa PHP upload_max_filesize
php -i | grep upload_max_filesize
# Harus: 20M
```

### 17.4 Queue Worker Mati / Notifikasi Tidak Terkirim

```bash
# Periksa status
sudo systemctl status sumakel-queue

# Periksa log
tail -50 /var/www/sumakel/storage/logs/queue-worker.log

# Restart
sudo systemctl restart sumakel-queue

# Periksa antrian yang gagal
cd /var/www/sumakel
php artisan queue:failed
php artisan queue:retry all   # Coba ulang semua yang gagal
```

### 17.5 Telegram Tidak Terkirim

```bash
# 1. Pastikan token sudah dikonfigurasi
grep TELEGRAM /var/www/sumakel/.env

# 2. Test token secara langsung
curl -s "https://api.telegram.org/botTOKEN_ANDA/getMe"

# 3. Test kirim pesan manual
curl -s -X POST "https://api.telegram.org/botTOKEN_ANDA/sendMessage" \
  -d chat_id=CHAT_ID_ANDA \
  -d text="Test dari server SUMAKEL"

# 4. Periksa log
grep -i telegram /var/www/sumakel/storage/logs/laravel.log | tail -20

# 5. Pastikan queue worker jalan
sudo systemctl status sumakel-queue
```

### 17.6 CSS/JS Tidak Termuat (Tampilan Berantakan)

```bash
# Pastikan build sudah jalan
ls -la /var/www/sumakel/public/build/

# Jika kosong, rebuild
cd /var/www/sumakel
npm ci && npm run build

# Pastikan APP_URL benar di .env
grep APP_URL /var/www/sumakel/.env
```

### 17.7 Lupa Password Superadmin

```bash
cd /var/www/sumakel
php artisan tinker
```

```php
$user = \App\Models\User::where('email', 'admin@pemda.go.id')->first();
$user->password = bcrypt('PasswordBaruAnda');
$user->save();
exit;
```

### 17.8 Perlu Reset Seluruh Data (Hati-hati!)

```bash
cd /var/www/sumakel
php artisan migrate:fresh --seed --force
php artisan config:cache
```

> **Peringatan:** Perintah ini **menghapus seluruh data** dan menggantinya dengan data demo. Hanya gunakan untuk instalasi awal atau lingkungan testing.

---

## Ringkasan Perintah Penting

| Keperluan | Perintah |
|-----------|----------|
| Restart Nginx | `sudo systemctl restart nginx` |
| Restart PHP-FPM | `sudo systemctl restart php8.3-fpm` |
| Restart MySQL | `sudo systemctl restart mysql` |
| Restart Queue | `sudo systemctl restart sumakel-queue` |
| Lihat log Laravel | `tail -f /var/www/sumakel/storage/logs/laravel.log` |
| Lihat log Nginx | `tail -f /var/log/nginx/error.log` |
| Lihat log Queue | `tail -f /var/www/sumakel/storage/logs/queue-worker.log` |
| Refresh cache | `php artisan config:cache && php artisan route:cache && php artisan view:cache` |
| Backup database | `/opt/backup-sumakel.sh` |
| Status semua service | `systemctl status nginx php8.3-fpm mysql sumakel-queue` |

---

*Panduan ini disusun untuk tim teknis/administrator server yang akan melakukan instalasi aplikasi e-Surat Pemda (SUMAKEL) versi 1.0 pada VPS Ubuntu Server 22.04 LTS.*
