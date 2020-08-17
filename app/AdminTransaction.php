<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AdminTransaction extends Model
{
    protected $fillable = [
    	'user_id',
    	'provider_id',
    	'transfer_code',
    	'recipient',
    	'amount',
    	'txn_status',
    	'status',
    	'deleted_at',
    ];

    /*
    |--------------------------------------------------------------------------
    | Eloquent Relations
    |--------------------------------------------------------------------------
    */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function provider()
    {
        return $this->belongsTo(User::class, 'provider_id')->with('bank_detail');
    }

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
    public function ScopeMyTransactions($query)
    {
        return $query->whereUserId(auth()->user()->id);
    }
}
