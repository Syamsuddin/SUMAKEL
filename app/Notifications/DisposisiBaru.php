<?php

namespace App\Notifications;

use App\Models\Disposisi;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class DisposisiBaru extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Disposisi $disposisi,
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
            'judul' => 'Disposisi Baru',
            'pesan' => "{$this->disposisi->dariUser->name} mendisposisikan surat kepada Anda: {$this->disposisi->instruksi}",
            'url' => route('surat-masuk.show', $this->disposisi->surat_masuk_id),
        ];
    }

    public function toTelegram(object $notifiable): string
    {
        return "📨 Disposisi Baru\n{$this->disposisi->dariUser->name} mendisposisikan surat kepada Anda.\nInstruksi: {$this->disposisi->instruksi}";
    }
}
