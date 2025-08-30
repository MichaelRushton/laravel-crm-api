<?php

namespace App\Events\User;

use App\Contracts\CanSaveRevision;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\HasMany;

class UserSaved implements CanSaveRevision
{
    public function __construct(public readonly User $user) {}

    public function revisions(): HasMany
    {
        return $this->user->revisions();
    }
}
