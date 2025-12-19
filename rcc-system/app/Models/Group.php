<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Group extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'responsible',
        'responsible_phone',
        'responsible_whatsapp',
        'responsible_email',
        'weekday',
        'color_hex',
        'time',
        'address',
        'photos',
        'cover_photo',
        'cover_bg_color',
        'cover_object_position',
    ];

    protected $casts = [
        'photos' => 'array',
        'time' => 'datetime:H:i',
    ];

    public function setResponsibleWhatsappAttribute($value): void
    {
        $digits = preg_replace('/\D+/', '', (string) $value);
        $this->attributes['responsible_whatsapp'] = $digits;
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function members()
    {
        return $this->belongsToMany(User::class, 'user_groups')->withTimestamps();
    }

    public function attendance()
    {
        return $this->hasMany(GroupAttendance::class);
    }

    public function draws()
    {
        return $this->hasMany(GroupDraw::class);
    }

    public function getColorHexAttribute($value): ?string
    {
        if (! $value) {
            return null;
        }
        $v = trim((string) $value);
        if ($v && $v[0] !== '#') {
            $v = '#'.$v;
        }

        return substr($v, 0, 9);
    }

    public static function colorMap(): array
    {
        return Cache::remember('groups:color_map', 600, function () {
            return self::query()->pluck('color_hex', 'id')
                ->map(function ($color) {
                    if (! $color) {
                        return null;
                    }
                    $v = trim((string) $color);

                    return ($v[0] === '#') ? substr($v, 0, 9) : ('#'.substr($v, 0, 8));
                })->toArray();
        });
    }

    protected static function booted(): void
    {
        foreach (['saved', 'deleted'] as $evt) {
            static::{$evt}(function () {
                Cache::forget('groups:color_map');
            });
        }
    }
}
