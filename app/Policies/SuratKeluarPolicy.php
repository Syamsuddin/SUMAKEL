<?php

namespace App\Policies;

use App\Models\SuratKeluar;
use App\Models\User;

class SuratKeluarPolicy
{
    public function before(User $user): ?bool
    {
        return $user->hasRole('superadmin') ? true : null;
    }

    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['admin_tu', 'pimpinan', 'staf']);
    }

    public function view(User $user, SuratKeluar $suratKeluar): bool
    {
        return $user->opd_id === $suratKeluar->opd_id;
    }

    public function create(User $user): bool
    {
        return $user->hasRole('admin_tu');
    }

    public function update(User $user, SuratKeluar $suratKeluar): bool
    {
        return $user->hasRole('admin_tu')
            && $user->opd_id === $suratKeluar->opd_id
            && $suratKeluar->status === 'draft';
    }

    public function delete(User $user, SuratKeluar $suratKeluar): bool
    {
        return $user->hasRole('admin_tu')
            && $user->opd_id === $suratKeluar->opd_id
            && $suratKeluar->status === 'draft';
    }

    public function terbitkan(User $user, SuratKeluar $suratKeluar): bool
    {
        return $user->hasRole('admin_tu')
            && $user->opd_id === $suratKeluar->opd_id
            && $suratKeluar->status === 'draft'
            && $suratKeluar->lampirans()->exists();
    }

    public function arsipkan(User $user, SuratKeluar $suratKeluar): bool
    {
        return $user->hasRole('admin_tu')
            && $user->opd_id === $suratKeluar->opd_id
            && $suratKeluar->status === 'terbit';
    }
}
