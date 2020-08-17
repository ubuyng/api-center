<?php

namespace App;

use App\Choice;
//use App\Response;
use App\SubCategory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    public $fillable = [
        'text',
        'sub_category_id',
        'required',
        'type',
        'deleted_at',
    ];

    /*
    |--------------------------------------------------------------------------
    | Eloquent Relations
    |--------------------------------------------------------------------------
    */
    public function subCategory()
    {
        return $this->belongsTo(SubCategory::class)->with('category');
    }

    /*public function category()
    {
        return $this->belongsTo(SubCategory::class)->with('category');
    }*/

	public function choices()
    {
        return $this->hasMany(Choice::class);
    }
/*
    public function responses()
    {
        return $this->hasMany(Response::class);
    }
*/
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
}
