<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'icon'
    ];

    protected $hidden = [
        'user_id',
        'icon',
        'created_at',
        'updated_at',
    ];
}