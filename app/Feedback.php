<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    public $fillable = [
        'user_id',
        'rating',
        'feedback',
        'deleted_at',
        'created_at',
        'updated_at',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

   
}
