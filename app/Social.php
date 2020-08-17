<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Social extends Model
{
    protected $fillable = [
    	'name',
    	'link',
    	'status',
    ];

    /*
    |--------------------------------------------------------------------------
    | Query Scopes
    |--------------------------------------------------------------------------
    */
    public function ScopeName($query, $name)
    {
        return $query->whereName($name);
    }
    public function ScopeData($query, $data)
    {
        return $query->where($data);
    }
    public function ScopeActive($query)
    {
        return $query->whereStatus(1);
    }
}
