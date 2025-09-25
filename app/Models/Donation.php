<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Donation extends Model
{
    protected $fillable = [
        'title',
        'description',
        'target_amount',
        'current_amount',
        'image',
        'status',
        'end_date',
    ];

    protected $casts = [
        'target_amount' => 'decimal:2',
        'current_amount' => 'decimal:2',
        'end_date' => 'datetime',
    ];

    public function transactions(): HasMany
    {
        return $this->hasMany(DonationTransaction::class);
    }

    public function getProgressPercentageAttribute(): float
    {
        if ($this->target_amount <= 0) {
            return 0;
        }
        return min(($this->current_amount / $this->target_amount) * 100, 100);
    }

    public function getCompletedTransactionsAttribute()
    {
        return $this->transactions()->where('status', 'completed');
    }

    public function getDonorsCountAttribute(): int
    {
        return $this->transactions()->where('status', 'completed')->count();
    }

    public function getRemainingDaysAttribute(): ?int
    {
        if (!$this->end_date) {
            return null;
        }

        if ($this->end_date->isPast()) {
            return 0;
        }

        // Calculate days between now and end_date
        $now = now()->startOfDay();
        $endDate = $this->end_date->startOfDay();
        
        return $now->diffInDays($endDate) ?: 1; // If same day, show 1 day
    }

    public function getDaysPassedSinceEndAttribute(): int
    {
        if (!$this->end_date || !$this->end_date->isPast()) {
            return 0;
        }

        $now = now()->startOfDay();
        $endDate = $this->end_date->startOfDay();
        
        return $endDate->diffInDays($now);
    }
}