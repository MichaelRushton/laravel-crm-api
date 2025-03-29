<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Enquiry extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'product_id',
    ];

    protected static function booted(): void
    {
        static::creating(function (Enquiry $note) {
            $note->created_by ??= Auth::user()?->id;
        });
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function scopeFilter(Builder $query, array $filter): void
    {

        foreach ($filter as $key => $value) {

            switch ($key) {

                case 'customer_id':
                case 'product_id':

                    if ($value) {
                        $query->where($key, $value);
                    }

                    break;

            }

        }

    }
}
