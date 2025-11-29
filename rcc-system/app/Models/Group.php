<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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

    public function attendance()
    {
        return $this->hasMany(GroupAttendance::class);
    }

    public function draws()
    {
        return $this->hasMany(GroupDraw::class);
    }
}
