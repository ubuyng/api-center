<?php

namespace App;

use App\Choices;
use App\Category;
use App\Question;
use App\Response;
use App\User;
use App\ResponseItem;
use Illuminate\Database\Eloquent\Model;

class PastProject extends Model
{
    public $fillable = [
        'service_id',
        'sub_category_id',
        'sub_category_name',
        'status',
        'user_id',
        'pro_name',
        'cus_name',
        'project_description',
        'cus_email',
        'phone_number',
        'image',
        'ask_review',
        'started_at',
        'project_time',
        'ended_at',
        'deleted_at',
        'created_at',
        'updated_at',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    /*
    |------------------------------------------------------------------------
    | Query Scopes
    |------------------------------------------------------------------------
    */

    public function ScopeUnDeleted($query)
    {
        return $query->where('deleted_at', null);
    }
    public function ScopeData($query, $data)
    {
        return $query->where($data);
    }
    public function ScopeMyResponse($query, $subCat)
    {
        return $query->where(['sub_category_id' => $subCat, 'user_id' => auth()->user()->id]);
    }
    public function ScopeGetResponse($query, $subCat, $userId)
    {
        return $query->where(['sub_category_id' => $subCat, 'user_id' => $userId]);
    }
    public function scopeProviders($query)
    {
        return $query->where('user_type', 1);
    }
    public function scopeNotClosed($query)
    {
        return $query->where('status', '!=', 3);
    }

    /*
    |------------------------------------------------------------------------
    | Eloquent Relations
    |------------------------------------------------------------------------
    */
    public function users()
    {
        return $this->belongsTo(User::class, 'user_id')->with('profile_with_count', 'responses');
    }


    public function profiles()
    {
        return $this->belongsTo('App\Profile');
    }
    
    public function services()
    {
        return $this->belongsTo('App\Service');
    }

   /* public function questions()
    {
        return $this->belongsToMany(Question::class);
    }

    public function choice()
    {
        return $this->belongsToMany(Choice::class);
    }*/

    public function sub_category()
    {
        return $this->belongsTo(SubCategory::class, 'sub_category_id')->with('questions');
    }
}
