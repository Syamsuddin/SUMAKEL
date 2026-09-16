<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

/**
 * Centralised icon + tone map for mail status and sensitivity (sifat)
 * so views never repeat colour ternaries.
 */
class StatusChip extends Component
{
    /** @var array<string, array{icon: string, tone: string, label: string}> */
    protected const MAP = [
        // sifat
        'biasa' => ['icon' => 'file-text', 'tone' => 'neutral', 'label' => 'Biasa'],
        'penting' => ['icon' => 'exclamation-triangle', 'tone' => 'warning', 'label' => 'Penting'],
        'rahasia' => ['icon' => 'shield-lock', 'tone' => 'danger', 'label' => 'Rahasia'],
        // status surat masuk
        'baru' => ['icon' => 'envelope', 'tone' => 'info', 'label' => 'Baru'],
        'didisposisi' => ['icon' => 'diagram-3', 'tone' => 'info', 'label' => 'Didisposisi'],
        'selesai' => ['icon' => 'patch-check', 'tone' => 'success', 'label' => 'Selesai'],
        'diarsip' => ['icon' => 'archive', 'tone' => 'neutral', 'label' => 'Diarsip'],
        // status surat keluar
        'draft' => ['icon' => 'pencil-square', 'tone' => 'neutral', 'label' => 'Draft'],
        'terbit' => ['icon' => 'patch-check', 'tone' => 'success', 'label' => 'Terbit'],
        // status disposisi
        'terkirim' => ['icon' => 'send', 'tone' => 'danger', 'label' => 'Terkirim'],
        'diproses' => ['icon' => 'hourglass-split', 'tone' => 'info', 'label' => 'Diproses'],
    ];

    public string $icon;

    public string $tone;

    public string $label;

    public function __construct(?string $status = null, ?string $sifat = null, ?string $label = null)
    {
        $key = strtolower((string) ($sifat ?? $status));
        $entry = self::MAP[$key] ?? ['icon' => 'circle', 'tone' => 'neutral', 'label' => ucfirst($key)];

        $this->icon = $entry['icon'];
        $this->tone = $entry['tone'];
        $this->label = $label ?? $entry['label'];
    }

    public function render(): View
    {
        return view('components.status-chip');
    }
}
