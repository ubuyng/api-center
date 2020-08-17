<?php

namespace App;

use App\User;
use App\Subscription;
use Illuminate\Database\Eloquent\Model;

class ProjectFile extends Model
{
    protected $fillable = [
        'project_id',
		'bid_id',
		'pro_id',
		'cus_id',
		'project_name',
		'file_name',
		'file_type',
		'sender_name',
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
