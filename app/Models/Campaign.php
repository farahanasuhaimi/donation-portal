<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

class Campaign extends Model
{
    use HasFactory;

    protected $fillable = [
        'organizer_user_id',
        'title',
        'description',
        'target_amount',
        'deadline',
        'qr_image',
    ];

    protected $casts = [
        'deadline' => 'date',
        'target_amount' => 'decimal:2',
    ];

    public function donations(): HasMany
    {
        return $this->hasMany(Donation::class);
    }

    public function organizer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'organizer_user_id');
    }

    public function isActive(): bool
    {
        if (! $this->deadline instanceof Carbon) {
            return true;
        }

        return $this->deadline->endOfDay()->isFuture();
    }
}
