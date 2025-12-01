<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Visit extends Model
{
    use HasFactory;

    protected $fillable = [
        'target_user_id','group_id','scheduled_at','team','report','status','created_by',
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
        'team' => 'array',
    ];

    public function targetUser() { return $this->belongsTo(User::class, 'target_user_id'); }
    public function group() { return $this->belongsTo(Group::class); }
    public function creator() { return $this->belongsTo(User::class, 'created_by'); }
}

