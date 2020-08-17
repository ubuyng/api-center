<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class BusinessHour extends Model
{
    protected $fillable = [
    	'user_id',
    	'day_id',
    	'open_time',
    	'close_time',
    ];

    /*
    |--------------------------------------------------------------------------
    | Accessor
    |--------------------------------------------------------------------------
    */
    public function getOpenTimeFullAttribute()
    {
        return $this->open_time ? Carbon::parse($this->open_time)->format('h:i A') : 'NA';
    }
    public function getCloseTimeFullAttribute()
    {
        return $this->close_time ? Carbon::parse($this->close_time)->format('h:i A') : 'NA';
    }

    /*
    |--------------------------------------------------------------------------
    | Query Scopes
    |--------------------------------------------------------------------------
    */
    public function ScopeCheckDay($query, $dayId)
    {
        return $query->where(['day_id' => $dayId, 'user_id' => auth()->user()->id]);
    }
    public function ScopeData($query, $data)
    {
        return $query->where($data);
    }
}
