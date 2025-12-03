<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;

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
        'role',
        'can_access_admin',
        'is_master_admin',
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

    protected $casts = [
        'can_access_admin' => 'boolean',
        'is_master_admin' => 'boolean',
    ];

    protected $appends = [
        'profile_photo_url',
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
            'role' => 'string',
        ];
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return ($this->status === 'active') && ($this->can_access_admin || $this->is_master_admin || $this->role === 'admin');
    }

    public function isMasterAdmin(): bool
    {
        return (bool) $this->is_master_admin;
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

    public function groups()
    {
        return $this->belongsToMany(Group::class, 'user_groups')->withTimestamps();
    }

    public function photos()
    {
        return $this->hasMany(UserPhoto::class);
    }

    public function activities()
    {
        return $this->hasMany(UserActivity::class);
    }

    public function messages()
    {
        return $this->hasMany(UserMessage::class);
    }

    public function sentMessages()
    {
        return $this->hasMany(UserMessage::class, 'sent_by');
    }

    public function activePhoto()
    {
        return $this->hasOne(UserPhoto::class)->where('is_active', true);
    }

    public function getProfilePhotoUrlAttribute(): string
    {
        $activePhoto = $this->activePhoto;
        if ($activePhoto) {
            $pathInfo = pathinfo($activePhoto->file_path);
            $thumb = $pathInfo['dirname'] . '/thumbs/' . $pathInfo['basename'];
            if (Storage::disk('public')->exists($thumb)) {
                return Storage::disk('public')->url($thumb);
            }
            return Storage::disk('public')->url($activePhoto->file_path);
        }
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&color=7F9CF5&background=EBF4FF';
    }
}
