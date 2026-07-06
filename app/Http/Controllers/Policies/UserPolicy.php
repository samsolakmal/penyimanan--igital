<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Tentukan apakah user boleh melihat daftar user (akses halaman Manage Users).
     */
    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['super_admin', 'admin']);
    }

    /**
     * Tentukan apakah user boleh melihat detail 1 user spesifik.
     */
    public function view(User $user, User $model): bool
    {
        // Super admin bisa lihat semua user
        if ($user->role === 'super_admin') {
            return true;
        }
        
        // Admin hanya bisa lihat user biasa (bukan admin/super_admin lain)
        if ($user->role === 'admin') {
            return $model->role === 'user';
        }
        
        return false;
    }

    /**
     * Tentukan apakah user boleh membuat user baru.
     */
    public function create(User $user): bool
    {
        return in_array($user->role, ['super_admin', 'admin']);
    }

    /**
     * Tentukan apakah user boleh mengedit user lain.
     */
    public function update(User $user, User $model): bool
    {
        // User tidak boleh edit akunnya sendiri (kecuali super admin)
        if ($user->id === $model->id && $user->role !== 'super_admin') {
            return false;
        }

        // Super admin bisa edit siapa saja
        if ($user->role === 'super_admin') {
            return true;
        }

        // Admin hanya bisa edit user biasa
        if ($user->role === 'admin') {
            return $model->role === 'user';
        }

        return false;
    }

    /**
     * Tentukan apakah user boleh menghapus user lain.
     */
    public function delete(User $user, User $model): bool
    {
        // Tidak boleh hapus diri sendiri (mencegah admin mengunci dirinya sendiri)
        if ($user->id === $model->id) {
            return false;
        }

        // Super admin bisa hapus siapa saja (kecuali dirinya sendiri)
        if ($user->role === 'super_admin') {
            return true;
        }

        // Admin hanya bisa hapus user biasa
        if ($user->role === 'admin') {
            return $model->role === 'user';
        }

        return false;
    }

    /**
     * Tentukan apakah user boleh reset password user lain.
     */
    public function resetPassword(User $user, User $model): bool
    {
        // Tidak boleh reset password sendiri (kecuali super admin)
        if ($user->id === $model->id && $user->role !== 'super_admin') {
            return false;
        }

        // Super admin bisa reset semua
        if ($user->role === 'super_admin') {
            return true;
        }

        // Admin hanya bisa reset password user biasa
        if ($user->role === 'admin') {
            return $model->role === 'user';
        }

        return false;
    }
}