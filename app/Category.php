<?php

namespace App;

use App\SubCategory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    public $fillable = [
        'name',
        'slug',
        'description',
        'icon',
        'image',
        'deleted_at',
    ];

    /*
    |--------------------------------------------------------------------------
    | Eloquent Relations
    |--------------------------------------------------------------------------
    */
    public function subCategories()
    {
        return $this->hasMany(SubCategory::class);
    }
    
    /**
     * Get the questions for the category.
     */
    public function questions()
    {
        return $this->hasMany('App\Models\Question');
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
}
