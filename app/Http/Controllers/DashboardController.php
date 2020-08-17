<?php

namespace App\Http\Controllers;

use DB;
use Flash;
use App\Choice;
use App\Profile;
use App\Project;
use App\Service;
use App\Message;
use App\Question;
use App\Response;
use App\JobRequest;
use App\FavoritePro;
use App\Category;
use App\AppHeader;
use App\SubCategory;
use App\Conversation;
use App\ResponseItem;
use App\Models\Auth\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;


class DashboardController extends Controller
{


    //V1 API INDEX
    public function apiIndex()
    {
        $title = "UBUY APP";
        $recomend = DB::table("sub_categories")
                    ->join('api_recommends', 'api_recommends.subcategory_id', '=', 'sub_categories.id')
                    ->orderBy('sub_categories.count', 'desc')->get()->take(7);
        $design = SubCategory::where('category_id','=', 8)->get()->take(7);
        $business = SubCategory::where('category_id','=', 14)->get()->take(7);
        $personal = SubCategory::where('category_id','=', 15)->get()->take(7);
        $home = SubCategory::where('category_id','=', 1)->get()->take(7);

    
        $row['recommend']=$recomend;
        $category = Category::get();
        $row['category']=$category;
        $row['design_web']=$design;
        $row['business']=$business;
        $row['personal']=$personal;
        $row['home']=$home;


        // echo $recomend;
        $set['UBUYAPI_V1'] = $row;
		
		// header( 'Content-Type: application/json; charset=utf-8' );
	    echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
		die();
    }
    public function apiProjects()
        {
        
            if (isset($_GET['user_id'])) {
                $user_id = filter_input(INPUT_GET, 'user_id', FILTER_SANITIZE_STRING);

                $user = Auth::loginUsingId($user_id);

                if (!$user) {
                    $set['UBUYAPI_V1'][]=array('msg' =>'Account not found','success'=>'0');
                }else if($user){
                // $projects = $user->projectsSubCat->get();

                $projects = DB::table("projects")
                ->join('sub_categories', 'projects.sub_category_id', '=', 'sub_categories.id')
                ->where('projects.user_id', '=', $user_id)
                ->select('projects.id', 'projects.user_id', 'projects.created_at', 'sub_categories.image', 'projects.sub_category_name', 'projects.sub_category_id','projects.address',  'projects.project_message')
                ->orderBy('projects.id', 'desc')->get();

                if($projects->isEmpty()){
                    // $set['UBUYAPI_V1'] = ;

                }
            else if($projects){

                foreach($projects as $project){
                        $date = Carbon::parse($project->created_at); // now date is a carbon instance
                
                    $set['UBUYAPI_V1'][]=array(
                        
                        'project_id' => $project->id,
                    'sub_category_id' => $project->sub_category_id,
                    'user_id' => $project->user_id,
                    'sub_category_name' => $project->sub_category_name,
                    'project_message' => $project->project_message,
                    'category_image' => $project->image,
                    'address' => $project->address,
                    'created_at' => $date->diffForHumans(),
                    // 'created_at' => $project->created_at,
                    );
                }
            } 

        

            

            // echo $projects;
            // $set['UBUYAPI_V1'] = $projects;
        }
        
        header( 'Content-Type: application/json; charset=utf-8' );
        echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
        die();
    }
}
    public function apiCategories()
        {
        
            $category = Category::get();
            $set['UBUYAPI_V1'] = $category;
            header( 'Content-Type: application/json; charset=utf-8' );
            echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
            die();
        }
        

        public function allSubcat(){
            // $json = SubCategory::get();
            $json = SubCategory::select('id', 'name', 'secondary_name')->get();

            $set['UBUYAPI_V1'] = $json;
            header( 'Content-Type: application/json; charset=utf-8' );
            echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
            die();
          
    
        }


        public function seachCat(){
            $id = filter_input(INPUT_GET, 'cat_id', FILTER_SANITIZE_STRING);

            $Category =  SubCategory::where('category_id','=', $id)->select('id', 'name','category_id', 'description', 'image')->get();


            $set['UBUYAPI_V1'] = $Category;
            header( 'Content-Type: application/json; charset=utf-8' );
            echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
            die();
          
    
        }


        public function fetch_subcat(){

            if (isset($_GET['phrase'])) {
                $phrase= filter_input(INPUT_GET, 'phrase', FILTER_SANITIZE_STRING);
                
                $json = SubCategory::where('name','LIKE','%'.$phrase.'%')->select('id', 'name', 'secondary_name')
                ->get();
    
                $set['UBUYAPI_V1'] = $json;
            header( 'Content-Type: application/json; charset=utf-8' );
            echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
            die();
            }
            $empty = "Couldn't get request";
            $set['UBUYAPI_V1'] = $empty;
            header( 'Content-Type: application/json; charset=utf-8' );
            echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
            die();
    
          
    
        }
        public function Suggestions(){

            $recomend = DB::table("sub_categories")
            ->join('api_recommends', 'api_recommends.subcategory_id', '=', 'sub_categories.id')
            ->select('sub_categories.id', 'sub_categories.category_id', 'sub_categories.name', 'sub_categories.icon')
            ->orderBy('sub_categories.count', 'desc')->get()->take(7);

            $set['UBUYAPI_V1'] = $recomend;
            header( 'Content-Type: application/json; charset=utf-8' );
            echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
            die();
    
          
    
        }

        public function singleSubCategory($id)
        {
    
            $subCategory = SubCategory::find($id);
            $questions = $subCategory->questions;
            $count = $subCategory->count++;
            $subCategory->update(['count' => $count]);
    
            $set['UBUYAPI_V1'] = $questions;
            header( 'Content-Type: application/json; charset=utf-8' );
            echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
            die();
    
        }

        public function singleQuestion($id)
        {
    
            $question = Question::find($id);
            $choices = $question->choices;
    
            $set['UBUYAPI_V1'] = $choices;
            header( 'Content-Type: application/json; charset=utf-8' );
            echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
            die();
    
        }

        public function apiInbox()
        {
        
            if (isset($_GET['user_id'])) {
                $user_id = filter_input(INPUT_GET, 'user_id', FILTER_SANITIZE_STRING);

                $user = Auth::loginUsingId($user_id);

                if (!$user) {
                    $set['UBUYAPI_V1'][]=array('msg' =>'Account not found','success'=>'0');
                }else if($user){
                // $projects = $user->projectsSubCat->get();

                $projects = DB::table("projects")
                ->join('sub_categories', 'projects.sub_category_id', '=', 'sub_categories.id')
                ->where('projects.user_id', '=', $user_id)
                ->select('projects.id', 'projects.user_id', 'projects.created_at', 'sub_categories.image', 'projects.sub_category_name', 'projects.sub_category_id','projects.address',  'projects.project_message')
                ->orderBy('projects.id', 'desc')->get();

                if($projects->isEmpty()){
                    $set['UBUYAPI_V1'] = "blank";

                }
            else if($projects){

                foreach($projects as $project){
                        $date = Carbon::parse($project->created_at); // now date is a carbon instance
                
                    $set['UBUYAPI_V1'][]=array(
                        
                        'project_id' => $project->id,
                    'sub_category_id' => $project->sub_category_id,
                    'user_id' => $project->user_id,
                    'sub_category_name' => $project->sub_category_name,
                    'project_message' => $project->project_message,
                    'category_image' => $project->image,
                    'address' => $project->address,
                    'created_at' => $date->diffForHumans(),
                    // 'created_at' => $project->created_at,
                    );
                }
            } 

        

            

            // echo $projects;
            // $set['UBUYAPI_V1'] = $projects;
        }
        
        header( 'Content-Type: application/json; charset=utf-8' );
        echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
        die();
    }
}
        public function apiQuickChat()
        {
        
            if (isset($_GET['user_id'])) {
                $user_id = filter_input(INPUT_GET, 'user_id', FILTER_SANITIZE_STRING);
                $project_id = filter_input(INPUT_GET, 'project_id', FILTER_SANITIZE_STRING);

                $user = Auth::loginUsingId($user_id);

                if (!$user) {
                    $set['UBUYAPI_V1'][]=array('msg' =>'Account not found','success'=>'0');
                }else if($user){
                // $projects = $user->projectsSubCat->get();

                $bids = DB::table("project_bids")
                ->where('project_bids.cus_id','=', $user->id)
                ->join('projects', 'project_bids.project_id', '=', 'projects.id')
                ->where('projects.id','=', $project_id)
                ->join('profiles', 'project_bids.user_id', '=', 'profiles.user_id')
                ->select('projects.id', 'project_bids.id AS bid_id',  'project_bids.user_id AS bidder_id', 'profiles.profile_photo AS bidder_image', 'projects.sub_category_name', 'project_bids.bid_message', 'project_bids.bid_amount', 'profiles.business_name', 'project_bids.created_at')
               ->get();

                if($bids->isEmpty()){
                    $set['UBUYAPI_V1'] = $user->email;
                }
            else if($bids){
                    // $set['UBUYAPI_V1'] = $bids;

                foreach($bids as $bid){
                        $date = Carbon::parse($bid->created_at); // now date is a carbon instance

                        $bid_amount = "₦".$bid->bid_amount;
                    $set['UBUYAPI_V1'][]=array(
                        
                    'project_id' => $bid->id,
                    'bid_id' => $bid->bid_id,
                    'bidder_id' => $bid->bidder_id,
                    'project_title' => $bid->sub_category_name,
                    'bid_message' => $bid->bid_message,
                    'bid_amount' => $bid_amount,
                    'bidder_image' => $bid->bidder_image,
                    'bidder_name' => $bid->business_name,
                    'created_at' => $date->diffForHumans(),
                    // 'created_at' => $project->created_at,
                    );
                }
            } 

        

            

            // echo $bids;
            // $set['UBUYAPI_V1'] = $projects;
        }
        
        header( 'Content-Type: application/json; charset=utf-8' );
        echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
        die();
    }
}
        public function apiChat()
        {
        
            if (isset($_GET['bid_id'])) {
                $user_id = filter_input(INPUT_GET, 'user_id', FILTER_SANITIZE_STRING);
                $bid_id = filter_input(INPUT_GET, 'bid_id', FILTER_SANITIZE_STRING);

                $bid = DB::table('project_bids')->where('id',$bid_id)->first();
                $user = DB::table('users')->where('id',$bid->user_id)->first();
                $profile = DB::table('profiles')->where('user_id',$bid->user_id)->first();

                $user->first_name;
                $auth_user = Auth::loginUsingId($user_id);
                 $auth_id=$auth_user->id;

                if (!$auth_user) {
                    $set['UBUYAPI_V1'][]=array('msg' =>'Account not found','success'=>'0');
                }else if($auth_user){
                // $projects = $user->projectsSubCat->get();

              
                 $chats = Message::where('bid_id',$bid_id)
                              ->where('sender_id',$user->id)
                              ->where('receiver_id',$auth_id)
                              ->Orwhere('sender_id',$auth_id)
                              ->where('receiver_id',$user->id)
                              ->get();

                if($chats->isEmpty()){
                    $set['UBUYAPI_V1'] = $user->email;
                }
            else if($chats){
                    // $set['UBUYAPI_V1'] = $chats;

                    foreach($chats as $chat){
                        $date = Carbon::parse($chat->created_at); // now date is a carbon instance
                
                        if($chat->sender_id != $auth_id){
                            if ($profile->profile_photo) {
                                $chat_image = 'https://beta.ubuy.ng/uploads/images/profile_pics/'.$profile->profile_photo;
                            } else{
                                $chat_image = 'https://placehold.it/50/55C1E7/fff&text='. mb_substr($profile->business_name , 0, 1);

                            }

                    $set['UBUYAPI_V1'][]=array(
                        
                            'chatter_id' =>  $chat->id,
                            'chatter_sender' => 0,
                            'chatter_message' =>  $chat->message,
                            'chatter_image' => $chat_image,
                            'chatter_time' => $date->diffForHumans(),
                        );
                        } else{
                            if ($profile->profile_photo) {
                                $chat_image = 'https://beta.ubuy.ng/uploads/images/profile_pics/'.$auth_user->image;
                            } else{
                                $chat_image = 'https://placehold.it/50/55C1E7/fff&text='. mb_substr($auth_user->last_name , 0, 1);

                            }

                    $set['UBUYAPI_V1'][]=array(
                        'chatter_id' =>  $chat->id,
                        'chatter_sender' => 1,
                        'chatter_message' =>  $chat->message,
                        'chatter_image' => $chat_image,
                        'chatter_time' => $date->diffForHumans(),
                        );
                        }
                   
                }

            } 

        

            

            // echo $bids;
            // $set['UBUYAPI_V1'] = $projects;
        }
        
        header( 'Content-Type: application/json; charset=utf-8' );
        echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
        die();
    }
}

public function storeMessage(Request $request){
    $chat = new Message;
    $chat->message = $request->message;
    $chat->sender_id = $request->sender;
    $chat->receiver_id = $request->receiver;
    $chat->bid_id = $request->bid_id;
    $chat->project_id = $request->project_id;
    $chat->save();
    typing::where('receiver_id',$chat->receiver_id)
          ->where('sender_id', $chat->sender_id)
          ->update(['check_status' => 0]);
    return back();
}

public function apiStoreMessage()
{
    
    $message = filter_input(INPUT_GET, 'message', FILTER_SANITIZE_STRING);
    $sender_id = filter_input(INPUT_GET, 'sender_id', FILTER_SANITIZE_STRING);
    $receiver_id = filter_input(INPUT_GET, 'receiver_id', FILTER_SANITIZE_STRING);
    $bid_id = filter_input(INPUT_GET, 'bid_id', FILTER_SANITIZE_STRING);
    $project_id = filter_input(INPUT_GET, 'project_id', FILTER_SANITIZE_STRING);
    
    $chat = new Message;
    $chat->message = $message;
    $chat->sender_id = $sender_id;
    $chat->receiver_id = $receiver_id;
    $chat->bid_id = $bid_id;
    $chat->project_id = $project_id;
    $chat->save();
}

   public function apiSearchCat()
   {

    $categories = Category::where('id','!=', 1)->where('id', '!=', 6)->where('id', '!=', 8)->select('id', 'name', 'image')->get();


       $set['UBUYAPI_V1'] = $categories;
       header( 'Content-Type: application/json; charset=utf-8' );
       echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
       die();

   }


  
    
}
