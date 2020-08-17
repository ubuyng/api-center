<?php

namespace App;

use App\Models\Auth\User;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
    	'user_id',
    	'provider_id',
    	'reference_id',
        'txn_status',
        'email',
        'amount',
        'ip_address',
    	'customer_code',
    	'status',
    	'deleted_at'
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
    public function ScopePaidToMe($query)
    {
        return $query->whereProviderId(auth()->user()->id);
    }
}
