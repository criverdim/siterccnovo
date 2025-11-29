<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'category',
        'photos',
        'location',
        'arrival_info',
        'map_embed_url',
        'start_date',
        'end_date',
        'start_time',
        'end_time',
        'days_count',
        'min_age',
        'schedule',
        'is_paid',
        'price',
        'parceling_enabled',
        'parceling_max',
        'coupons_enabled',
        'extra_services',
        'terms',
        'rules',
        'has_coffee',
        'has_lunch',
        'generates_ticket',
        'allows_online_payment',
        'capacity',
        'show_on_homepage',
        'is_active',
    ];

    protected $casts = [
        'photos' => 'array',
        'schedule' => 'array',
        'extra_services' => 'array',
        'start_date' => 'date',
        'end_date' => 'date',
        'start_time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i',
        'is_paid' => 'boolean',
        'parceling_enabled' => 'boolean',
        'coupons_enabled' => 'boolean',
        'has_coffee' => 'boolean',
        'has_lunch' => 'boolean',
        'generates_ticket' => 'boolean',
        'allows_online_payment' => 'boolean',
        'show_on_homepage' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function participations()
    {
        return $this->hasMany(EventParticipation::class);
    }
}
