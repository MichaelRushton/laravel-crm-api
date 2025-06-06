<?php

namespace App\Policies\V1;

use App\Models\Customer;
use App\Models\User;

class CustomerPolicy
{
    public function before(User $user, string $ability): ?true
    {
        return $user->isAdministrator() ?: null;
    }

    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Customer $model): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Customer $model): bool
    {
        return true;
    }

    public function delete(User $user, Customer $model): bool
    {
        return true;
    }

    public function restore(User $user, Customer $model): bool
    {
        return true;
    }

    public function forceDelete(User $user, Customer $model): bool
    {
        return false;
    }

    public function enquiries(User $user): bool
    {
        return true;
    }
}
