<?php

namespace Tests\Feature;

use App\Models\Klasifikasi;
use App\Models\Lampiran;
use App\Models\Opd;
use App\Models\SuratMasuk;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class SuratMasukTest extends TestCase
{
    use RefreshDatabase;

    private Opd $opdA;

    private Opd $opdB;

    private User $adminA;

    private User $adminB;

    private Klasifikasi $klasifikasi;

    protected function setUp(): void
    {
        parent::setUp();

        foreach (['superadmin', 'admin_tu', 'pimpinan', 'staf'] as $role) {
            Role::firstOrCreate(['name' => $role]);
        }

        $this->opdA = Opd::create(['kode' => 'OPD_A', 'nama' => 'OPD A']);
        $this->opdB = Opd::create(['kode' => 'OPD_B', 'nama' => 'OPD B']);

        $this->adminA = User::factory()->create(['opd_id' => $this->opdA->id]);
        $this->adminA->assignRole('admin_tu');

        $this->adminB = User::factory()->create(['opd_id' => $this->opdB->id]);
        $this->adminB->assignRole('admin_tu');

        $this->klasifikasi = Klasifikasi::create(['kode' => '005', 'nama' => 'Undangan']);
    }

    private function validData(array $overrides = []): array
    {
        return array_merge([
            'klasifikasi_id' => $this->klasifikasi->id,
            'nomor_surat' => '001/UN/2026',
            'asal_surat' => 'Kementerian Test',
            'tanggal_surat' => '2026-06-01',
            'tanggal_terima' => '2026-06-02',
            'perihal' => 'Undangan Rapat',
            'sifat' => 'biasa',
        ], $overrides);
    }

    public function test_admin_tu_can_create_surat_masuk(): void
    {
        Storage::fake('local');

        $response = $this->actingAs($this->adminA)->post(route('surat-masuk.store'), array_merge(
            $this->validData(),
            ['lampirans' => [UploadedFile::fake()->create('test.pdf', 100, 'application/pdf')]]
        ));

        $response->assertRedirect();
        $this->assertDatabaseHas('surat_masuks', ['nomor_surat' => '001/UN/2026', 'opd_id' => $this->opdA->id]);
        $this->assertDatabaseCount('lampirans', 1);
    }

    public function test_nomor_agenda_is_auto_generated(): void
    {
        $this->actingAs($this->adminA)->post(route('surat-masuk.store'), $this->validData());
        $this->actingAs($this->adminA)->post(route('surat-masuk.store'), $this->validData(['nomor_surat' => '002/UN/2026']));

        $agendas = SuratMasuk::pluck('nomor_agenda')->sort()->values()->all();
        $this->assertEquals([1, 2], $agendas);
    }

    public function test_admin_tu_cannot_see_other_opd_surat(): void
    {
        $this->actingAs($this->adminA)->post(route('surat-masuk.store'), $this->validData());

        $sm = SuratMasuk::first();

        $response = $this->actingAs($this->adminB)->get(route('surat-masuk.show', $sm));
        $response->assertStatus(404);
    }

    public function test_index_filters_work(): void
    {
        $this->actingAs($this->adminA)->post(route('surat-masuk.store'), $this->validData());
        $this->actingAs($this->adminA)->post(route('surat-masuk.store'), $this->validData([
            'nomor_surat' => '002/UN/2026',
            'sifat' => 'penting',
        ]));

        $response = $this->actingAs($this->adminA)->get(route('surat-masuk.index', ['sifat' => 'penting']));
        $response->assertStatus(200);
        $response->assertSee('002/UN/2026');
        $response->assertDontSee('001/UN/2026');
    }

    public function test_lampiran_download_with_auth(): void
    {
        Storage::fake('local');

        $this->actingAs($this->adminA)->post(route('surat-masuk.store'), array_merge(
            $this->validData(),
            ['lampirans' => [UploadedFile::fake()->create('doc.pdf', 50, 'application/pdf')]]
        ));

        $lampiran = Lampiran::first();

        $response = $this->actingAs($this->adminA)->get(route('lampiran.download', $lampiran));
        $response->assertStatus(200);

        $response = $this->actingAs($this->adminB)->get(route('lampiran.download', $lampiran));
        $response->assertStatus(403);
    }

    public function test_staf_cannot_create_surat_masuk(): void
    {
        $staf = User::factory()->create(['opd_id' => $this->opdA->id]);
        $staf->assignRole('staf');

        $response = $this->actingAs($staf)->post(route('surat-masuk.store'), $this->validData());
        $response->assertStatus(403);
    }
}
