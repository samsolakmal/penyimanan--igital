<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['super_admin', 'admin']);
    }

    public function view(User $user, User $model): bool
    {
        if ($user->role === 'super_admin') {
            return true;
        }
        
        if ($user->role === 'admin') {
            return $model->role === 'user';
        }
        
        return false;
    }

    public function create(User $user): bool
    {
        return in_array($user->role, ['super_admin', 'admin']);
    }

    public function update(User $user, User $model): bool
    {
        if ($user->id === $model->id && $user->role !== 'super_admin') {
            return false;
        }

        if ($user->role === 'super_admin') {
            return true;
        }

        if ($user->role === 'admin') {
            return $model->role === 'user';
        }

        return false;
    }

    public function delete(User $user, User $model): bool
    {
        if ($user->id === $model->id) {
            return false;
        }

        if ($user->role === 'super_admin') {
            return true;
        }

        if ($user->role === 'admin') {
            return $model->role === 'user';
        }

        return false;
    }

    public function resetPassword(User $user, User $model): bool
    {
        if ($user->id === $model->id && $user->role !== 'super_admin') {
            return false;
        }

        if ($user->role === 'super_admin') {
            return true;
        }

        if ($user->role === 'admin') {
            return $model->role === 'user';
        }

        return false;
    }
}