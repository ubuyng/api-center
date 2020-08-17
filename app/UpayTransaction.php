<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class UpayTransaction extends Model
{
    protected $fillable = [
    	'user_id',
    	'cus_id',
    	'txref',
    	'bid_id',
    	'project_id',
    	'amount',
    	'status',
    	'updated_at',
    	'updated_at',
    	'deleted_at',
    ];

    /*
    |--------------------------------------------------------------------------
    | Accessor
    |--------------------------------------------------------------------------
    */


    /*
    |--------------------------------------------------------------------------
    | Query Scopes
    |--------------------------------------------------------------------------
    */
 
}
