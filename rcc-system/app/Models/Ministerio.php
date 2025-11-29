<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ministerio extends Model
{
    protected $table = 'ministries';

    protected $fillable = [
        'name',
        'description',
        'is_active',
    ];
}
