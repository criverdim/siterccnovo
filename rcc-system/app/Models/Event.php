<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Schema;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'short_description',
        'start_date',
        'end_date',
        'start_time',
        'end_time',
        'location',
        'address',
        'price',
        'capacity',
        'tickets_sold',
        'status',
        'featured_image',
        'folder_image',
        'gallery_images',
        'organizers',
        'schedule',
        'additional_info',
        'is_featured',
        'is_paid',
        'mercado_pago_preference_id',
        'category',
        'min_age',
        'days_count',
        'arrival_info',
        'map_embed_url',
        'parceling_enabled',
        'parceling_max',
        'coupons_enabled',
        'generates_ticket',
        'allows_online_payment',
        'extra_services',
        'terms',
        'rules',
        'is_active',
        'show_on_homepage',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'price' => 'decimal:2',
        'gallery_images' => 'array',
        'organizers' => 'array',
        'schedule' => 'array',
        'additional_info' => 'array',
        'is_featured' => 'boolean',
        'is_paid' => 'boolean',
        'tickets_sold' => 'integer',
        'is_active' => 'boolean',
        'min_age' => 'integer',
        'days_count' => 'integer',
        'parceling_enabled' => 'boolean',
        'parceling_max' => 'integer',
        'coupons_enabled' => 'boolean',
        'generates_ticket' => 'boolean',
        'allows_online_payment' => 'boolean',
        'extra_services' => 'array',
    ];

    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class);
    }

    public function participations(): HasMany
    {
        return $this->hasMany(EventParticipation::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function isActive(): bool
    {
        $statusActive = ($this->status ?? null) === 'active';
        $flagActive = (bool) ($this->is_active ?? false);
        $future = $this->start_date instanceof \Illuminate\Support\Carbon
            ? $this->start_date->isFuture()
            : \Carbon\Carbon::parse($this->start_date)->isFuture();

        return ($statusActive || $flagActive) && $future;
    }

    public function scopeActive($query)
    {
        return $query->where(function ($q) {
            $applied = false;
            if (Schema::hasColumn('events', 'status')) {
                $q->where('status', 'active');
                $applied = true;
            }
            if (Schema::hasColumn('events', 'is_active')) {
                if ($applied) {
                    $q->orWhere('is_active', true);
                } else {
                    $q->where('is_active', true);
                }
                $applied = true;
            }
            if (! $applied) {
                $q->whereRaw('1=1');
            }
        });
    }

    public function isSoldOut(): bool
    {
        if ($this->capacity === null) {
            return false;
        }

        return (int) $this->tickets_sold >= (int) $this->capacity;
    }

    public function availableTickets(): int
    {
        if ($this->capacity === null) {
            return PHP_INT_MAX;
        }

        return max(0, ((int) $this->capacity) - ((int) $this->tickets_sold));
    }

    public function getStatusLabel(): string
    {
        return match ($this->status) {
            'active' => 'Ativo',
            'inactive' => 'Inativo',
            'cancelled' => 'Cancelado',
            'sold_out' => 'Esgotado',
            default => 'Desconhecido',
        };
    }

    public function getStatusColor(): string
    {
        return match ($this->status) {
            'active' => 'success',
            'inactive' => 'warning',
            'cancelled' => 'danger',
            'sold_out' => 'gray',
            default => 'gray',
        };
    }
}
