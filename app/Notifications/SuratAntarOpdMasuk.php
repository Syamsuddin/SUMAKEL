<?php

namespace App\Notifications;

use App\Models\SuratMasuk;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class SuratAntarOpdMasuk extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public SuratMasuk $suratMasuk,
    ) {}

    public function via(object $notifiable): array
    {
        $channels = ['database'];

        if ($notifiable->telegram_chat_id) {
            $channels[] = Channels\TelegramChannel::class;
        }

        return $channels;
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'judul' => 'Surat Masuk Antar-OPD',
            'pesan' => "Surat dari {$this->suratMasuk->asal_surat}: {$this->suratMasuk->perihal}",
            'url' => route('surat-masuk.show', $this->suratMasuk),
        ];
    }

    public function toTelegram(object $notifiable): string
    {
        return "📬 Surat Masuk Antar-OPD\nDari: {$this->suratMasuk->asal_surat}\nPerihal: {$this->suratMasuk->perihal}";
    }
}
