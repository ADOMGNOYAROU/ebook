<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ebook extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'slug', 'description', 'author', 'file_path', 
        'cover_path', 'file_size', 'pages', 'language', 'is_free', 'category_id'
    ];

    protected $casts = [
        'is_free' => 'boolean',
        'file_size' => 'integer',
        'pages' => 'integer',
        'downloads_count' => 'integer',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function downloads()
    {
        return $this->hasMany(Download::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function scopeFree($query)
    {
        return $query->where('is_free', true);
    }

    public function scopeInCategory($query, $categoryId)
    {
        return $query->where('category_id', $categoryId);
    }

    public function incrementDownloads()
    {
        $this->increment('downloads_count');
    }

    public function getFileSizeAttribute()
    {
        $size = $this->attributes['file_size'];
        if ($size >= 1048576) {
            return round($size / 1048576, 1) . ' GB';
        } elseif ($size >= 1024) {
            return round($size / 1024, 1) . ' MB';
        }
        return $size . ' KB';
    }
}