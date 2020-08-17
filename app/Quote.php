<?php

namespace App;

use App\Choices;
use App\Category;
use App\Question;
use App\Response;
use App\User;
use App\Project;
use App\ResponseItem;
use Illuminate\Database\Eloquent\Model;

class Quote extends Model
{
    public $fillable = [
        'sender_id',
        'project_id',
        'budget',
        'bid_type',
        'message',
        'sender_name',
        'bid_status',
        'deleted_at',
        'created_at',
        'updated_at',
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
