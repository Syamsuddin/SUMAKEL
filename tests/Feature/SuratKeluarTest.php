<?php

namespace Tests\Feature;

use App\Events\SuratAntarOpdTerkirim;
use App\Models\Klasifikasi;
use App\Models\Opd;
use App\Models\SuratKeluar;
use App\Models\SuratMasuk;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class SuratKeluarTest extends TestCase
{
    use RefreshDatabase;

    private Opd $opdA;

    private Opd $opdB;

    private User $adminA;

    private Klasifikasi $klasifikasi;

    protected function setUp(): void
    {
        parent::setUp();

        foreach (['superadmin', 'admin_tu', 'pimpinan', 'staf'] as $role) {
            Role::firstOrCreate(['name' => $role]);
        }

        $this->opdA = Opd::create(['kode' => 'DISKO', 'nama' => 'Diskominfo']);
        $this->opdB = Opd::create(['kode' => 'DINKES', 'nama' => 'Dinas Kesehatan', 'format_nomor' => '{nomor}/{klasifikasi}/{kode_opd}/{tahun}']);

        $this->adminA = User::factory()->create(['opd_id' => $this->opdA->id]);
        $this->adminA->assignRole('admin_tu');

        $this->klasifikasi = Klasifikasi::create(['kode' => '005', 'nama' => 'Undangan']);
    }

    public function test_create_draft_surat_keluar(): void
    {
        Storage::fake('local');

        $response = $this->actingAs($this->adminA)->post(route('surat-keluar.store'), [
            'klasifikasi_id' => $this->klasifikasi->id,
            'tanggal_surat' => '2026-06-04',
            'jenis_tujuan' => 'eksternal',
            'tujuan_eksternal' => 'Kementerian ABC',
            'perihal' => 'Undangan Rapat',
            'sifat' => 'biasa',
            'lampirans' => [UploadedFile::fake()->create('surat.pdf', 100, 'application/pdf')],
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('surat_keluars', ['perihal' => 'Undangan Rapat', 'status' => 'draft']);
    }

    public function test_draft_has_no_nomor(): void
    {
        $sk = SuratKeluar::create([
            'opd_id' => $this->opdA->id,
            'klasifikasi_id' => $this->klasifikasi->id,
            'tanggal_surat' => '2026-06-04',
            'jenis_tujuan' => 'eksternal',
            'tujuan_eksternal' => 'Test',
            'perihal' => 'Test',
            'sifat' => 'biasa',
        ]);

        $this->assertNull($sk->nomor);
        $this->assertNull($sk->nomor_urut);
    }

    public function test_terbitkan_generates_nomor(): void
    {
        Storage::fake('local');

        $sk = SuratKeluar::create([
            'opd_id' => $this->opdA->id,
            'klasifikasi_id' => $this->klasifikasi->id,
            'tanggal_surat' => '2026-06-04',
            'jenis_tujuan' => 'eksternal',
            'tujuan_eksternal' => 'Test',
            'perihal' => 'Test',
            'sifat' => 'biasa',
        ]);

        $sk->lampirans()->create([
            'path' => 'lampiran/test.pdf',
            'nama_asli' => 'test.pdf',
            'mime' => 'application/pdf',
            'ukuran' => 1000,
        ]);

        $response = $this->actingAs($this->adminA)->post(route('surat-keluar.terbitkan', $sk));
        $response->assertRedirect();

        $sk->refresh();
        $this->assertEquals('terbit', $sk->status);
        $this->assertNotNull($sk->nomor);
        $this->assertEquals(1, $sk->nomor_urut);
        $this->assertStringContains('DISKO', $sk->nomor);
        $this->assertStringContains('VI', $sk->nomor);
    }

    public function test_sequential_nomor_urut(): void
    {
        Storage::fake('local');

        foreach (range(1, 2) as $i) {
            $sk = SuratKeluar::create([
                'opd_id' => $this->opdA->id,
                'klasifikasi_id' => $this->klasifikasi->id,
                'tanggal_surat' => '2026-06-04',
                'jenis_tujuan' => 'eksternal',
                'tujuan_eksternal' => 'Test',
                'perihal' => "Test $i",
                'sifat' => 'biasa',
            ]);
            $sk->lampirans()->create([
                'path' => 'lampiran/test.pdf',
                'nama_asli' => 'test.pdf',
                'mime' => 'application/pdf',
                'ukuran' => 1000,
            ]);
            $this->actingAs($this->adminA)->post(route('surat-keluar.terbitkan', $sk));
        }

        $nomors = SuratKeluar::orderBy('nomor_urut')->pluck('nomor_urut')->all();
        $this->assertEquals([1, 2], $nomors);
    }

    public function test_different_opd_format(): void
    {
        Storage::fake('local');

        $adminB = User::factory()->create(['opd_id' => $this->opdB->id]);
        $adminB->assignRole('admin_tu');

        $sk = SuratKeluar::create([
            'opd_id' => $this->opdB->id,
            'klasifikasi_id' => $this->klasifikasi->id,
            'tanggal_surat' => '2026-06-04',
            'jenis_tujuan' => 'eksternal',
            'tujuan_eksternal' => 'Test',
            'perihal' => 'Test B',
            'sifat' => 'biasa',
        ]);
        $sk->lampirans()->create([
            'path' => 'lampiran/test.pdf',
            'nama_asli' => 'test.pdf',
            'mime' => 'application/pdf',
            'ukuran' => 1000,
        ]);

        $this->actingAs($adminB)->post(route('surat-keluar.terbitkan', $sk));

        $sk->refresh();
        $this->assertStringContains('DINKES', $sk->nomor);
        $this->assertMatchesRegularExpression('/^001\/005\/DINKES\/2026$/', $sk->nomor);
    }

    public function test_routing_internal_creates_surat_masuk(): void
    {
        Storage::fake('local');
        Event::fake([SuratAntarOpdTerkirim::class]);

        $adminB = User::factory()->create(['opd_id' => $this->opdB->id]);
        $adminB->assignRole('admin_tu');

        $sk = SuratKeluar::create([
            'opd_id' => $this->opdA->id,
            'klasifikasi_id' => $this->klasifikasi->id,
            'tanggal_surat' => '2026-06-04',
            'jenis_tujuan' => 'internal',
            'tujuan_opd_id' => $this->opdB->id,
            'perihal' => 'Routing Test',
            'sifat' => 'biasa',
        ]);
        $sk->lampirans()->create([
            'path' => 'lampiran/test.pdf',
            'nama_asli' => 'test.pdf',
            'mime' => 'application/pdf',
            'ukuran' => 1000,
        ]);

        $this->actingAs($this->adminA)->post(route('surat-keluar.terbitkan', $sk));

        $sk->refresh();
        $this->assertEquals('terbit', $sk->status);

        $smTujuan = SuratMasuk::withoutGlobalScopes()->where('surat_keluar_id', $sk->id)->first();
        $this->assertNotNull($smTujuan);
        $this->assertEquals($this->opdB->id, $smTujuan->opd_id);
        $this->assertEquals($sk->nomor, $smTujuan->nomor_surat);
        $this->assertEquals('Diskominfo', $smTujuan->asal_surat);
        $this->assertEquals(1, $smTujuan->lampirans()->count());

        Event::assertDispatched(SuratAntarOpdTerkirim::class);

        $response = $this->actingAs($adminB)->get(route('surat-masuk.index'));
        $response->assertSee('Routing Test');
    }

    private function assertStringContains(string $needle, string $haystack): void
    {
        $this->assertTrue(
            str_contains($haystack, $needle),
            "Failed asserting that '$haystack' contains '$needle'."
        );
    }
}
