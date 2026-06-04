<?php

namespace App\Policies;

use App\Models\SuratMasuk;
use App\Models\User;

class SuratMasukPolicy
{
    public function before(User $user): ?bool
    {
        return $user->hasRole('superadmin') ? true : null;
    }

    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['admin_tu', 'pimpinan', 'staf']);
    }

    public function view(User $user, SuratMasuk $suratMasuk): bool
    {
        if ($user->opd_id !== $suratMasuk->opd_id) {
            return false;
        }

        if ($suratMasuk->sifat !== 'rahasia') {
            return true;
        }

        if ($user->hasAnyRole(['admin_tu', 'pimpinan'])) {
            return true;
        }

        return $suratMasuk->disposisis()
            ->where(fn ($q) => $q->where('kepada_user_id', $user->id)->orWhere('dari_user_id', $user->id))
            ->exists();
    }

    public function create(User $user): bool
    {
        return $user->hasRole('admin_tu');
    }

    public function update(User $user, SuratMasuk $suratMasuk): bool
    {
        return $user->hasRole('admin_tu')
            && $user->opd_id === $suratMasuk->opd_id
            && $suratMasuk->status !== 'diarsip';
    }

    public function delete(User $user, SuratMasuk $suratMasuk): bool
    {
        return $user->hasRole('admin_tu')
            && $user->opd_id === $suratMasuk->opd_id
            && $suratMasuk->status === 'baru';
    }

    public function arsipkan(User $user, SuratMasuk $suratMasuk): bool
    {
        return $user->hasRole('admin_tu')
            && $user->opd_id === $suratMasuk->opd_id
            && in_array($suratMasuk->status, ['selesai']);
    }
}
