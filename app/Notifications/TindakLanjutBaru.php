<?php

namespace App\Notifications;

use App\Models\TindakLanjut;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class TindakLanjutBaru extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public TindakLanjut $tindakLanjut,
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
            'judul' => 'Tindak Lanjut Baru',
            'pesan' => "{$this->tindakLanjut->user->name} mencatat tindak lanjut: {$this->tindakLanjut->catatan}",
            'url' => route('surat-masuk.show', $this->tindakLanjut->disposisi->surat_masuk_id),
        ];
    }

    public function toTelegram(object $notifiable): string
    {
        return "✅ Tindak Lanjut Baru\n{$this->tindakLanjut->user->name} mencatat tindak lanjut.\nCatatan: {$this->tindakLanjut->catatan}";
    }
}
