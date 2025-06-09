<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class CustomerNote extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'note',
    ];

    protected static function booted(): void
    {
        static::creating(function (CustomerNote $note) {
            $note->created_by ??= Auth::user()?->id;
        });
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
