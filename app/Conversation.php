<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    protected $fillable = [
    	'user_one',
    	'user_two',
    	'status',
    ];
}
