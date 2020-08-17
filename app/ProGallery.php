<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProGallery extends Model
{
    protected $guarded = [];
    public $timestamps = true;

    // public function jobs(){
    //     return $this->hasMany(Job::class);
    // }

    // public function active_jobs(){
    //     return $this->hasMany(Job::class)->whereStatus(1)->where('deadline', '>=', date('Y-m-d').' 00:00:00');
    // }


    

       
}
