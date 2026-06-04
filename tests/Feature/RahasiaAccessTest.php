<?php

namespace Tests\Feature;

use App\Models\Disposisi;
use App\Models\Klasifikasi;
use App\Models\Opd;
use App\Models\SuratMasuk;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class RahasiaAccessTest extends TestCase
{
    use RefreshDatabase;

    private Opd $opd;

    private SuratMasuk $suratRahasia;

    protected function setUp(): void
    {
        parent::setUp();

        foreach (['superadmin', 'admin_tu', 'pimpinan', 'staf'] as $role) {
            Role::firstOrCreate(['name' => $role]);
        }

        $this->opd = Opd::create(['kode' => 'TEST', 'nama' => 'OPD Test']);
        $klasifikasi = Klasifikasi::create(['kode' => '005', 'nama' => 'Undangan']);

        $this->suratRahasia = SuratMasuk::create([
            'opd_id' => $this->opd->id,
            'klasifikasi_id' => $klasifikasi->id,
            'nomor_agenda' => 1,
            'nomor_surat' => 'RAHASIA/001/2026',
            'asal_surat' => 'Rahasia',
            'tanggal_surat' => '2026-06-01',
            'tanggal_terima' => '2026-06-02',
            'perihal' => 'Surat Rahasia',
            'sifat' => 'rahasia',
            'status' => 'didisposisi',
        ]);
    }

    public function test_admin_tu_can_see_surat_rahasia(): void
    {
        $adminTu = User::factory()->create(['opd_id' => $this->opd->id]);
        $adminTu->assignRole('admin_tu');

        $response = $this->actingAs($adminTu)->get(route('surat-masuk.show', $this->suratRahasia));
        $response->assertStatus(200);
    }

    public function test_staf_with_disposisi_can_see_surat_rahasia(): void
    {
        $pimpinan = User::factory()->create(['opd_id' => $this->opd->id]);
        $pimpinan->assignRole('pimpinan');

        $staf = User::factory()->create(['opd_id' => $this->opd->id]);
        $staf->assignRole('staf');

        Disposisi::create([
            'surat_masuk_id' => $this->suratRahasia->id,
            'dari_user_id' => $pimpinan->id,
            'kepada_user_id' => $staf->id,
            'instruksi' => 'Tindak lanjuti',
        ]);

        $response = $this->actingAs($staf)->get(route('surat-masuk.show', $this->suratRahasia));
        $response->assertStatus(200);
    }

    public function test_staf_without_disposisi_cannot_see_surat_rahasia(): void
    {
        $staf = User::factory()->create(['opd_id' => $this->opd->id]);
        $staf->assignRole('staf');

        $response = $this->actingAs($staf)->get(route('surat-masuk.show', $this->suratRahasia));
        $response->assertStatus(403);
    }

    public function test_surat_rahasia_filtered_from_index(): void
    {
        $staf = User::factory()->create(['opd_id' => $this->opd->id]);
        $staf->assignRole('staf');

        $response = $this->actingAs($staf)->get(route('surat-masuk.index'));
        $response->assertStatus(200);
        $response->assertDontSee('RAHASIA/001/2026');
    }
}
