<?php

namespace App;

use App\Question;
use Illuminate\Database\Eloquent\Model;

class Choice extends Model
{
    public $fillable = [
        'question_id',
        'text',
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
    public function question()
    {
        return $this->belongsTo(Question::class);
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
}
