<?php

namespace App;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    protected $fillable = [
    	
    	'status',
    	'rating',
    	'comment',
    	'rate_title',
    	'project_name',
    	'pro_id',
    	'cus_id',
    	'cus_name',
    	'project_id',
    	'rate_type',
    ];

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
    public function ScopeMyRatings($query, $userId)
    {
        return $query->where([
            'author_id'     => auth()->user()->id,
            'ratingable_id' => $userId,
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Eloquent Relations
    |--------------------------------------------------------------------------
    */
    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function provider()
    {
        return $this->belongsTo(User::class, 'ratingable_id');
    }
}
