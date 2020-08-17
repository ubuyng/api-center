<?php

namespace App;

use App\User;
use Illuminate\Database\Eloquent\Model;

class BankDetail extends Model
{
    protected $fillable = [
    	'user_id',
    	'name',
    	'bank_code',
        'bank_name',
    	'account_number',
    	'sub_account_id',
    	'created_at',
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
    |--------------------------------------------------------------------------
    | Eloquent Relations
    |--------------------------------------------------------------------------
    */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
