<?php

namespace App\Http\Controllers;

use DB;
use Flash;
use App\Choice;
use App\Profile;
use App\Project;
use App\Service;
use App\ContactUser;
use App\Message;
use App\Question;
use App\Response;
use App\JobRequest;
use App\FavoritePro;
use App\Category;
use App\SubCategory;
use App\Conversation;
use App\ResponseItem;
use App\UPoints;
use App\Models\Auth\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Auth;

class ProDashboardController extends Controller
{
    public function apiIndex(){

        // auth checker using user_id
        if (isset($_GET['user_id'])) {
            $user_id = filter_input(INPUT_GET, 'user_id', FILTER_SANITIZE_STRING);

            $user = Auth::loginUsingId($user_id);

            if (!$user) {
                $set['UBUYAPI_V1'][]=array('msg' =>'Account not found','success'=>'0');
            }else if($user){

        $user_pro = Profile::select('id', 'lat', 'lng', 'pro_city', 'pro_state', 'distance')
        ->where('user_id','=', $user->id)
        ->first();


        $lat = $user_pro->lat;
        $lon = $user_pro->lng;
        $user_id= $user->id;
        $distance = $user_pro->distance;

        $available_request = DB::table("services")
        ->where('services.user_id', '=', $user_id)
        ->join('projects', function ($join) use ($lat, $lon, $distance) {
                  $join->on('projects.sub_category_id', '=', 'services.sub_category_id')
                        ->whereRaw( DB::raw("3959 * acos(cos(radians(" . $lat . ")) 
        * cos(radians(projects.lat)) 
        * cos(radians(projects.lng) - radians(" . $lon . ")) 
        + sin(radians(" .$lat. ")) 
        * sin(radians(projects.lat))) < $distance "));
                    })->select('projects.id', 'projects.cus_name', 'services.user_id', 'projects.created_at', 'projects.sub_category_name', 'projects.sub_category_id','projects.city', 'projects.state', 'projects.project_message')
                    ->orderBy('projects.id', 'desc')->get()->take(7);

    }

        $set['UBUYAPI_V1'] = $available_request;
        header( 'Content-Type: application/json; charset=utf-8' );
        echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
        die();

        }
    }

    public function contacts(){

        $contacts = ContactUser::get();


        // return view('admin.dashboard', $data);
        return view('demo.contacts',  compact('contacts'));
    }
}
