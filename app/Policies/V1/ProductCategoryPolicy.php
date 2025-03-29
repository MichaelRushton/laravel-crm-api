<?php

namespace App\Policies\V1;

use App\Models\ProductCategory;
use App\Models\User;

class ProductCategoryPolicy
{
    public function before(User $user, string $ability): ?true
    {
        return $user->isAdministrator() ?: null;
    }

    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, ProductCategory $model): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, ProductCategory $model): bool
    {
        return true;
    }

    public function delete(User $user, ProductCategory $model): bool
    {
        return true;
    }

    public function restore(User $user, ProductCategory $model): bool
    {
        return true;
    }

    public function forceDelete(User $user, ProductCategory $model): bool
    {
        return false;
    }
}
