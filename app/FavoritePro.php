<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FavoritePro extends Model
{
    public $fillable = [
        'id',
        'user_id',
        'pro_id',
        'created_at',
        'updated_at',
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

    public function requests()
    {
        return $this->hasManyThrough('projects', 'services', 'sub_category_id', 'sub_category_id');
    }

}
