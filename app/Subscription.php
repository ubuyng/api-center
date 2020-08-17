<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    protected $fillable = [
    	'name',
    	'price',
    	'service',
    	'deleted_at',
    ];

    /*
    |--------------------------------------------------------------------------
    | Query Scopes
    |--------------------------------------------------------------------------
    */

    public function ScopeUnDeleted($query)
    {
        return $query->where('deleted_at', null);
    }

    public function ScopeData($query, $data)
    {
        return $query->where($data);
    }

    /*
    |--------------------------------------------------------------------
    | Accessor
    |--------------------------------------------------------------------
    */
    public function getFullNameAttribute()
    {
       return ucwords($this->name);
    }
}
