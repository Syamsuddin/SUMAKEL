#!/bin/zsh
# Full smoke test SUMAKEL — menjalankan seluruh alur bisnis di aplikasi nyata lewat browser, per peran.
# Butuh: agent-browser CLI, php, zsh. Memakai SQLite terpisah + QUEUE_CONNECTION=sync (data lokal tidak disentuh).
#
#   zsh scripts/smoke-test.sh            # port default 8082
#   PORT=8090 zsh scripts/smoke-test.sh
set -u
cd "$(dirname "$0")/.."
PORT=${PORT:-8082}; B=http://localhost:$PORT
S=$(mktemp -d /tmp/sumakel-smoke.XXXXXX)
export DB_DATABASE=$S/smoke.sqlite QUEUE_CONNECTION=sync
touch $DB_DATABASE
php artisan migrate:fresh --seed --force >/dev/null || { echo "migrate gagal"; exit 1; }
php artisan serve --port=$PORT > $S/serve.log 2>&1 &
SERVER=$!; trap 'kill $SERVER 2>/dev/null; agent-browser close >/dev/null 2>&1' EXIT
sleep 2
printf '%%PDF-1.4\n1 0 obj<</Type/Catalog>>endobj\ntrailer<</Root 1 0 R>>\n%%%%EOF\n' > $S/lampiran-uji.pdf
agent-browser set viewport 1280 800 >/dev/null
PASS=0; FAIL=0; LOG=$S/smoke-report.txt; : > $LOG
ab() { agent-browser "$@" 2>&1; }
ok()   { PASS=$((PASS+1)); echo "PASS  $1" | tee -a $LOG; }
bad()  { FAIL=$((FAIL+1)); echo "FAIL  $1 — $2" | tee -a $LOG; ab screenshot $S/fail-$PASS-$FAIL.png >/dev/null; }
go()   { ab open "$B$1" >/dev/null; sleep 0.6; }
title(){ ab get title | sed 's/ — e-Surat Pemda//'; }
ev()   { ab eval "$1"; }
has()  { local t; t=$(ev "document.body.innerText.includes($(printf '%s' "$1" | python3 -c 'import json,sys;print(json.dumps(sys.stdin.read()))'))"); [[ "$t" == "true" ]]; }
expect_text() { if has "$1"; then ok "$2"; else bad "$2" "teks '$1' tidak ada"; fi; }
expect_no_text() { if has "$1"; then bad "$2" "teks '$1' seharusnya tidak ada"; else ok "$2"; fi; }
expect_title() { local t; t=$(title); if [[ "$t" == *"$1"* ]]; then ok "$2"; else bad "$2" "title='$t'"; fi; }
login() { go /login; ab fill '#email' "$1" >/dev/null; ab fill '#password' 'password' >/dev/null; ab click 'main form button[type=submit]' >/dev/null; sleep 1.2; }
logout(){ go /dashboard; ev 'document.querySelector("#logout-form").submit()' >/dev/null; sleep 1; }
submit(){ ab click "$1" >/dev/null; sleep 1.2; }
setdate(){ ev "(() => { const e=document.querySelector('$1'); e.value='$2'; e.dispatchEvent(new Event('input',{bubbles:true})); e.dispatchEvent(new Event('change',{bubbles:true})); return e.value; })()" >/dev/null; }
sqlq() { php artisan tinker --execute="$1" 2>/dev/null | tail -1; }

echo "=== 0. GUEST ===" | tee -a $LOG
go /; expect_title "Masuk" "root redirect ke login"
go /dashboard; expect_title "Masuk" "dashboard tanpa login → login"
go /password/reset; expect_title "Lupa Kata Sandi" "halaman lupa sandi tampil"
go /halaman-tidak-ada; t=$(ev 'document.title + "|" + (document.querySelector("h1,h2,.code")||{}).textContent'); [[ "$t" == *404* ]] && ok "404 page (title=$t)" || bad "404 page" "$t"
go /login; ab fill '#email' 'admin_tu.setda@esurat.test' >/dev/null; ab fill '#password' 'salah' >/dev/null; ab click 'main form button[type=submit]' >/dev/null; sleep 1
c=$(ev 'document.querySelector(".invalid-feedback") ? document.querySelector(".invalid-feedback").textContent.trim() : ""'); [[ -n "$c" && "$c" != '""' ]] && ok "login salah → error inline ($c)" || bad "login salah → error inline" "tidak ada pesan"

echo "=== 1. SUPERADMIN: master data ===" | tee -a $LOG
login superadmin@esurat.test; expect_title "Dashboard Superadmin" "login superadmin"
expect_text "Rekap Per OPD" "dashboard superadmin rekap"
go /opd/create; ab fill '#kode' 'SMOKE' >/dev/null; ab fill '#nama' 'Dinas Uji Asap' >/dev/null; submit 'main form button[type=submit]'
expect_text "Dinas Uji Asap" "OPD create → tampil di daftar"
id=$(sqlq 'echo \App\Models\Opd::where("kode","SMOKE")->value("id");')
go /opd/$id/edit; ab fill '#nama' 'Dinas Uji Asap (Ubah)' >/dev/null; ev 'document.querySelector("#is_aktif").checked=false' >/dev/null; submit 'main form button[type=submit]'
expect_text "Dinas Uji Asap (Ubah)" "OPD edit → nama berubah"
st=$(sqlq 'echo \App\Models\Opd::where("kode","SMOKE")->value("is_aktif") ? "aktif" : "nonaktif";'); [[ "$st" == "nonaktif" ]] && ok "OPD toggle nonaktif tersimpan" || bad "OPD toggle nonaktif" "$st"
go /klasifikasi/create; ab fill '#kode' '999' >/dev/null; ab fill '#nama' 'Klasifikasi Uji' >/dev/null; submit 'main form button[type=submit]'
expect_text "Klasifikasi Uji" "Klasifikasi create"
kid=$(sqlq 'echo \App\Models\Klasifikasi::where("kode","999")->value("id");')
go /klasifikasi/$kid/edit; ab fill '#nama' 'Klasifikasi Uji Ubah' >/dev/null; submit 'main form button[type=submit]'; expect_text "Klasifikasi Uji Ubah" "Klasifikasi edit"
ev "document.querySelector('#modal-hapus-$kid form').submit()" >/dev/null; sleep 1.2
expect_no_text "Klasifikasi Uji Ubah" "Klasifikasi hapus (via form modal)"
go /user/create; ab fill '#name' 'Staf Asap' >/dev/null; ab fill '#email' 'staf.asap@esurat.test' >/dev/null; ab fill '#password' 'password' >/dev/null; ab select '#role' 'staf' >/dev/null; ab select '#opd_id' "$(sqlq 'echo \App\Models\Opd::where("kode","SETDA")->value("id");')" >/dev/null; submit 'main form button[type=submit]'
expect_text "Staf Asap" "User create"
uid=$(sqlq 'echo \App\Models\User::where("email","staf.asap@esurat.test")->value("id");')
go /user/$uid/edit; ab fill '#name' 'Staf Asap Ubah' >/dev/null; submit 'main form button[type=submit]'; expect_text "Staf Asap Ubah" "User edit"
go /profil; ab fill '#name' 'Super Admin Uji' >/dev/null; submit 'main form button[type=submit]'; expect_text "Super Admin Uji" "Profil update nama"
go /surat-masuk; t=$(title); [[ "$t" == *"Surat Masuk"* || "$t" == *"403"* ]] && ok "superadmin /surat-masuk → $t" || bad "superadmin /surat-masuk" "$t"
logout

echo "=== 2. ADMIN TU SETDA: surat masuk + disposisi ===" | tee -a $LOG
login admin_tu.setda@esurat.test; expect_title "Dashboard Admin TU" "login admin TU SETDA"
go /surat-masuk; expect_text "Belum ada surat masuk" "SM index empty state"
go /surat-masuk/create
ab fill '#nomor_surat' 'UJI/001/2026' >/dev/null; ab fill '#asal_surat' 'Kantor Uji Asap' >/dev/null; ab select '#klasifikasi_id' "$(sqlq 'echo \App\Models\Klasifikasi::first()->id;')" >/dev/null
setdate '#tanggal_surat' '2026-09-10'; setdate '#tanggal_terima' '2026-09-16'; ab fill '#perihal' 'Perihal Uji Asap Surat Masuk Biasa' >/dev/null; ab select '#sifat' 'penting' >/dev/null
ab upload '#lampirans' $S/lampiran-uji.pdf >/dev/null; submit 'main form button[type=submit]'
expect_title "Detail Surat Masuk" "SM create → redirect detail"
expect_text "Perihal Uji Asap Surat Masuk Biasa" "SM detail perihal"
expect_text "lampiran-uji.pdf" "SM lampiran tersimpan & tampil"
smid=$(sqlq 'echo \App\Models\SuratMasuk::withoutGlobalScopes()->where("nomor_surat","UJI/001/2026")->value("id");')
agenda=$(sqlq "echo \App\Models\SuratMasuk::withoutGlobalScopes()->find($smid)->nomor_agenda;"); [[ -n "$agenda" ]] && ok "nomor agenda otomatis = $agenda" || bad "nomor agenda otomatis" "kosong"
lid=$(sqlq 'echo \App\Models\Lampiran::first()->id;')
ct=$(ev "fetch('/lampiran/$lid/download').then(r => r.status + ' ' + r.headers.get('content-type'))"); [[ "$ct" == *"200"*pdf* ]] && ok "lampiran download ($ct)" || bad "lampiran download" "$ct"
go /surat-masuk/$smid/edit; ab fill '#perihal' 'Perihal Uji Asap (diubah)' >/dev/null; submit 'main form button[type=submit]'; expect_text "Perihal Uji Asap (diubah)" "SM edit"
# rahasia
go /surat-masuk/create; ab fill '#nomor_surat' 'RHS/002/2026' >/dev/null; ab fill '#asal_surat' 'Sumber Rahasia' >/dev/null; ab select '#klasifikasi_id' "$(sqlq 'echo \App\Models\Klasifikasi::first()->id;')" >/dev/null; setdate '#tanggal_surat' '2026-09-11'; setdate '#tanggal_terima' '2026-09-16'; ab fill '#perihal' 'Perihal Sangat Rahasia' >/dev/null; ab select '#sifat' 'rahasia' >/dev/null; submit 'main form button[type=submit]'
expect_text "Perihal Sangat Rahasia" "SM rahasia create"
rid=$(sqlq 'echo \App\Models\SuratMasuk::withoutGlobalScopes()->where("nomor_surat","RHS/002/2026")->value("id");')
go /surat-masuk; expect_text "RHS/002/2026" "admin TU melihat surat rahasia di index"
n=$(ev 'document.querySelectorAll(".sk-table-wrap tbody tr").length'); [[ "$n" == "2" ]] && ok "SM index 2 baris" || bad "SM index 2 baris" "$n"
go "/surat-masuk?sifat=rahasia"; n=$(ev 'document.querySelectorAll(".sk-table-wrap tbody tr").length'); [[ "$n" == "1" ]] && ok "filter sifat=rahasia → 1 baris" || bad "filter sifat" "$n"
go "/surat-masuk?cari=Uji"; expect_text "UJI/001/2026" "filter cari"
# disposisi ke pimpinan
pid=$(sqlq 'echo \App\Models\User::where("email","pimpinan.setda@esurat.test")->value("id");')
go /surat-masuk/$smid; ab select '#kepada_user_id' "$pid" >/dev/null; ab fill '#instruksi' 'Mohon tindak lanjut segera' >/dev/null; setdate '#batas_waktu' '2026-09-20'; submit 'form[action$="/disposisi"] button[type=submit]'
expect_text "Mohon tindak lanjut segera" "disposisi admin→pimpinan tampil di timeline"
st=$(sqlq "echo \App\Models\SuratMasuk::withoutGlobalScopes()->find($smid)->status;"); [[ "$st" == "didisposisi" ]] && ok "status SM → didisposisi" || bad "status SM" "$st"
nn=$(sqlq "echo \App\Models\User::find($pid)->unreadNotifications()->count();"); [[ "$nn" == "1" ]] && ok "notifikasi DB ke pimpinan = 1 (queue sync)" || bad "notifikasi pimpinan" "$nn"

echo "=== 3. ADMIN TU SETDA: surat keluar + routing ===" | tee -a $LOG
go /surat-keluar; expect_text "Belum ada surat keluar" "SK index empty state"
dk=$(sqlq 'echo \App\Models\Opd::where("kode","DISKOMINFO")->value("id");')
go /surat-keluar/create; ab select '#klasifikasi_id' "$(sqlq 'echo \App\Models\Klasifikasi::first()->id;')" >/dev/null; setdate '#tanggal_surat' '2026-09-17'; ab fill '#perihal' 'Permohonan Data Uji Antar OPD' >/dev/null; ab select '#jenis_tujuan' 'internal' >/dev/null; ab select '#tujuan_opd_id' "$dk" >/dev/null; submit 'main form button[type=submit]'
expect_title "Detail Surat Keluar" "SK create draft → detail"
expect_text "Nomor diterbitkan saat surat terbit" "SK draft placeholder nomor"
expect_text "Unggah" "SK draft tanpa PDF → peringatan syarat terbit"
skid=$(sqlq 'echo \App\Models\SuratKeluar::withoutGlobalScopes()->where("perihal","Permohonan Data Uji Antar OPD")->value("id");')
b=$(ev 'document.querySelector("[data-bs-target=\"#modal-terbitkan\"]") ? "ada" : "tidak"'); [[ "$b" == '"tidak"' ]] && ok "tombol Terbitkan disembunyikan tanpa lampiran" || bad "tombol Terbitkan" "$b"
go /surat-keluar/$skid/edit; ab upload '#lampirans' $S/lampiran-uji.pdf >/dev/null; submit 'main form button[type=submit]'; expect_text "lampiran-uji.pdf" "SK edit + unggah PDF"
b=$(ev 'document.querySelector("[data-bs-target=\"#modal-terbitkan\"]") ? "ada" : "tidak"'); [[ "$b" == '"ada"' ]] && ok "tombol Terbitkan muncul setelah PDF" || bad "tombol Terbitkan muncul" "$b"
ev 'document.querySelector("#modal-terbitkan form").submit()' >/dev/null; sleep 1.5
nomor=$(sqlq "echo \App\Models\SuratKeluar::withoutGlobalScopes()->find($skid)->nomor;"); [[ "$nomor" == *"/SETDA/IX/2026" ]] && ok "terbitkan → nomor '$nomor'" || bad "terbitkan nomor" "$nomor"
expect_text "$nomor" "nomor tampil di kop detail"
expect_text "Diterima sebagai agenda" "routing antar-OPD: info agenda tujuan"
rsm=$(sqlq "echo \App\Models\SuratMasuk::withoutGlobalScopes()->where('surat_keluar_id',$skid)->where('opd_id',$dk)->count();"); [[ "$rsm" == "1" ]] && ok "surat masuk otomatis di DISKOMINFO" || bad "surat masuk otomatis" "$rsm"
lc=$(sqlq "echo \App\Models\SuratMasuk::withoutGlobalScopes()->where('surat_keluar_id',$skid)->first()->lampirans()->count();"); [[ "$lc" == "1" ]] && ok "lampiran diduplikasi (baris) ke SM tujuan" || bad "lampiran duplikasi" "$lc"
st=$(ev 'document.querySelectorAll(".sk-step.is-done").length + "/" + document.querySelectorAll(".sk-step.is-current").length'); [[ "$st" == '"2/1"' ]] && ok "stepper: 2 selesai, 1 aktif (Terkirim)" || bad "stepper" "$st"
ev 'document.querySelector("#modal-arsipkan form").submit()' >/dev/null; sleep 1.2
st=$(sqlq "echo \App\Models\SuratKeluar::withoutGlobalScopes()->find($skid)->status;"); [[ "$st" == "diarsip" ]] && ok "SK arsipkan → diarsip" || bad "SK arsipkan" "$st"
# eksternal
go /surat-keluar/create; ab select '#klasifikasi_id' "$(sqlq 'echo \App\Models\Klasifikasi::first()->id;')" >/dev/null; setdate '#tanggal_surat' '2026-09-17'; ab fill '#perihal' 'Surat Eksternal Uji' >/dev/null; ab fill '#tujuan_eksternal' 'Kementerian Uji' >/dev/null; ab upload '#lampirans' $S/lampiran-uji.pdf >/dev/null; submit 'main form button[type=submit]'
expect_text "Kementerian Uji" "SK eksternal create"
n=$(ev 'document.querySelectorAll(".sk-step").length'); [[ "$n" == "2" ]] && ok "stepper eksternal 2 langkah" || bad "stepper eksternal" "$n"
go /surat-keluar; n=$(ev 'document.querySelectorAll(".sk-table-wrap tbody tr").length'); [[ "$n" == "2" ]] && ok "SK index 2 baris" || bad "SK index" "$n"
go "/surat-keluar?status=diarsip"; n=$(ev 'document.querySelectorAll(".sk-table-wrap tbody tr").length'); [[ "$n" == "1" ]] && ok "filter status=diarsip" || bad "filter status" "$n"

echo "=== 4. ADMIN TU SETDA: agenda, notifikasi ===" | tee -a $LOG
go "/agenda?dari=2026-09-01&sampai=2026-09-30"; n=$(ev 'document.querySelectorAll("tbody tr").length'); [[ "$n" == "3" ]] && ok "agenda periode: 3 baris (2 SM + 1 SK terbit)" || bad "agenda baris" "$n"
go "/agenda?dari=2026-09-01&sampai=2026-09-30&jenis=keluar"; n=$(ev 'document.querySelectorAll("tbody tr").length'); [[ "$n" == "1" ]] && ok "agenda jenis=keluar → 1" || bad "agenda jenis" "$n"
ct=$(ev "fetch('/agenda/cetak?dari=2026-09-01&sampai=2026-09-30').then(r => r.status + ' ' + r.headers.get('content-type'))"); [[ "$ct" == *"200 application/pdf"* ]] && ok "agenda cetak PDF ($ct)" || bad "agenda cetak" "$ct"
go /notifikasi; expect_title "Notifikasi" "halaman notifikasi admin"
go /dashboard; expect_text "Surat Masuk Bulan Ini" "dashboard admin stat"; v=$(ev 'document.querySelector(".sk-stat-value").textContent.trim()'); [[ "$v" == '"2"' ]] && ok "stat SM bulan ini = 2" || bad "stat SM bulan ini" "$v"
logout

echo "=== 5. PIMPINAN SETDA ===" | tee -a $LOG
login pimpinan.setda@esurat.test; expect_title "Dashboard Pimpinan" "login pimpinan"
expect_text "Perihal Uji Asap (diubah)" "dashboard pimpinan: disposisi menunggu tampil"
badge=$(ev 'document.querySelector(".sk-topbar-badge") ? document.querySelector(".sk-topbar-badge").textContent.trim() : "0"'); [[ "$badge" == '"1"' ]] && ok "badge lonceng = 1" || bad "badge lonceng" "$badge"
go /surat-masuk/$rid; expect_text "Perihal Sangat Rahasia" "pimpinan boleh lihat rahasia se-OPD"
sid=$(sqlq 'echo \App\Models\User::where("email","staf.setda@esurat.test")->value("id");')
go /surat-masuk/$smid; expect_text "Teruskan Disposisi" "pimpinan: panel Teruskan Disposisi"
ab select '#kepada_user_id' "$sid" >/dev/null; ab fill '#instruksi' 'Siapkan bahan rapat' >/dev/null; submit 'form[action$="/disposisi"] button[type=submit]'
expect_text "Siapkan bahan rapat" "disposisi pimpinan→staf berantai"
nn=$(sqlq "echo \App\Models\User::find($sid)->unreadNotifications()->count();"); [[ "$nn" == "1" ]] && ok "notifikasi ke staf = 1" || bad "notifikasi staf" "$nn"
go /notifikasi; ev 'document.querySelector("form[action$=\"mark-all-read\"]").submit()' >/dev/null; sleep 1; nn=$(sqlq "echo \App\Models\User::find($pid)->unreadNotifications()->count();"); [[ "$nn" == "0" ]] && ok "tandai semua dibaca" || bad "tandai semua dibaca" "$nn"
logout

echo "=== 6. STAF SETDA ===" | tee -a $LOG
login staf.setda@esurat.test; expect_title "Dashboard Staf" "login staf"
expect_text "Siapkan bahan rapat" "dashboard staf: tugas tampil"
go /surat-masuk; expect_no_text "RHS/002/2026" "staf TIDAK melihat surat rahasia di index"
go /surat-masuk/$rid; t=$(title); [[ "$t" == *403* || "$t" == *Forbidden* ]] && ok "staf akses rahasia langsung → $t" || bad "staf akses rahasia" "$t"
go /surat-masuk/$smid; expect_text "Catat Tindak Lanjut" "staf: form tindak lanjut"
ab fill '#catatan' 'Bahan rapat sudah disiapkan' >/dev/null; ab upload '#tl_lampirans' $S/lampiran-uji.pdf >/dev/null; ab check '#selesai' >/dev/null; submit 'form[action*="tindak-lanjut"] button[type=submit]'
expect_text "Bahan rapat sudah disiapkan" "tindak lanjut tercatat di timeline"
dst=$(sqlq "echo \App\Models\Disposisi::withoutGlobalScopes()->where('kepada_user_id',$sid)->value('status');"); [[ "$dst" == "selesai" ]] && ok "disposisi staf → selesai" || bad "disposisi selesai" "$dst"
nn=$(sqlq "echo \App\Models\User::find($pid)->unreadNotifications()->count();"); [[ "$nn" == "1" ]] && ok "notifikasi tindak lanjut ke pimpinan" || bad "notif TL pimpinan" "$nn"
go /surat-keluar/create; t=$(title); [[ "$t" == *403* || "$t" == *Forbidden* ]] && ok "staf buat SK → 403" || bad "staf buat SK" "$t"
logout

echo "=== 7. ADMIN TU DISKOMINFO (OPD lain) ===" | tee -a $LOG
login admin_tu.diskominfo@esurat.test; expect_title "Dashboard Admin TU" "login admin TU DISKOMINFO"
go /surat-masuk; expect_text "Permohonan Data Uji Antar OPD" "surat routing tampil sebagai SM DISKOMINFO"
expect_no_text "UJI/001/2026" "isolasi OPD: SM SETDA tidak terlihat"
badge=$(ev 'document.querySelector(".sk-topbar-badge") ? document.querySelector(".sk-topbar-badge").textContent.trim() : "0"'); [[ "$badge" == '"1"' ]] && ok "notif surat antar-OPD ke admin DISKOMINFO" || bad "notif antar-OPD" "$badge"
go /surat-masuk/$smid; t=$(title); [[ "$t" == *403* || "$t" == *Forbidden* || "$t" == *404* || "$t" == *"Not Found"* ]] && ok "akses SM OPD lain → $t" || bad "akses SM OPD lain" "$t"
go /user; expect_no_text "Staf Asap" "isolasi OPD: user SETDA tidak terlihat"
go /opd; t=$(title); [[ "$t" == *403* || "$t" == *Forbidden* ]] && ok "admin TU /opd → 403" || bad "admin TU /opd" "$t"
logout

echo "=== 8. ADMIN TU SETDA: arsip SM selesai ===" | tee -a $LOG
login admin_tu.setda@esurat.test
sqlq "\App\Models\SuratMasuk::withoutGlobalScopes()->find($smid)->update(['status'=>'selesai']); echo 'ok';" >/dev/null
go /surat-masuk/$smid; b=$(ev 'document.querySelector("[data-bs-target=\"#modal-arsipkan\"]") ? "ada" : "tidak"'); [[ "$b" == '"ada"' ]] && ok "tombol Arsipkan untuk SM selesai" || bad "tombol Arsipkan SM" "$b"
ev 'document.querySelector("#modal-arsipkan form").submit()' >/dev/null; sleep 1.2
st=$(sqlq "echo \App\Models\SuratMasuk::withoutGlobalScopes()->find($smid)->status;"); [[ "$st" == "diarsip" ]] && ok "SM arsipkan → diarsip" || bad "SM arsipkan" "$st"
expect_text "sudah diarsipkan" "SM diarsip: panel aksi diganti keterangan"
logout; go /login; expect_title "Masuk" "logout → login"

echo "" | tee -a $LOG; echo "TOTAL: PASS=$PASS FAIL=$FAIL (laporan: $LOG)" | tee -a $LOG
[[ $FAIL -eq 0 ]]
