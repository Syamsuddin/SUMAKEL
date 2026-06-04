<?php

namespace Tests\Feature;

use App\Models\Opd;
use App\Services\PenomoranService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PenomoranServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_next_returns_sequential_numbers(): void
    {
        $opd = Opd::create(['kode' => 'TEST', 'nama' => 'Test']);
        $service = app(PenomoranService::class);

        $results = [];
        for ($i = 0; $i < 50; $i++) {
            $results[] = $service->next($opd->id, 'agenda_masuk', 2026);
        }

        $this->assertEquals(range(1, 50), $results);
    }

    public function test_counters_are_isolated_per_opd(): void
    {
        $opdA = Opd::create(['kode' => 'A', 'nama' => 'OPD A']);
        $opdB = Opd::create(['kode' => 'B', 'nama' => 'OPD B']);
        $service = app(PenomoranService::class);

        $service->next($opdA->id, 'agenda_masuk', 2026);
        $service->next($opdA->id, 'agenda_masuk', 2026);
        $resultB = $service->next($opdB->id, 'agenda_masuk', 2026);

        $this->assertEquals(1, $resultB);
    }

    public function test_counters_are_isolated_per_jenis(): void
    {
        $opd = Opd::create(['kode' => 'TEST', 'nama' => 'Test']);
        $service = app(PenomoranService::class);

        $service->next($opd->id, 'agenda_masuk', 2026);
        $service->next($opd->id, 'agenda_masuk', 2026);
        $result = $service->next($opd->id, 'surat_keluar', 2026);

        $this->assertEquals(1, $result);
    }

    public function test_counters_are_isolated_per_tahun(): void
    {
        $opd = Opd::create(['kode' => 'TEST', 'nama' => 'Test']);
        $service = app(PenomoranService::class);

        $service->next($opd->id, 'agenda_masuk', 2025);
        $service->next($opd->id, 'agenda_masuk', 2025);
        $result = $service->next($opd->id, 'agenda_masuk', 2026);

        $this->assertEquals(1, $result);
    }
}
