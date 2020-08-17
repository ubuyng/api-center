<?php

namespace App\Http\Controllers;

use DB;
use Flash;
use App\Project;
use App\Category;
use App\SubCategory;
use App\Quote;
use App\Service;
use App\Models\Auth\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SavedProsController extends Controller
{
    public function index(){
        $title = 'Saved pros - Ubuy.ng';

        $user = auth()->user();
   
        $pros = DB::table("favorite_pros")
        ->where('favorite_pros.user_id','=', $user->id)
        ->join('profiles', 'favorite_pros.pro_id', '=', 'profiles.id')
        ->select('profiles.id', 'profiles.business_name', 'profiles.profile_photo')
       ->get();   
       
       $services = Service::orderBy('id', 'desc')->get();

         
            return view('admin.customer_saved_pros', compact('pros', 'title', 'services'));
    }
   
    
  
   
}
