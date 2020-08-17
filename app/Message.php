<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    public $fillable = [
        'bid_id',
        'project_id',
        'message',
        'is_pro_seen',
        'sender_id',
        'receiver_id',
        'is_cus_seen',
        'typing',
        'deleted_by_customer',
        'deleted_by_pro',
        'created_at',
        'updated_at',
    ];
}
