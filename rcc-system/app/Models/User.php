<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;

class User extends Authenticatable implements FilamentUser
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'whatsapp',
        'birth_date',
        'cpf',
        'cep',
        'address',
        'number',
        'complement',
        'district',
        'city',
        'state',
        'gender',
        'group_id',
        'is_servo',
        'profile_completed_at',
        'consent_at',
        'status',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'birth_date' => 'date',
            'profile_completed_at' => 'datetime',
            'consent_at' => 'datetime',
            'is_servo' => 'boolean',
        ];
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return ($this->status === 'active') && (bool) $this->is_servo;
    }

    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    public function eventParticipations()
    {
        return $this->hasMany(EventParticipation::class);
    }

    public function groupAttendance()
    {
        return $this->hasMany(GroupAttendance::class);
    }

    public function groupDraws()
    {
        return $this->hasMany(GroupDraw::class);
    }

    public function waMessages()
    {
        return $this->hasMany(WaMessage::class);
    }

    public function ministries()
    {
        return $this->belongsToMany(Ministry::class, 'user_ministries')->withTimestamps();
    }
}
