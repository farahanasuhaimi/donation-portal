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
        'archived_at',
        'archived_by_user_id',
    ];

    protected $casts = [
        'deadline' => 'date',
        'target_amount' => 'decimal:2',
        'archived_at' => 'datetime',
    ];

    public function donations(): HasMany
    {
        return $this->hasMany(Donation::class);
    }

    public function organizer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'organizer_user_id');
    }

    public function archivedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'archived_by_user_id');
    }

    public function isActive(): bool
    {
        if ($this->isArchived()) {
            return false;
        }

        if ($this->isAchieved()) {
            return false;
        }

        if (! $this->deadline instanceof Carbon) {
            return true;
        }

        return $this->deadline->endOfDay()->isFuture();
    }

    public function isAchieved(): bool
    {
        $target = (float) $this->target_amount;
        $raised = (float) ($this->donations_sum_amount ?? $this->donations()->sum('amount'));

        return $target > 0 && $raised >= $target;
    }

    public function isArchived(): bool
    {
        return $this->archived_at !== null;
    }
}
