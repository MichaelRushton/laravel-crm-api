<?php

namespace App\Events\Customer;

use App\Contracts\CanSaveRevision;
use App\Models\Customer;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CustomerSaved implements CanSaveRevision
{
    public function __construct(public readonly Customer $customer) {}

    public function revisions(): HasMany
    {
        return $this->customer->revisions();
    }
}
