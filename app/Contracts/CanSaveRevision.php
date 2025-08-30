<?php

namespace App\Contracts;

use Illuminate\Database\Eloquent\Relations\HasMany;

interface CanSaveRevision
{
    public function revisions(): HasMany;
}
