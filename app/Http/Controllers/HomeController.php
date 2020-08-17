<?php

namespace App\Http\Controllers;

use App\Category;
use App\SubCategory;
use App\Profile; 
use App\Service; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Mail;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(){
        //$this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){

        $title = 'Discover nearby professionals at Ubuy.ng';
        $user = auth()->user();
        
      

        $home_cat = Category::orderBy('id')->get()->take(7);
        $search_subcat = SubCategory::orderBy('id')->get();
        $demo_pros = Profile::where('demo_pros', 1)->get();
        $demo_service = Service::get();
        if (auth()->user() == true) {
           
       
        $verified_user = $user->verify_confirm;
        if ($user->user_role == 'pro' && $verified_user == 2) {
            $verify_status = 2;
      


        }elseif ($user->user_role == 'pro' && $verified_user == 1) {
            $verify_status = 1;

        }
        elseif ($user->user_role == 'pro' && $verified_user == 0) {
            $verify_status = 0;
       
        }

    } else{
        $verify_status = null;
    }
        return view('home', compact('user', 'home_cat', 'search_subcat', 'title', 'verify_status', 'demo_service', 'demo_pros'));
    }


    /**
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     *
     * Clear all cache
     */
    public function clearCache(){
        Artisan::call('debugbar:clear');
        Artisan::call('view:clear');
        Artisan::call('route:clear');
        Artisan::call('config:clear');
        Artisan::call('cache:clear');
        if (function_exists('exec')){
            exec('rm ' . storage_path('logs/*'));
        }
        return redirect(route('home'));
    }


    public function fetch_subcat(Request $request){
        $q = $request->q;
        $json = SubCategory::where('name','LIKE','%'.$q.'%')->select('id', 'name', 'description')
        ->get();
        

        // $filtered = $json;
        //     if(strlen($request->q)) {
        //         $filtered = array_filter($json, function ($val) use ($q) {
        //             if (stripos($val['Name'], $q) !== false) {
        //                 return true;
        //             } else {
        //                 return false;
        //             }
        //         });
        //     }
        //     $search = json_encode(array_slice(array_values($filtered), 0, 20));
        // $search_subcat = SubCategory::orderBy('id')->get();

      
        return \Response::json( $json);

    }


}
