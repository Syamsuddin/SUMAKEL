<?php

namespace App\View\Components;

use Illuminate\Support\Facades\Route;
use Illuminate\View\Component;
use Illuminate\View\View;

/**
 * Builds breadcrumb items from the current route name, e.g.
 * "surat-masuk.show" => Beranda › Surat Masuk › Detail.
 */
class Breadcrumb extends Component
{
    /** @var array<string, string> */
    protected const MODULES = [
        'surat-masuk' => 'Surat Masuk',
        'surat-keluar' => 'Surat Keluar',
        'agenda' => 'Agenda',
        'notifikasi' => 'Notifikasi',
        'opd' => 'OPD',
        'klasifikasi' => 'Klasifikasi',
        'user' => 'Pengguna',
        'profil' => 'Profil',
    ];

    /** @var array<string, string> */
    protected const ACTIONS = [
        'create' => 'Tambah',
        'edit' => 'Ubah',
        'show' => 'Detail',
        'cetak' => 'Cetak',
    ];

    /** @var array<int, array{label: string, url: ?string}> */
    public array $items = [];

    public function __construct(?string $current = null)
    {
        $routeName = Route::currentRouteName() ?? '';
        $this->items[] = ['label' => 'Beranda', 'url' => Route::has('dashboard') ? route('dashboard') : null];

        if ($routeName === '' || $routeName === 'dashboard') {
            return;
        }

        [$module, $action] = array_pad(explode('.', $routeName, 2), 2, null);
        $moduleLabel = self::MODULES[$module] ?? ucwords(str_replace('-', ' ', $module));
        $indexRoute = $module.'.index';

        if ($action === null || $action === 'index' || $action === 'edit' && $module === 'profil') {
            $this->items[] = ['label' => $current ?? $moduleLabel, 'url' => null];

            return;
        }

        $this->items[] = ['label' => $moduleLabel, 'url' => Route::has($indexRoute) ? route($indexRoute) : null];
        $this->items[] = ['label' => $current ?? (self::ACTIONS[$action] ?? ucfirst($action)), 'url' => null];
    }

    public function render(): View
    {
        return view('components.breadcrumb');
    }
}
