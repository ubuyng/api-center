<?php

namespace App;

use App\User;
use App\Subscription;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    protected $fillable = [
        'business_name',
		'number_of_empolyees',
		'website',
		'founded_year',
		'about_profile',
		'profile_slug',
		'licence_state_id',
		'licence_type',
		'licence_number',
		'user_id',
		'pro_address',
		'pro_state',
		'pro_city',
		'lat',
		'lng',
		'distance',
		'cover_photo',
		'available',
		'facebook_url',
		'twitter_url',
		'linkedin_url',
		'instagram_url',
		'website',
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
    public function Scopelat($query)
    {
        return $query->where('lat' , '!=', null);
    }
    public function Scopelng($query)
    {
        return $query->where('lng' , '!=', null);
    }

    public function requests()
    {
        return $this->hasManyThrough('projects', 'services', 'sub_category_id', 'sub_category_id');
    }
}
