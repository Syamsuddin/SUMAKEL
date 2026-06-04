<?php

namespace Tests\Feature;

use App\Events\DisposisiDibuat;
use App\Events\SuratAntarOpdTerkirim;
use App\Events\TindakLanjutDicatat;
use App\Models\Disposisi;
use App\Models\Klasifikasi;
use App\Models\Opd;
use App\Models\SuratKeluar;
use App\Models\SuratMasuk;
use App\Models\TindakLanjut;
use App\Models\User;
use App\Notifications\DisposisiBaru;
use App\Notifications\SuratAntarOpdMasuk;
use App\Notifications\TindakLanjutBaru;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class NotifikasiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        foreach (['superadmin', 'admin_tu', 'pimpinan', 'staf'] as $role) {
            Role::firstOrCreate(['name' => $role]);
        }
    }

    public function test_disposisi_dibuat_sends_notification_to_recipient(): void
    {
        Notification::fake();

        $opd = Opd::create(['kode' => 'TEST', 'nama' => 'Test']);
        $klasifikasi = Klasifikasi::create(['kode' => '005', 'nama' => 'Test']);

        $dari = User::factory()->create(['opd_id' => $opd->id]);
        $dari->assignRole('admin_tu');

        $kepada = User::factory()->create(['opd_id' => $opd->id]);
        $kepada->assignRole('pimpinan');

        $sm = SuratMasuk::create([
            'opd_id' => $opd->id, 'klasifikasi_id' => $klasifikasi->id,
            'nomor_agenda' => 1, 'nomor_surat' => '001', 'asal_surat' => 'Test',
            'tanggal_surat' => '2026-06-01', 'tanggal_terima' => '2026-06-02',
            'perihal' => 'Test', 'sifat' => 'biasa', 'status' => 'baru',
        ]);

        $disposisi = Disposisi::create([
            'surat_masuk_id' => $sm->id, 'dari_user_id' => $dari->id,
            'kepada_user_id' => $kepada->id, 'instruksi' => 'Test instruksi',
        ]);

        event(new DisposisiDibuat($disposisi));

        Notification::assertSentTo($kepada, DisposisiBaru::class);
        Notification::assertNotSentTo($dari, DisposisiBaru::class);
    }

    public function test_tindak_lanjut_sends_notification_to_disposer(): void
    {
        Notification::fake();

        $opd = Opd::create(['kode' => 'TEST', 'nama' => 'Test']);
        $klasifikasi = Klasifikasi::create(['kode' => '005', 'nama' => 'Test']);

        $dari = User::factory()->create(['opd_id' => $opd->id]);
        $kepada = User::factory()->create(['opd_id' => $opd->id]);

        $sm = SuratMasuk::create([
            'opd_id' => $opd->id, 'klasifikasi_id' => $klasifikasi->id,
            'nomor_agenda' => 1, 'nomor_surat' => '001', 'asal_surat' => 'Test',
            'tanggal_surat' => '2026-06-01', 'tanggal_terima' => '2026-06-02',
            'perihal' => 'Test', 'sifat' => 'biasa', 'status' => 'baru',
        ]);

        $disposisi = Disposisi::create([
            'surat_masuk_id' => $sm->id, 'dari_user_id' => $dari->id,
            'kepada_user_id' => $kepada->id, 'instruksi' => 'Test',
        ]);

        $tl = TindakLanjut::create([
            'disposisi_id' => $disposisi->id, 'user_id' => $kepada->id,
            'catatan' => 'Sudah selesai',
        ]);

        event(new TindakLanjutDicatat($tl));

        Notification::assertSentTo($dari, TindakLanjutBaru::class);
    }

    public function test_surat_antar_opd_sends_notification_to_admin_tu_tujuan(): void
    {
        Notification::fake();

        $opdA = Opd::create(['kode' => 'A', 'nama' => 'OPD A']);
        $opdB = Opd::create(['kode' => 'B', 'nama' => 'OPD B']);
        $klasifikasi = Klasifikasi::create(['kode' => '005', 'nama' => 'Test']);

        $adminB = User::factory()->create(['opd_id' => $opdB->id]);
        $adminB->assignRole('admin_tu');

        $stafB = User::factory()->create(['opd_id' => $opdB->id]);
        $stafB->assignRole('staf');

        $sk = SuratKeluar::create([
            'opd_id' => $opdA->id, 'klasifikasi_id' => $klasifikasi->id,
            'tanggal_surat' => '2026-06-04', 'jenis_tujuan' => 'internal',
            'tujuan_opd_id' => $opdB->id, 'perihal' => 'Test',
            'sifat' => 'biasa', 'status' => 'terbit', 'nomor' => '001/TEST',
        ]);

        $sm = SuratMasuk::withoutGlobalScopes()->create([
            'opd_id' => $opdB->id, 'klasifikasi_id' => $klasifikasi->id,
            'nomor_agenda' => 1, 'nomor_surat' => '001/TEST', 'asal_surat' => 'OPD A',
            'tanggal_surat' => '2026-06-04', 'tanggal_terima' => '2026-06-04',
            'perihal' => 'Test', 'sifat' => 'biasa', 'status' => 'baru',
            'surat_keluar_id' => $sk->id,
        ]);

        event(new SuratAntarOpdTerkirim($sk, $sm));

        Notification::assertSentTo($adminB, SuratAntarOpdMasuk::class);
        Notification::assertNotSentTo($stafB, SuratAntarOpdMasuk::class);
    }

    public function test_dashboard_accessible_per_role(): void
    {
        $opd = Opd::create(['kode' => 'TEST', 'nama' => 'Test']);

        foreach (['admin_tu', 'pimpinan', 'staf'] as $role) {
            $user = User::factory()->create(['opd_id' => $opd->id]);
            $user->assignRole($role);

            $response = $this->actingAs($user)->get(route('dashboard'));
            $response->assertStatus(200);
        }

        $superadmin = User::factory()->create();
        $superadmin->assignRole('superadmin');
        $response = $this->actingAs($superadmin)->get(route('dashboard'));
        $response->assertStatus(200);
    }
}
