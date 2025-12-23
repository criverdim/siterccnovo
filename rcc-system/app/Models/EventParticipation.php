<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventParticipation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'event_id',
        'quantity',
        'payment_status',
        'payment_method',
        'mp_payment_id',
        'mp_payload_raw',
        'ticket_uuid',
        'ticket_qr_hash',
        'checked_in_at',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'mp_payload_raw' => 'array',
        'checked_in_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}
