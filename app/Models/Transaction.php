<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'name',
        'user_id',
        'category_id',
        'type',
        'amount',
        'transaction_date',
        'note'
    ];

    protected $hidden = [
        'user_id',
        'created_at',
        'updated_at'
    ];
}
