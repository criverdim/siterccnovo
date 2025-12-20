<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'event_id',
        'payment_id',
        'qr_code',
        'ticket_code',
        'status',
        'used_at',
        'pdf_path',
        'additional_data',
    ];

    protected $casts = [
        'used_at' => 'datetime',
        'additional_data' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function payment(): BelongsTo
    {
        return $this->belongsTo(Payment::class);
    }

    public function checkin(): HasOne
    {
        return $this->hasOne(Checkin::class);
    }

    public function isUsed(): bool
    {
        return $this->status === 'used' || $this->used_at !== null;
    }

    public function isActive(): bool
    {
        return $this->status === 'active' && ! $this->isUsed();
    }

    public function markAsUsed(): void
    {
        $this->update([
            'status' => 'used',
            'used_at' => now(),
        ]);
    }

    public function getQrCodeUrl(): string
    {
        return route('events.ticket.qr', ['ticket' => $this->id]);
    }

    public function getDownloadUrl(): string
    {
        return route('events.ticket.download', ['ticket' => $this->id]);
    }

    public function getStatusLabel(): string
    {
        return match ($this->status) {
            'active' => 'Ativo',
            'used' => 'Utilizado',
            'cancelled' => 'Cancelado',
            default => 'Desconhecido',
        };
    }

    public function getStatusColor(): string
    {
        return match ($this->status) {
            'active' => 'success',
            'used' => 'gray',
            'cancelled' => 'danger',
            default => 'gray',
        };
    }
}
