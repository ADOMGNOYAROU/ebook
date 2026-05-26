<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Plan extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'price',
        'billing_cycle',
        'features',
        'max_ebooks',
        'max_users',
        'storage_mb',
        'has_custom_domain',
        'has_api',
        'has_analytics',
        'has_mobile_app',
        'is_active',
        'stripe_price_id',
    ];

    protected $casts = [
        'features' => 'array',
        'price' => 'decimal:2',
        'has_custom_domain' => 'boolean',
        'has_api' => 'boolean',
        'has_analytics' => 'boolean',
        'has_mobile_app' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class);
    }

    public function activeSubscriptions(): HasMany
    {
        return $this->subscriptions()->where('status', 'active');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByBillingCycle($query, $cycle)
    {
        return $query->where('billing_cycle', $cycle);
    }

    public function getFormattedPriceAttribute(): string
    {
        return '€' . number_format($this->price, 2);
    }

    public function getFormattedBillingCycleAttribute(): string
    {
        return match($this->billing_cycle) {
            'monthly' => 'par mois',
            'yearly' => 'par an',
            default => $this->billing_cycle
        };
    }

    public function getMaxEbooksDisplayAttribute(): string
    {
        return $this->max_ebooks === -1 ? 'Illimité' : $this->max_ebooks;
    }

    public function getMaxUsersDisplayAttribute(): string
    {
        return $this->max_users === -1 ? 'Illimité' : $this->max_users;
    }

    public function getStorageDisplayAttribute(): string
    {
        if ($this->storage_mb === -1) {
            return 'Illimité';
        }

        if ($this->storage_mb >= 1024) {
            return round($this->storage_mb / 1024, 1) . ' Go';
        }

        return $this->storage_mb . ' Mo';
    }
}
