<?php

namespace App;

use App\SubCategory;
use Illuminate\Database\Eloquent\Model;

class JobRequest extends Model
{
    protected $fillable = [
    	'requested_by',
    	'requested_to',
    	'sub_category_id',
        'type',
        'status',
    	'datetime',
    	'time',
    	'phone_number',
    	'value',
    	'st_address',
    	'appartment',
    	'zip_code',
    	'deleted_at',
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

    public function ScopeAppointed($query)
    {
        return $query->where('type', 'appoint');
    }

    /*
    |--------------------------------------------------------------------------
    | Eloquent Relations
    |--------------------------------------------------------------------------
    */
    public function sub_category()
    {
        return $this->belongsTo(SubCategory::class);
    }
}
