<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserPhoto extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'file_path',
        'file_name',
        'file_size',
        'mime_type',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'file_size' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function getFullUrlAttribute(): string
    {
        return asset('storage/'.$this->file_path);
    }

    public function getThumbnailUrlAttribute(): string
    {
        $pathInfo = pathinfo($this->file_path);
        $thumbnailPath = $pathInfo['dirname'].'/thumbs/'.$pathInfo['basename'];

        return asset('storage/'.$thumbnailPath);
    }
}
