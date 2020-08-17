<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Typing extends Model
{
    public $fillable = [
        'sender_id',
        'receiver_id',
        'check_status',
        'created_at',
        'updated_at',
    ];
}
