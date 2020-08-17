<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Reward extends Model
{
    protected $fillable = [
    	'name',
    	'value',
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
}
