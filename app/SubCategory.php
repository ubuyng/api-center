<?php

namespace App;

use App\Choices;
use App\Category;
use App\Question;
use App\Response;
use App\ResponseItem;
use Illuminate\Database\Eloquent\Model;

class SubCategory extends Model
{
    public $fillable = [
        'category_id',
        'name',
        'secondary_name',
        'image',
        'bg_image',
        'slug',
        'description',
        'deleted_at',
        'created_at',
        'updated_at',
    ];

    /*
    |--------------------------------------------------------------------------
    | Eloquent Relations
    |--------------------------------------------------------------------------
    */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function questions()
    {
        return $this->hasMany(Question::class)->unDeleted()->with('choices');
    }

    public function responseItems()
    {
        return $this->hasMany(ResponseItem::class);
    }

    public function responses()
    {
        return $this->hasMany(Response::class);
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
    public function ScopeHasImage($query)
    {
        return $query->where('image', '!=', null);
    }

    public function ScopeData($query, $data)
    {
        return $query->where($data);
    }
}
