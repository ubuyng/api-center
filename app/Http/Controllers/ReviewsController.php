<?php

namespace App\Http\Controllers;

use DB;
use Flash;
use App\Project;
use App\Response;
use App\JobRequest;
use App\Category;
use App\SubCategory;
use App\Quote;
use App\Rating;
use App\Models\Auth\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ReviewsController extends Controller
{
 
 
    public function store(Request $request){
        $user = auth()->user();

        $rules = [
            'rating' => 'required',
            'rate_title' => 'required',
        ];
        $this->validate($request, $rules);

        $data = [
            'project_id' =>$request->project_id,
            'project_name' =>$request->project_name,
            'cus_name' =>$request->cus_name,
            'cus_id' =>$request->cus_id,
            'pro_id' =>$request->pro_id,
            'rating' => $request->rating,
            'rate_title' => $request->rate_title,
            'comment' => $request->comment,
        ];

        Rating::create($data);
        return back()->with('success');
    }
   
}
