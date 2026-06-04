<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    public function before(User $user): ?bool
    {
        return $user->hasRole('superadmin') ? true : null;
    }

    public function viewAny(User $user): bool
    {
        return $user->hasRole('admin_tu');
    }

    public function view(User $user, User $model): bool
    {
        return $user->hasRole('admin_tu') && $user->opd_id === $model->opd_id;
    }

    public function create(User $user): bool
    {
        return $user->hasRole('admin_tu');
    }

    public function update(User $user, User $model): bool
    {
        return $user->hasRole('admin_tu') && $user->opd_id === $model->opd_id;
    }

    public function delete(User $user, User $model): bool
    {
        if ($user->id === $model->id) {
            return false;
        }

        return $user->hasRole('admin_tu') && $user->opd_id === $model->opd_id;
    }
}
