<?php

namespace App;

use App\Rating;
use App\Profile;
use App\Response;
use App\Project;
use App\Service;
use App\ProjectBid;
use App\FavoritePro;
use App\BankDetail;
use App\BusinessHour;
use App\ResponseItem;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;

use Illuminate\Support\Facades\Cache;

class User extends Model implements AuthenticatableContract
{   
    use Notifiable;
    public function routeNotificationForMail()
    {
        return $this->email;
    }


    use Authenticatable;


    protected $fillable = [
        'uuid',
        'first_name',
        'last_name',
        'email',
        'user_token',
        'avatar_type',
        'avatar_location',
        'image',
        'password',
        'number',
        'profile_approved',
        'licence_approved',
        'user_slug',
        'password_changed_at',
        'active',
        'confirmation_code',
        'confirmed',
        'timezone',
        'user_role',
        'last_login_at',
        'last_login_ip',
        'dob',
        'address',
        'address2',
        'city',
        'state',
        'zip',
        'lat',
        'lng',
        'rating',
        'referral_code',
        'enable_text_message',
        'verify_code',
        'verify_image',
        'verify_confirm',
        'number_verify_code',
        'email_verify_code',
        'facebook_auth',
        'google_token',
        'accept_terms',
    ];

    /*
    |--------------------------------------------------------------------------
    | Eloquent Relations
    |--------------------------------------------------------------------------
    */
    public function response_items()
    {
        return $this->hasMany(ResponseItem::class)->with('sub_category')->unDeleted()->orderBy('id', 'desc');
    }
    public function projects()
    {
        return $this->hasMany(Project::class)->with('sub_category')->unDeleted()->orderBy('id', 'desc');
    }
    public function services()
    {
        return $this->hasMany(Service::class)->with('sub_category')->unDeleted()->orderBy('id', 'desc');
    }
    public function projectbids()
    {
        return $this->hasMany(ProjectBid::class)->with('sub_category')->unDeleted()->orderBy('id', 'desc');
    }
    public function favoritePros()
    {
        return $this->hasMany(FavoritePro::class)->with('sub_category')->unDeleted()->orderBy('id', 'desc');
    }
    public function response_complete_items()
    {
        return $this->hasMany(ResponseItem::class)->with('sub_category')->unDeleted()->where('status', 3)->orderBy('id', 'desc');
    }
    public function responses()
    {
        return $this->hasMany(Response::class);
    }
    public function profile()
    {
        return $this->hasOne(Profile::class);
    }
    public function profile_with_count()
    {
        return $this->hasOne(Profile::class)->haveServiceCount();
    }
    public function reviews()
    {
        return $this->hasMany(Rating::class, 'author_id')->unDeleted();
    }
    public function business_hours()
    {
        return $this->hasMany(BusinessHour::class);
    }
    public function bank_detail()
    {
        return $this->hasOne(BankDetail::class);
    }
    public function rating_to_me()
    {
        return $this->hasMany(Rating::class, 'ratingable_id')->unDeleted();
    }

    /*
    |--------------------------------------------------------------------------
    | Accessor
    |--------------------------------------------------------------------------
    */
    public function getPhotoUrlAttribute()
    {
        return asset(env('FRONTEND_IMAGES_PATH') . $this->image);
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
    public function ScopeNoVerification($query)
    {
        return $query->where('verify_confirm', 0);
    }
    public function ScopePendingVerification($query)
    {
        return $query->where('verify_confirm',1);
    }
    public function ScopeVerified($query)
    {
        return $query->where('verify_confirm',2);
    }
    public function ScopeData($query, $data)
    {
        return $query->where($data);
    }
    public function ScopeAdmins($query)
    {
        return $query->whereHas('roles' , function($q){$q->where('name', 'administrator'); });
    }
    // public function isOnline()
    // {
    //     return \Cache::has('user-is-online-' . $this->id);
    // }
}
