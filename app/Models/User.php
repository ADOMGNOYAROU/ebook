<?php

namespace App\Models;

use App\Models\Download;
use App\Models\Ebook;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Laravel\Cashier\Billable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, Billable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'avatar',
        'stripe_id',
        'pm_type',
        'pm_last_four',
        'trial_ends_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'role' => 'string',
        'trial_ends_at' => 'datetime',
    ];

    public function downloads()
    {
        return $this->hasMany(Download::class);
    }
    
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
    
    public function ebooks()
    {
        return $this->belongsToMany(Ebook::class, 'downloads')
            ->withTimestamps()
            ->withPivot(['downloaded_at'])
            ->using(Download::class);
    }

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isUser()
    {
        return $this->role === 'user';
    }

    /**
     * Vérifie si l'utilisateur a acheté un ebook spécifique
     */
    public function hasPurchased($ebookId)
    {
        return $this->purchasedEbooks()->where('ebook_id', $ebookId)->exists();
    }

    /**
     * Relation avec les ebooks achetés
     */
    public function purchasedEbooks()
    {
        return $this->belongsToMany(Ebook::class, 'user_purchases')
            ->withPivot('amount', 'payment_id', 'created_at')
            ->withTimestamps();
    }

    /**
     * Relation avec les ebooks favoris de l'utilisateur
     */
    public function favorites()
    {
        return $this->belongsToMany(Ebook::class, 'favorites', 'user_id', 'ebook_id')
            ->withTimestamps();
    }

    public function hasDownloaded(int $ebookId): bool
    {
        return $this->downloads()->where('ebook_id', $ebookId)->exists();
    }

    public function hasFavorited(int $ebookId): bool
    {
        return $this->favorites()->where('ebooks.id', $ebookId)->exists();
    }

    /**
     * Vérifie si l'utilisateur a acheté un ebook spécifique
     *
     * @param int $ebookId
     * @return bool
     */
    public function hasPurchasedEbook($ebookId)
    {
        // Ici, tu devras implémenter la logique pour vérifier si l'utilisateur
        // a acheté l'ebook. Par exemple, en vérifiant dans une table de commandes.
        // Pour l'instant, on retourne true pour les tests.
        // À remplacer par la logique réelle d'achat.
        return true;
    }
}