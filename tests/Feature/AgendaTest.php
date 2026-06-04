<?php

namespace Tests\Feature;

use App\Models\Klasifikasi;
use App\Models\Opd;
use App\Models\SuratMasuk;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class AgendaTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        foreach (['superadmin', 'admin_tu', 'pimpinan', 'staf'] as $role) {
            Role::firstOrCreate(['name' => $role]);
        }
    }

    public function test_agenda_page_accessible(): void
    {
        $opd = Opd::create(['kode' => 'TEST', 'nama' => 'Test']);
        $user = User::factory()->create(['opd_id' => $opd->id]);
        $user->assignRole('admin_tu');

        $response = $this->actingAs($user)->get(route('agenda.index'));
        $response->assertStatus(200);
    }

    public function test_agenda_cetak_returns_pdf(): void
    {
        $opd = Opd::create(['kode' => 'TEST', 'nama' => 'Test']);
        $klasifikasi = Klasifikasi::create(['kode' => '005', 'nama' => 'Test']);

        $user = User::factory()->create(['opd_id' => $opd->id]);
        $user->assignRole('admin_tu');

        SuratMasuk::create([
            'opd_id' => $opd->id, 'klasifikasi_id' => $klasifikasi->id,
            'nomor_agenda' => 1, 'nomor_surat' => '001', 'asal_surat' => 'Test',
            'tanggal_surat' => '2026-06-01', 'tanggal_terima' => '2026-06-02',
            'perihal' => 'Test agenda', 'sifat' => 'biasa', 'status' => 'baru',
        ]);

        $response = $this->actingAs($user)->get(route('agenda.cetak', [
            'dari' => '2026-06-01',
            'sampai' => '2026-06-30',
        ]));

        $response->assertStatus(200);
        $response->assertHeader('content-type', 'application/pdf');
    }
}
