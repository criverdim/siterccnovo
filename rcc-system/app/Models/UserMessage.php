<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserMessage extends Model
{
    use HasFactory;

    const MESSAGE_TYPES = ['email', 'notification'];
    const STATUSES = ['pending', 'sent', 'failed', 'delivered'];

    protected $fillable = [
        'user_id',
        'sent_by',
        'message_type',
        'subject',
        'content',
        'status',
        'sent_at',
    ];

    protected $casts = [
        'sent_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sent_by');
    }

    public function scopeByType($query, string $type)
    {
        return $query->where('message_type', $type);
    }

    public function scopeByStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    public function scopeRecent($query, int $days = 30)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }

    public function markAsSent(): void
    {
        $this->update([
            'status' => 'sent',
            'sent_at' => now(),
        ]);
    }

    public function markAsFailed(): void
    {
        $this->update([
            'status' => 'failed',
        ]);
    }

    public function markAsDelivered(): void
    {
        $this->update([
            'status' => 'delivered',
        ]);
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'pending' => 'warning',
            'sent' => 'info',
            'delivered' => 'success',
            'failed' => 'danger',
            default => 'secondary',
        };
    }

    public function getStatusIconAttribute(): string
    {
        return match($this->status) {
            'pending' => 'heroicon-o-clock',
            'sent' => 'heroicon-o-paper-airplane',
            'delivered' => 'heroicon-o-check-circle',
            'failed' => 'heroicon-o-x-circle',
            default => 'heroicon-o-question-mark-circle',
        };
   