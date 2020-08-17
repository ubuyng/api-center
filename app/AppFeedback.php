<?php

namespace App;

use App\User;
use App\Subscription;
use Illuminate\Database\Eloquent\Model;

class AppFeedback extends Model
{
    protected $fillable = [
        'user_id',
        'full_name',
        'email',
        'number',
        'message',
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
    public function project()
    {
        return $this->belongsTo('App\Project','project_id','id');
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
