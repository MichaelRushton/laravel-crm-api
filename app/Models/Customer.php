<?php

namespace App\Models;

use App\Events\Customer\CustomerSaved;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
    ];

    protected $dispatchesEvents = [
        'saved' => CustomerSaved::class,
    ];

    public function revisions(): HasMany
    {
        return $this->hasMany(CustomerRevision::class);
    }

    public function scopeFilter(Builder $query, array $filter): void
    {

        foreach ($filter as $key => $value) {

            switch ($key) {

                case 'first_name':
                case 'last_name':
                case 'email':

                    if ($value) {
                        $query->where($key, $value);
                    }

                    break;

            }

        }

    }

    public function scopeSort(Builder $query, array $sort): void
    {

        foreach ($sort as $key => $value) {

            $direction = match (strtolower($value)) {
                'desc' => 'desc',
                default => 'asc',
            };

            switch ($key) {

                case 'first_name':
                case 'last_name':
                case 'email':
                    $query->orderBy($key, $direction);
                    break;

            }

        }

    }
}
