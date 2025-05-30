<?php

namespace App\Policies\V1;

use App\Models\CustomerNote;
use App\Models\User;

class CustomerNotePolicy
{
    public function before(User $user, string $ability): ?true
    {
        return $user->isAdministrator() ?: null;
    }

    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, CustomerNote $model): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, CustomerNote $model): bool
    {
        return true;
    }

    public function delete(User $user, CustomerNote $model): bool
    {
        return true;
    }

    public function restore(User $user, CustomerNote $model): bool
    {
        return true;
    }

    public function forceDelete(User $user, CustomerNote $model): bool
    {
        return false;
    }
}
