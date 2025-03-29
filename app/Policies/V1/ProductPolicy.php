<?php

namespace App\Policies\V1;

use App\Models\Product;
use App\Models\User;

class ProductPolicy
{
    public function before(User $user, string $ability): ?true
    {
        return $user->isAdministrator() ?: null;
    }

    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Product $model): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Product $model): bool
    {
        return true;
    }

    public function delete(User $user, Product $model): bool
    {
        return true;
    }

    public function restore(User $user, Product $model): bool
    {
        return true;
    }

    public function forceDelete(User $user, Product $model): bool
    {
        return false;
    }
}
