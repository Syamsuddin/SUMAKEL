<?php

namespace Tests\Feature;

use App\Events\DisposisiDibuat;
use App\Events\TindakLanjutDicatat;
use App\Models\Klasifikasi;
use App\Models\Opd;
use App\Models\SuratMasuk;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class DisposisiTest extends TestCase
{
    use RefreshDatabase;

    private Opd $opd;

    private User $adminTu;

    private User $pimpinan;

    private User $staf;

    private SuratMasuk $suratMasuk;

    protected function setUp(): void
    {
        parent::setUp();

        foreach (['superadmin', 'admin_tu', 'pimpinan', 'staf'] as $role) {
            Role::firstOrCreate(['name' => $role]);
        }

        $this->opd = Opd::create(['kode' => 'TEST', 'nama' => 'OPD Test']);
        $klasifikasi = Klasifikasi::create(['kode' => '005', 'nama' => 'Undangan']);

        $this->adminTu = User::factory()->create(['opd_id' => $this->opd->id]);
        $this->adminTu->assignRole('admin_tu');

        $this->pimpinan = User::factory()->create(['opd_id' => $this->opd->id]);
        $this->pimpinan->assignRole('pimpinan');

        $this->staf = User::factory()->create(['opd_id' => $this->opd->id]);
        $this->staf->assignRole('staf');

        $this->suratMasuk = SuratMasuk::create([
            'opd_id' => $this->opd->id,
            'klasifikasi_id' => $klasifikasi->id,
            'nomor_agenda' => 1,
            'nomor_surat' => '001/TEST/2026',
            'asal_surat' => 'Test',
            'tanggal_surat' => '2026-06-01',
            'tanggal_terima' => '2026-06-02',
            'perihal' => 'Test Surat',
            'sifat' => 'biasa',
            'status' => 'baru',
        ]);
    }

    public function test_full_disposition_flow(): void
    {
        Event::fake([DisposisiDibuat::class, TindakLanjutDicatat::class]);

        // Admin TU disposisi ke pimpinan
        $response = $this->actingAs($this->adminTu)->post(route('disposisi.store', $this->suratMasuk), [
            'kepada_user_id' => $this->pimpinan->id,
            'instruksi' => 'Mohon disposisi ke staf terkait',
        ]);
        $response->assertRedirect();
        Event::assertDispatched(DisposisiDibuat::class);

        $this->suratMasuk->refresh();
        $this->assertEquals('didisposisi', $this->suratMasuk->status);

        $disposisiAkar = $this->suratMasuk->disposisis()->first();

        // Pimpinan meneruskan ke staf
        $response = $this->actingAs($this->pimpinan)->post(route('disposisi.store', $this->suratMasuk), [
            'kepada_user_id' => $this->staf->id,
            'instruksi' => 'Segera tindak lanjuti',
            'batas_waktu' => '2026-06-10',
        ]);
        $response->assertRedirect();

        $disposisiAnak = $this->suratMasuk->disposisis()->where('parent_id', $disposisiAkar->id)->first();
        $this->assertNotNull($disposisiAnak);

        // Staf mencatat tindak lanjut dan menandai selesai
        $response = $this->actingAs($this->staf)->post(route('tindak-lanjut.store', $disposisiAnak), [
            'catatan' => 'Sudah ditindaklanjuti',
            'selesai' => 1,
        ]);
        $response->assertRedirect();
        Event::assertDispatched(TindakLanjutDicatat::class);

        $disposisiAnak->refresh();
        $this->assertEquals('selesai', $disposisiAnak->status);

        // Disposisi akar masih aktif karena belum ditandai selesai
        $disposisiAkar->refresh();
        $this->assertNotEquals('selesai', $disposisiAkar->status);

        // Selesaikan disposisi akar juga
        $response = $this->actingAs($this->pimpinan)->post(route('tindak-lanjut.store', $disposisiAkar), [
            'catatan' => 'Selesai semua',
            'selesai' => 1,
        ]);

        $this->suratMasuk->refresh();
        $this->assertEquals('selesai', $this->suratMasuk->status);
    }

    public function test_staf_without_disposisi_cannot_create_disposisi(): void
    {
        $staf2 = User::factory()->create(['opd_id' => $this->opd->id]);
        $staf2->assignRole('staf');

        $response = $this->actingAs($staf2)->post(route('disposisi.store', $this->suratMasuk), [
            'kepada_user_id' => $this->staf->id,
            'instruksi' => 'Hacking attempt',
        ]);
        $response->assertStatus(403);
    }

    public function test_events_are_dispatched(): void
    {
        Event::fake([DisposisiDibuat::class, TindakLanjutDicatat::class]);

        $this->actingAs($this->adminTu)->post(route('disposisi.store', $this->suratMasuk), [
            'kepada_user_id' => $this->pimpinan->id,
            'instruksi' => 'Test',
        ]);

        Event::assertDispatched(DisposisiDibuat::class, function ($event) {
            return $event->disposisi->kepada_user_id === $this->pimpinan->id;
        });

        $disposisi = $this->suratMasuk->disposisis()->first();

        $this->actingAs($this->pimpinan)->post(route('tindak-lanjut.store', $disposisi), [
            'catatan' => 'Noted',
        ]);

        Event::assertDispatched(TindakLanjutDicatat::class);
    }
}
