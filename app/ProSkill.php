<?php

namespace App;

use App\User;
use App\Subscription;
use Illuminate\Database\Eloquent\Model;

class ProSkill extends Model
{
    protected $fillable = [
        'skill_title',
		'skill_type',
		'user_id',
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
    public function state()
    {
        return $this->belongsTo('App\Models\State','licence_state_id','id');
    }
    public function subscription()
    {
        return $this->belongsTo(Subscription::class);
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
    public function ScopeHaveServiceCount($query)
    {
        return $query->where('service_count' , '!=', '');
    }
}
