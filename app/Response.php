<?php

namespace App;

use App\User;
use App\Choice;
use App\Question;
use Illuminate\Database\Eloquent\Model;

class Response extends Model
{
    public $fillable = [
        'question_id',
        'sub_category_id',
        'user_id',
        'choice_id',
        'project_id',
        'deleted_at',
        'created_at',
        'updated_at',
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

    public function ScopeGetResponse($query, $subCat, $userId)
    {
        return $query->where(['sub_category_id' => $subCat, 'user_id' => $userId]);
    }

    /*
    |--------------------------------------------------------------------------
    | Eloquent Relations
    |--------------------------------------------------------------------------
    */
    public function users()
    {
        return $this->belongsTo(User::class);
    }

    public function questions()
    {
        return $this->belongsTo(Question::class, 'choice_id');
    }

    public function choices()
    {
        return $this->belongsTo(Choice::class);
    }

    public static function processData($collections)
    {
        foreach ($collections as $collection) {
            $option   = Choice::find($collection->choice_id);
            $question = Question::find($collection->question_id);
            $collection->choice   = $option->text;
            $collection->question = $question->text;
        }
        
        return $collections;
    }
}
