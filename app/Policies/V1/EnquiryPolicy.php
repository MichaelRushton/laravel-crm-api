<?php

namespace App\Policies\V1;

use App\Models\Enquiry;
use App\Models\User;

class EnquiryPolicy
{
    public function before(User $user, string $ability): ?true
    {
        return $user->isAdministrator() ?: null;
    }

    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Enquiry $model): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Enquiry $model): bool
    {
        return true;
    }

    public function delete(User $user, Enquiry $model): bool
    {
        return true;
    }

    public function restore(User $user, Enquiry $model): bool
    {
        return true;
    }

    public function forceDelete(User $user, Enquiry $model): bool
    {
        return false;
    }
}
