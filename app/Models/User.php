<?php

namespace App\Models;

use App\Enums\UserRole;
use App\Events\User\UserSaved;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use Notifiable;
    use SoftDeletes;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'role' => UserRole::class,
        ];
    }

    protected $dispatchesEvents = [
        'saved' => UserSaved::class,
    ];

    public function isAdministrator(): bool
    {
        return $this->role === UserRole::Administrator;
    }

    public function revisions(): HasMany
    {
        return $this->hasMany(UserRevision::class);
    }

    public function scopeFilter(Builder $query, array $filter): void
    {

        foreach ($filter as $key => $value) {

            switch ($key) {

                case 'first_name':
                case 'last_name':
                case 'email':
                case 'role':

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
