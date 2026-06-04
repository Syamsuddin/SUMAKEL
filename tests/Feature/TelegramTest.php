<?php

namespace Tests\Feature;

use App\Models\Disposisi;
use App\Models\Klasifikasi;
use App\Models\Opd;
use App\Models\SuratMasuk;
use App\Models\User;
use App\Notifications\Channels\TelegramChannel;
use App\Notifications\DisposisiBaru;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class TelegramTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        foreach (['superadmin', 'admin_tu', 'pimpinan', 'staf'] as $role) {
            Role::firstOrCreate(['name' => $role]);
        }
    }

    public function test_telegram_channel_sends_correct_payload(): void
    {
        Http::fake();
        config(['services.telegram.bot_token' => 'test-token']);

        $opd = Opd::create(['kode' => 'TEST', 'nama' => 'Test']);
        $klasifikasi = Klasifikasi::create(['kode' => '005', 'nama' => 'Test']);

        $dari = User::factory()->create(['opd_id' => $opd->id]);
        $kepada = User::factory()->create(['opd_id' => $opd->id, 'telegram_chat_id' => '123456']);

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

        $channel = new TelegramChannel;
        $channel->send($kepada, new DisposisiBaru($disposisi));

        Http::assertSent(function ($request) {
            return str_contains($request->url(), 'api.telegram.org/bottest-token/sendMessage')
                && $request['chat_id'] === '123456'
                && str_contains($request['text'], 'Disposisi Baru');
        });
    }

    public function test_telegram_not_sent_without_chat_id(): void
    {
        Http::fake();
        config(['services.telegram.bot_token' => 'test-token']);

        $opd = Opd::create(['kode' => 'TEST', 'nama' => 'Test']);
        $user = User::factory()->create(['opd_id' => $opd->id, 'telegram_chat_id' => null]);

        $channel = new TelegramChannel;

        $klasifikasi = Klasifikasi::create(['kode' => '005', 'nama' => 'Test']);
        $dari = User::factory()->create(['opd_id' => $opd->id]);

        $sm = SuratMasuk::create([
            'opd_id' => $opd->id, 'klasifikasi_id' => $klasifikasi->id,
            'nomor_agenda' => 1, 'nomor_surat' => '001', 'asal_surat' => 'Test',
            'tanggal_surat' => '2026-06-01', 'tanggal_terima' => '2026-06-02',
            'perihal' => 'Test', 'sifat' => 'biasa', 'status' => 'baru',
        ]);

        $disposisi = Disposisi::create([
            'surat_masuk_id' => $sm->id, 'dari_user_id' => $dari->id,
            'kepada_user_id' => $user->id, 'instruksi' => 'Test',
        ]);

        $channel->send($user, new DisposisiBaru($disposisi));

        Http::assertNothingSent();
    }
}
