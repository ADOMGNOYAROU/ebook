<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Subscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'tenant_id',
        'plan_id',
        'stripe_subscription_id',
        'status',
        'trial_ends_at',
        'ends_at',
        'current_period_start',
        'current_period_end',
        'amount',
        'currency',
        'metadata',
    ];

    protected $casts = [
        'trial_ends_at' => 'datetime',
        'ends_at' => 'datetime',
        'current_period_start' => 'datetime',
        'current_period_end' => 'datetime',
        'amount' => 'decimal:2',
        'metadata' => 'array',
    ];

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function plan(): BelongsTo
    {
        return $this->belongsTo(Plan::class);
    }

    public function isActive(): bool
    {
        return $this->status === 'active' || $this->isOnTrial();
    }

    public function isOnTrial(): bool
    {
        return $this->status === 'trialing' || 
               ($this->trial_ends_at && $this->trial_ends_at->isFuture());
    }

    public function isCanceled(): bool
    {
        return $this->status === 'canceled';
    }

    public function isPastDue(): bool
    {
        return $this->status === 'past_due';
    }

    public function willCancelAtPeriodEnd(): bool
    {
        return isset($this->metadata['cancel_at_period_end']) && 
               $this->metadata['cancel_at_period_end'] === true;
    }

    public function getDaysUntilEnd(): int
    {
        $endDate = $this->ends_at ?? $this->current_period_end;
        
        if (!$endDate) {
            return 0;
        }

        return now()->diffInDays($endDate, false);
    }

    public function getFormattedAmountAttribute(): string
    {
        return '€' . number_format($this->amount, 2);
    }

    public function getFormattedStatusAttribute(): string
    {
        return match($this->status) {
            'active' => 'Actif',
            'trialing' => 'Essai gratuit',
            'canceled' => 'Annulé',
            'past_due' => 'En retard',
            'unpaid' => 'Impayé',
            default => ucfirst($this->status)
        };
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeForTenant($query, $tenantId)
    {
        return $query->where('tenant_id', $tenantId);
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeExpiringSoon($query, $days = 7)
    {
        return $query->where('current_period_end', '<=', now()->addDays($days))
                    ->where('current_period_end', '>', now());
    }
}
