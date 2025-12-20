<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Checkin extends Model
{
    use HasFactory;

    protected $fillable = [
        'ticket_id',
        'validated_by',
        'status',
        'checkin_at',
        'validation_method',
        'notes',
        'additional_data',
    ];

    protected $casts = [
        'checkin_at' => 'datetime',
        'additional_data' => 'array',
    ];

    public function ticket(): BelongsTo
    {
        return $this->belongsTo(Ticket::class);
    }

    public function validator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'validated_by');
    }

    public function isValid(): bool
    {
        return $this->status === 'valid';
    }

    public function isDuplicate(): bool
    {
        return $this->status === 'duplicate';
    }

    public function isInvalid(): bool
    {
        return $this->status === 'invalid';
    }

    public function getStatusLabel(): string
    {
        return match ($this->status) {
            'valid' => 'Válido',
            'invalid' => 'Inválido',
            'duplicate' => 'Duplicado',
            default => 'Desconhecido',
        };
    }

    public function getStatusColor(): string
    {
        return match ($this->status) {
            'valid' => 'success',
            'invalid' => 'danger',
            'duplicate' => 'warning',
            default => 'gray',
        };
    }
}
