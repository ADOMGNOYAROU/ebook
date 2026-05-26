<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Tenant extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'subdomain',
        'domain',
        'settings',
        'logo',
        'primary_color',
        'secondary_color',
        'is_active',
        'trial_ends_at',
    ];

    protected $casts = [
        'settings' => 'array',
        'trial_ends_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'tenant_users')
            ->withPivot('role', 'joined_at')
            ->withTimestamps();
    }

    public function subscription(): HasOne
    {
        return $this->hasOne(Subscription::class)->latest();
    }

    public function ebooks(): HasMany
    {
        return $this->hasMany(Ebook::class);
    }

    public function categories(): HasMany
    {
        return $this->hasMany(Category::class);
    }

    public function currentPlan()
    {
        return $this->subscription?->plan;
    }

    public function isOnTrial(): bool
    {
        return $this->trial_ends_at && $this->trial_ends_at->isFuture();
    }

    public function isSubscribed(): bool
    {
        return $this->subscription && $this->subscription->isActive();
    }

    public function canCreateMoreEbooks(): bool
    {
        if (!$this->currentPlan()) {
            return false;
        }

        $maxEbooks = $this->currentPlan()->max_ebooks;
        if ($maxEbooks === -1) { // Unlimited
            return true;
        }

        return $this->ebooks()->count() < $maxEbooks;
    }

    public function canAddMoreUsers(): bool
    {
        if (!$this->currentPlan()) {
            return false;
        }

        $maxUsers = $this->currentPlan()->max_users;
        if ($maxUsers === -1) { // Unlimited
            return true;
        }

        return $this->users()->count() < $maxUsers;
    }

    public function getStorageUsed(): int
    {
        // Calculer l'espace utilisé en MB
        $totalSize = 0;
        
        foreach ($this->ebooks as $ebook) {
            if ($ebook->file_size) {
                $totalSize += $ebook->file_size;
            }
        }

        // Convertir en MB
        return round($totalSize / (1024 * 1024), 2);
    }

    public function canUploadMoreStorage(int $fileSizeMb): bool
    {
        if (!$this->currentPlan()) {
            return false;
        }

        $maxStorage = $this->currentPlan()->storage_mb;
        if ($maxStorage === -1) { // Unlimited
            return true;
        }

        $usedStorage = $this->getStorageUsed();
        return ($usedStorage + $fileSizeMb) <= $maxStorage;
    }

    public function hasFeature(string $feature): bool
    {
        if (!$this->currentPlan()) {
            return false;
        }

        $features = $this->currentPlan()->features;
        return isset($features[$feature]) && $features[$feature] === true;
    }

    public function getDomainAttribute(): string
    {
        if ($this->domain) {
            return $this->domain;
        }

        return $this->subdomain . '.' . config('app.domain');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeBySubdomain($query, $subdomain)
    {
        return $query->where('subdomain', $subdomain);
    }
}
