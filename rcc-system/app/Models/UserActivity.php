<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserActivity extends Model
{
    use HasFactory;

    const ACTIVITY_TYPES = [
        'login',
        'logout',
        'profile_updated',
        'photo_uploaded',
        'message_sent',
        'event_participation',
        'group_attendance',
        'status_changed',
        'password_changed',
    ];

    protected $fillable = [
        'user_id',
        'activity_type',
        'details',
        'ip_address',
        'created_at',
    ];

    protected $casts = [
        'details' => 'array',
        'created_at' => 'datetime',
    ];

    public $timestamps = false;

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopeByType($query, string $type)
    {
        return $query->where('activity_type', $type);
    }

    public function scopeRecent($query, int $days = 30)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }

    public function getActivityDescriptionAttribute(): string
    {
        return match ($this->activity_type) {
            'login' => 'Realizou login no sistema',
            'logout' => 'Realizou logout do sistema',
            'profile_updated' => 'Atualizou seu perfil',
            'photo_uploaded' => 'Envio de foto de perfil',
            'message_sent' => 'Mensagem enviada',
            'event_participation' => 'Participou de um evento',
            'group_attendance' => 'Presença em grupo',
            'status_changed' => 'Status alterado',
            'password_changed' => 'Senha alterada',
            default => 'Atividade realizada',
        };
    }

    public function getActivityIconAttribute(): string
    {
        return match ($this->activity_type) {
            'login' => 'heroicon-o-arrow-right-on-rectangle',
            'logout' => 'heroicon-o-arrow-left-on-rectangle',
            'profile_updated' => 'heroicon-o-user-circle',
            'photo_uploaded' => 'heroicon-o-camera',
            'message_sent' => 'heroicon-o-envelope',
            'event_participation' => 'heroicon-o-calendar',
            'group_attendance' => 'heroicon-o-user-group',
            'status_changed' => 'heroicon-o-arrow-path',
            'password_changed' => 'heroicon-o-key',
            default => 'heroicon-o-clock',
        };
    }
}
