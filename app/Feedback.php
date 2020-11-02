<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

   
}
