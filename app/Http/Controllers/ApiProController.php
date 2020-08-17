<?php

namespace App\Http\Controllers;

use DB;
use Flash;
use App\Choice;
use App\Profile;
use App\Project;
use App\ProjectBid;
use App\Service;
use App\Message;
use App\ProjectFile;
use App\Question;
use App\Response;
use App\JobRequest;
use App\FavoritePro;
use App\Rating;
use App\Category;
use App\AppHeader;
use App\TaskTracker;
use App\State;
use App\SubCategory;
use App\Conversation;
use App\User;
use App\Notification;
use App\PastProject;
use App\ProGallery;
use App\ResponseItem;
use App\UpayTransaction;
use App\BankDetail;
use App\ProCredential;
use App\FavoriteTask;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use GuzzleHttp\Client;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;
use Closure;


class ApiProController extends Controller
{

      /* 
   ** ======== API VERSION 1 SECTION STARTS HERE =======
   **
   */
       public function apiProStats()
       {
           $title = "UBUYAPI_V1";
           
      
           if (isset($_GET['user_id'])) {
            $user_id = filter_input(INPUT_GET, 'user_id', FILTER_SANITIZE_STRING);

            $user = Auth::loginUsingId($user_id);

 
            $user_pro = Profile::select('id', 'lat', 'lng', 'pro_city', 'pro_state', 'distance')
            ->where('user_id','=', $user->id)
            ->first();

            /* upay */
            $upay_balance = UpayTransaction::where('user_id',$user->id)->where('status',1)->get();
                $upay = $upay_balance->sum('amount');

                $transact_percent = 9.5;
                $percent_total = ($transact_percent/100)*$upay;
                $transact_total = $upay - $percent_total;
                
                $project_count = $user->projects->count();
                $bids_count = $user->projectbids->count();

       
        

       
           $row['upay_balance']=$transact_total;
           $row['pro_projects']=$project_count;
           $row['bids_count']=$bids_count;
   
   
           // echo $recomend;
        //    $set['UBUYAPI_V1'] = $row;
           $set[$title] = $row;
           
           // header( 'Content-Type: application/json; charset=utf-8' );
           echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
           die();
         }
       }
       public function apiupayStats()
       {
           
      
           if (isset($_GET['user_id'])) {
            $user_id = filter_input(INPUT_GET, 'user_id', FILTER_SANITIZE_STRING);

            $user = Auth::loginUsingId($user_id);



            /* upay */
            $upay_balance = UpayTransaction::where('user_id',$user->id)->where('status',2)->get();
            $upay_onhold = UpayTransaction::where('user_id',$user->id)->where('status',1)->get();
            $upay_total = UpayTransaction::where('user_id',$user->id)->get();


            /* total */
                $upay = $upay_total->sum('amount');

                $transact_percent = 9.5;
                $percent_total = ($transact_percent/100)*$upay;
                $transact_total = $upay - $percent_total;

                 /* balance available calculation */
          $upay_balance_sum = $upay_balance->sum('amount');
          $bal_percent_total = ($transact_percent/100)*$upay_balance_sum;
          $transact_bal_total = $upay_balance_sum - $bal_percent_total;

           /* hold calculation */
           $upay_hold_sum = $upay_onhold->sum('amount');
           $hold_percent_total = ($transact_percent/100)*$upay_hold_sum;
           $transact_hold_total = $upay_hold_sum - $hold_percent_total;
   
           $set['UBUYAPI_V1'][]=array(
               'total_earnings' =>$transact_total,
               'onhold_earnings' =>$transact_hold_total,
               'balance_earnings' =>$transact_bal_total,
               );

           
           header( 'Content-Type: application/json; charset=utf-8' );
           echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
           die();
         }
       }
       public function apiPendingPaymenTasks()
       {
            if (isset($_GET['user_id'])) {
                $user_id = filter_input(INPUT_GET, 'user_id', FILTER_SANITIZE_STRING);

                $user = Auth::loginUsingId($user_id);

                $upay_onhold = UpayTransaction::where('user_id',$user->id)->where('status',1)->get();
               
              $bids = DB::table("project_bids")
                ->where('project_bids.user_id', '=', $user->id)
                ->join('upay_transactions', 'project_bids.id', '=', 'upay_transactions.bid_id')
                ->where('upay_transactions.status', '=', '1' )
                ->select('project_bids.id as bid_id', 'project_bids.user_id as pro_id','project_bids.cus_id as cus_id','project_bids.created_at', 'project_bids.bid_duration', 'project_bids.bid_amount', 'project_bids.bid_message','project_bids.bid_status', 'project_bids.cus_id', 'project_bids.project_id')
                ->get();
              $bids_count = DB::table("project_bids")
                ->where('project_bids.user_id', '=', $user->id)
                ->join('upay_transactions', 'project_bids.id', '=', 'upay_transactions.bid_id')
                ->where('upay_transactions.status', '=', '1' )
                ->select('project_bids.id as bid_id', 'project_bids.user_id as pro_id','project_bids.cus_id as cus_id','project_bids.created_at', 'project_bids.bid_duration', 'project_bids.bid_amount', 'project_bids.bid_message','project_bids.bid_status', 'project_bids.cus_id', 'project_bids.project_id')
                ->exists();
            
                if (!$bids_count) {
                   
                    $set['UBUYAPI_V1'][]=array(
                                     
                        'msg' => 'No pending tasks',
                        );

                }else{
                    foreach($bids as $bid){
                        $date = Carbon::parse($bid->created_at); // now date is a carbon instance
                
                         
                        $projects = Project::select('id', 'status', 'pro_id')->where('id','=', $bid->project_id)
                        ->get();
                        foreach($projects as $project){
                            if ($project->id == $bid->project_id) {
                                
                                
                                    $set['UBUYAPI_V1'][]=array(
                                     
                                        'bid_id' => $bid->bid_id,
                                        'project_id' => $bid->project_id,
                                        'customer_id' => $bid->cus_id,
                                        'user_id' => $bid->pro_id,
                                        'bid_message' => $bid->bid_message,
                                        'bid_amount' =>"₦".$bid->bid_amount,
                                        'bid_duration' => $bid->bid_duration,
                                        'bid_status' => 1,
                                        'created_at' => $date->diffForHumans(),
                                        );
                                
                             }
                        }
                     
                        }
                }

            }

          
            header( 'Content-Type: application/json; charset=utf-8' );
            echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
            die();
    }
       public function apiApprovedPaymenTasks()
       {
            if (isset($_GET['user_id'])) {
                $user_id = filter_input(INPUT_GET, 'user_id', FILTER_SANITIZE_STRING);

                $user = Auth::loginUsingId($user_id);               
               $bids = DB::table("project_bids")
                ->where('project_bids.user_id', '=', $user->id)
                ->join('upay_transactions', 'project_bids.id', '=', 'upay_transactions.bid_id')
                ->where('upay_transactions.status', '=', 2 )
                ->select('project_bids.id as bid_id', 'project_bids.user_id as pro_id','project_bids.cus_id as cus_id','project_bids.created_at', 'project_bids.bid_duration', 'project_bids.bid_amount', 'project_bids.bid_message','project_bids.bid_status', 'project_bids.cus_id', 'project_bids.project_id')
                ->get();
                
                $bids_count = DB::table("project_bids")
                ->where('project_bids.user_id', '=', $user->id)
                ->join('upay_transactions', 'project_bids.id', '=', 'upay_transactions.bid_id')
                ->where('upay_transactions.status', '=', 2 )
                ->select('project_bids.id as bid_id', 'project_bids.user_id as pro_id','project_bids.cus_id as cus_id','project_bids.created_at', 'project_bids.bid_duration', 'project_bids.bid_amount', 'project_bids.bid_message','project_bids.bid_status', 'project_bids.cus_id', 'project_bids.project_id')
                ->exists();
               

                if (!$bids_count) {
                   
                    $set['UBUYAPI_V1'][]=array(
                                     
                        'msg' => 'No awaiting payment tasks found',
                        );

                }else{
                    foreach($bids as $bid){
                        $date = Carbon::parse($bid->created_at); // now date is a carbon instance
                
                         
                        $projects = Project::select('id', 'status', 'pro_id')->where('id','=', $bid->project_id)
                        ->get();
                        foreach($projects as $project){
                            if ($project->id == $bid->project_id) {
                                
                                
                                    $set['UBUYAPI_V1'][]=array(
                                     
                                        'bid_id' => $bid->bid_id,
                                        'project_id' => $bid->project_id,
                                        'customer_id' => $bid->cus_id,
                                        'user_id' => $bid->pro_id,
                                        'bid_message' => $bid->bid_message,
                                        'bid_amount' =>"₦".$bid->bid_amount,
                                        'bid_duration' => $bid->bid_duration,
                                        'bid_status' => 1,
                                        'created_at' => $date->diffForHumans(),
                                        );
                                
                             }
                        }
                     
                        }
                }

            }
            header( 'Content-Type: application/json; charset=utf-8' );
            echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
            die();
    }



    public function apiProjects(){
        if (isset($_GET['user_id'])) {
            $user_id = filter_input(INPUT_GET, 'user_id', FILTER_SANITIZE_STRING);

            $user = User::where('id', $user_id)->first();
            
            if ($user) {
                $user_pro = Profile::select('id', 'lat', 'lng', 'pro_city', 'pro_state', 'distance')
                ->where('user_id','=', $user->id)
                ->first();
                if ($user_pro) {

                         
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
                    })->select('projects.id', 'services.user_id as pro_id', 'projects.project_title', 'projects.user_id', 'projects.cus_name', 'projects.created_at', 'projects.sub_category_name', 'projects.sub_category_id','projects.city', 'projects.state', 'projects.project_message')
                    ->orderBy('projects.id', 'desc')->get();


                    $set['UBUYAPI_V1'] = $available_request;

                } else {
                    $set['UBUYAPI_V1'][]=array('msg' =>'Sorry, Please create a business account to see recent tasks','success'=>'0');
                }
                
            }
            else {
                $set['UBUYAPI_V1'][]=array('msg' =>'Account not found','success'=>'0');
            }

        }
        header( 'Content-Type: application/json; charset=utf-8' );
        echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
        die();
    }

// bids api
public function apiBids(){
    $title = "UBUYAPI_V1";

    
    if (isset($_GET['user_id'])) {
        $user_id = filter_input(INPUT_GET, 'user_id', FILTER_SANITIZE_STRING);

        $user = Auth::loginUsingId($user_id);

        if (!$user) {
            $set['UBUYAPI_V1'][]=array('msg' =>'Account not found','success'=>'0');
        }elseif($user){
            
            $bids = $user->projectbids;

        
            foreach($bids as $bid){
                $date = Carbon::parse($bid->created_at); // now date is a carbon instance
        
                 
                $projects = Project::select('id', 'status', 'pro_id')->where('id','=', $bid->project_id)
                ->get();
                foreach($projects as $project){
                    if ($project->pro_id == $bid->user_id) {
                        
                        if ($project->status == 2) {
                            $set['UBUYAPI_V1'][]=array(
                             
                                'bid_id' => $bid->id,
                                'project_id' => $bid->project_id,
                                'customer_id' => $bid->cus_id,
                                'user_id' => $bid->user_id,
                                'bid_message' => $bid->bid_message,
                                'bid_amount' =>"₦".$bid->bid_amount,
                                'bid_duration' => $bid->bid_duration,
                                'bid_status' => 1,
                                'created_at' => $date->diffForHumans(),
                                );
                        }elseif ($project->status == 3) {
                            $set['UBUYAPI_V1'][]=array(
                             
                                'bid_id' => $bid->id,
                                'project_id' => $bid->project_id,
                                'customer_id' => $bid->cus_id,
                                'user_id' => $bid->user_id,
                                'bid_message' => $bid->bid_message,
                                'bid_amount' => '₦'.$bid->bid_amount,
                                'bid_duration' => $bid->bid_duration,
                                'bid_status' => 2,
                                'created_at' => $date->diffForHumans(),
                                );
                        }
                     } elseif($project->pro_id == null) {
                        $set['UBUYAPI_V1'][]=array(
                             
                            'bid_id' => $bid->id,
                            'project_id' => $bid->project_id,
                            'customer_id' => $bid->cus_id,
                            'user_id' => $bid->user_id,
                            'bid_message' => $bid->bid_message,
                            'bid_amount' => '₦'.$bid->bid_amount,
                            'bid_duration' => $bid->bid_duration,
                            'bid_status' => 0,
                            'created_at' => $date->diffForHumans(),
                            );
                     }
     
                     
                    //  if ($bid->status == 0 & $p_status == 0) {
                    //    $status = 0;
                    //  }elseif($bid->status == 1 & $p_status == 0 ){
                    //      $status = 1;
                    //  }elseif($bid->status == 1 & $p_status == 1 ){
                    //     $status = 1;
                    // }
                    // elseif($bid->status == 1 & $p_status == 2 ){
                    //      $status = 2;
                    //  }elseif ($bid->status == 1 & $p_status == 3 ) {
                    //      $status = 3;
                    //  }
     
                     
     
                        
                }
             
                }

        }
    
            header( 'Content-Type: application/json; charset=utf-8' );
            echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
            die();
    }
}
public function apiProInbox(){
    $title = "UBUYAPI_V1";

    
    if (isset($_GET['user_id'])) {
        $user_id = filter_input(INPUT_GET, 'user_id', FILTER_SANITIZE_STRING);

        $user = Auth::loginUsingId($user_id);

        if (!$user) {
            $set['UBUYAPI_V1'][]=array('msg' =>'Account not found','success'=>'0');
        }elseif($user){
            
            $bids = $user->projectbids;

            if ($user->image) {
                $user_image = 'https://ubuy.ng/uploads/images/profile_pics/'.$user->image;
            } else {
                $user_image = null;
            }
        
            foreach($bids as $bid){
                $date = Carbon::parse($bid->created_at); // now date is a carbon instance
        
                 
                $projects = Project::select('id', 'status', 'pro_id', 'project_title', 'sub_category_name')->where('id','=', $bid->project_id)->get();
                $profile = Profile::select('id', 'business_name')->where('user_id','=', $bid->user_id)->first();
                // $last_chat = Message::select('id', 'message')->where('bid_id','=', $bid->project_id)
                $last_chat = Message::select('message')->where('bid_id','=', $bid->id)->latest()->first();

                foreach($projects as $project){
                    if ($project->pro_id == $bid->user_id) {
                        
                        if ($project->status >= 1) {
                            $set['UBUYAPI_V1'][]=array(
                             
                                'bid_id' => $bid->id,
                                'project_id' => $bid->project_id,
                                'customer_id' => $bid->cus_id,
                                'user_id' => $bid->user_id,
                                'last_message' => $last_chat->message,
                                'pro_name' =>$profile->business_name,
                                'pro_image' => $user_image,
                                'project_title' => $project->project_title,
                                'sub_category_name' => $project->sub_category_name,
                                'bid_status' => 1,
                                'created_at' => $date->diffForHumans(),
                                );
                        }
                     } elseif($project->pro_id == null) {
                      if ($bid->bid_status > 0) {
                        $set['UBUYAPI_V1'][]=array(
                             
                            'bid_id' => $bid->id,
                            'project_id' => $bid->project_id,
                            'customer_id' => $bid->cus_id,
                            'user_id' => $bid->user_id,
                            'last_message' => $last_chat->message,
                            'pro_name' =>$profile->business_name,
                            'pro_image' => $user_image,
                            'project_title' => $project->project_title,
                            'sub_category_name' => $project->sub_category_name,
                            'bid_status' => 0,
                            'created_at' => $date->diffForHumans(),
                            );
                      }
                     }
     
                     
                    //  if ($bid->status == 0 & $p_status == 0) {
                    //    $status = 0;
                    //  }elseif($bid->status == 1 & $p_status == 0 ){
                    //      $status = 1;
                    //  }elseif($bid->status == 1 & $p_status == 1 ){
                    //     $status = 1;
                    // }
                    // elseif($bid->status == 1 & $p_status == 2 ){
                    //      $status = 2;
                    //  }elseif ($bid->status == 1 & $p_status == 3 ) {
                    //      $status = 3;
                    //  }
     
                     
     
                        
                }
             
                }

        }
    
            header( 'Content-Type: application/json; charset=utf-8' );
            echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
            die();
    }
}

    public function updateTask()
    {

        if (isset($_GET['project_id'])) {
            $project_id = filter_input(INPUT_GET, 'project_id', FILTER_SANITIZE_STRING);
            $status_update = filter_input(INPUT_GET, 'status', FILTER_SANITIZE_STRING);

            $project = Project::where('id','=', $project_id)->first();


            if (!$project) {
                $set['UBUYAPI_V1'][]=array('msg' =>'Project not found','success'=>'0');
            }else if($project){

                if ($status_update == 4) {

                    $project->update(['status' => $status_update]);
                    $track_message =  'You paused ' . $project->sub_category_name . ' task';
                    $tracker = [
                        'user_id' => $project->user_id,
                        'project_id' => $project->id,
                        'track_type' => "project_status",
                        'message' => $track_message,
                    ];
                    TaskTracker::create($tracker);
                    $update_task[]=array(
                        'project_id' => $project->id,
                        'project_status' => $project->status,
                        'msg' => "Task paused",
                    );
                    
                } elseif($status_update == 3) {
                    $project->update(['status' => $status_update]);
                    $track_message =  'You marked  ' . $project->sub_category_name. ' task as done';
                $tracker = [
                    'user_id' => $project->user_id,
                    'project_id' => $project->id,
                    'track_type' => "project_status",
                    'message' => $track_message,
                ];
                TaskTracker::create($tracker);
                $update_task[]=array(
                    'project_id' => $project->id,
                    'project_status' => $project->status,
                    'msg' => "Task Completed",
                );
                } elseif($status_update == 5) {
                    $project->update(['status' => $status_update]);
                    $track_message =  'you deleted  ' . $project->sub_category_name. ' task';
                $tracker = [
                    'user_id' => $project->user_id,
                    'project_id' => $project->id,
                    'track_type' => "project_status",
                    'message' => $track_message,
                ];
                TaskTracker::create($tracker);
                $update_task[]=array(
                    'project_id' => $project->id,
                    'project_status' => $project->status,
                    'msg' => "Task Deleted",
                );
                }
                

            }
            
            $set['UBUYAPI_V1'] = $update_task;

        }

        header( 'Content-Type: application/json; charset=utf-8' );
        echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
        die();

    }

    public function ApiSingleBid()
        {
            if (isset($_GET['bid_id'])) {
                $bid_id = filter_input(INPUT_GET, 'bid_id', FILTER_SANITIZE_STRING);

                $bid = ProjectBid::where('id','=', $bid_id)->first();
                $bid->update(['bid_status' => 1]);

            $bid_api = DB::table("project_bids")
            ->where('project_bids.id', '=', $bid->id)
            ->join('profiles', 'project_bids.user_id', '=', 'profiles.user_id')
            ->join('users', 'project_bids.user_id', '=', 'users.id')
            ->select('project_bids.id as bid_id', 'project_bids.user_id as pro_id','profiles.profile_photo', 'profiles.pro_city', 'profiles.project_done','users.image', 'project_bids.cus_id as cus_id','profiles.business_name', 'project_bids.created_at', 'project_bids.bid_amount', 'project_bids.bid_message','project_bids.bid_status', 'project_bids.cus_id', 'project_bids.project_id')
            ->first();


            $date = Carbon::parse($bid_api->created_at); // now date is a carbon instance

            /* Profiles photo checker and links */
            if ($bid_api->profile_photo != null ) {
                $profile_url = 'https://ubuy.ng/uploads/images/profile_pics/'.$bid_api->profile_photo;
            } elseif($bid_api->profile_photo == null && $bid_api->image != null)  {
                $profile_url = 'https://ubuy.ng/uploads/images/profile_pics/'.$bid_api->image;
            }else {
                $profile_url = null;

            }

            $task_done_1  = $bid_api->project_done;

            $task_done_2 =  count(Project::where('pro_id','=', $bid_api->pro_id)->get());
        
    
             $rating_checker = Rating::where('pro_id','=', $bid_api->pro_id)->select('rating')->get();
             if ($rating_checker) {
                 $pro_rating = $rating_checker->avg('rating');
             } elseif ($rating_checker == null) {
                $pro_rating = 0;
            } 
             


            $task_counter = $task_done_1+$task_done_2;

            $all_bids[]=array(
                'bid_id' => $bid_api->bid_id,
                'bid_message' => $bid_api->bid_message,
                'bid_amount' => '₦'.$bid_api->bid_amount,
                'profile_photo' => $profile_url,
                'pro_name' => $bid_api->business_name,
                'bid_status' => $bid_api->bid_status,
                'pro_id' => $bid_api->pro_id,
                'cus_id' => $bid_api->cus_id,
                'pro_city' => $bid_api->pro_city,
                'project_id' => $bid_api->project_id,
                'task_done' => $task_counter,
                'pro_rating' => $pro_rating,
                'created_at' => $date->diffForHumans(),
            );

            $bid_track_checker = TaskTracker::where('bid_id','=', $bid_api->bid_id)->where('track_type','=', 'bid_opened')->first();

          if (!$bid_track_checker) {
            $track_message =  'you opened  ' . $bid_api->business_name. ' bid';
            $tracker = [
                'user_id' => $bid_api->cus_id,
                'project_id' => $bid_api->project_id,
                'bid_id' => $bid_api->bid_id,
                'track_type' => "bid_opened",
                'message' => $track_message,
            ];
            TaskTracker::create($tracker);
          } 
            $set['UBUYAPI_V1'] = $all_bids;
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
    public function apiNotification()
    {
        if (isset($_GET['user_id'])) {
            $user_id = filter_input(INPUT_GET, 'user_id', FILTER_SANITIZE_STRING);

            $user = User::find($user_id);
            if($user){
                $notifications = $user->notifications;

                if ($notifications) {
                    foreach($notifications as $notify){
              
                        
                        $date = Carbon::parse($notify->created_at); // now date is a carbon instance
    
                        /* checking the type of notify we have*/
                        if ($notify->type == "App\Notifications\NewTask") {
                           
                            $row[]=array(
                                'cus_id' => $notify->data['cus_id'],
                                'project_id' =>  $notify->data['project_id'],
                                'notify_msg' =>  $notify->data['cus_name'].' just posted a  '.$notify->data['project_name'].' task',
                                'created_at' => $date->diffForHumans(),
                                'notify_type' => 1,
                                'notify_status' => $notify->status,
                                'notify_url' => null
                            );

                        }elseif ( $notify->type == "App\Notifications\CusTransactNotify") {
                            if ($notify->data['pro_id'] == $user->id) {
                                $row[]=array(
                                    'project_id' =>  $notify->data['project_id'],
                                    'notify_msg' =>  $notify->data['cus_name'].' Has made Payment and awarded you a '.$notify->data['project_name'].' task',
                                    'created_at' => $date->diffForHumans(),
                                    'notify_type' => 0,
                                    'bid_id' => null,
                                    'notify_status' => $notify->status,
                                    'notify_url' => null
    
    
                                );
                            }
                           
                        }

                        if ($notify->status == 0) {
                            Notification::where('notifiable_id',$notify->notifiable_id)
                            ->update(['status' => 1]);
                        }
                       
                    }  
                }
            }
      
        
        $set['UBUYAPI_V1'] = $row;
        header( 'Content-Type: application/json; charset=utf-8' );
        echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
        die();


       

    }
        }
    public function apiSaveTask()
    {
                if (isset($_GET['pro_id']) &&  isset($_GET['project_id']) ) {
                    $user_id = filter_input(INPUT_GET, 'pro_id', FILTER_SANITIZE_STRING);
                    $project_id = filter_input(INPUT_GET, 'project_id', FILTER_SANITIZE_STRING);

                    $user = User::find($user_id);
                    if($user){
                        $project = Project::where('id', $project_id)->first();
                        if ($project) {
                            $data = [
                                'user_id' => $user->id,
                                'project_id' => $project->id,
                            ];
                            FavoriteTask::create($data);

                            $row[]=array(
                                'msg' =>  $project->sub_category_name.' task added to favorite',
                            );

                        } else {
                            $row[]=array(
                                'msg' =>  "Sorry that task could't be found",
                            );
                        }                
                    }else {
                        $row[]=array(
                            'msg' =>  "Sorry that profile is not found",
                        );
                    }
            
                
                $set['UBUYAPI_V1'] = $row;
                header( 'Content-Type: application/json; charset=utf-8' );
                echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
                die();
            }
    }
    public function apiAllFavTasks()
    {
                if (isset($_GET['pro_id'])) {
                    $user_id = filter_input(INPUT_GET, 'pro_id', FILTER_SANITIZE_STRING);

                    $user = User::find($user_id);
                    if($user){

                        $favorites = DB::table("favorite_tasks")
                        ->where('favorite_tasks.user_id','=', $user->id)
                        ->join('projects', 'favorite_tasks.project_id', '=', 'projects.id')
                        ->select('projects.id', 'favorite_tasks.user_id as pro_id', 'projects.project_title', 'projects.user_id', 'projects.cus_name', 'projects.created_at', 'projects.sub_category_name', 'projects.sub_category_id','projects.city', 'projects.state', 'projects.project_message')
                    ->get();
                    
                    if (!$favorites->isEmpty()) {
                            $row[]=$favorites;

                        } else {
                            $row[]=array(
                                'msg' =>  "No saved task found",
                            );
                        }                
                    }else {
                        $row[]=array(
                            'msg' =>  "Sorry that profile is not found",
                        );
                    }
            
                
                $set['UBUYAPI_V1'] = $row;
                header( 'Content-Type: application/json; charset=utf-8' );
                echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
                die();
            }
    }

    public function apiHomeStats()
        {
        
            if (isset($_GET['user_id'])) {
                $user_id = filter_input(INPUT_GET, 'user_id', FILTER_SANITIZE_STRING);

                $user = User::where('id', $user_id)->first();
                // $user_pro = Profile::select('id', 'lat', 'lng', 'pro_city', 'pro_state', 'distance')
                // ->where('user_id','=', $user->id)
                // ->first();

                if ($user) {
                    /* upay */
                    $upay_balance = UpayTransaction::where('user_id',$user->id)->where('status',1)->get();
                    $upay = $upay_balance->sum('amount');

                    $transact_percent = 9.5;
                    $percent_total = ($transact_percent/100)*$upay;
                    $transact_total = $upay - $percent_total;



                    $home_stats = [
                        'user_id' => $user->id,
                        'upay_balance' => '₦'.$transact_total,
                        'task_completed' =>$user->projectbids->where('bid_status', 2)->count(),
                        'bids_sent' => $user->projectbids->count(),
                        'review' => 0,
                        'success'=>'1'
                    ];

                        $set['UBUYAPI_V1'] = $home_stats;
                }
                else {
                    $set['UBUYAPI_V1'][]=array('msg' =>'Account not found','success'=>'0');
                }

            }
            header( 'Content-Type: application/json; charset=utf-8' );
            echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
            die();
        }
        

        public function apiRecentFeeds()
        {
        
            if (isset($_GET['user_id'])) {
                $user_id = filter_input(INPUT_GET, 'user_id', FILTER_SANITIZE_STRING);

                $user = User::where('id', $user_id)->first();
                
                if ($user) {
                    $user_pro = Profile::select('id', 'lat', 'lng', 'pro_city', 'pro_state', 'distance')
                    ->where('user_id','=', $user->id)
                    ->first();
                    if ($user_pro) {

                             
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
                        })->select('projects.id', 'services.user_id as pro_id', 'projects.project_title', 'projects.user_id', 'projects.cus_name', 'projects.created_at', 'projects.sub_category_name', 'projects.sub_category_id','projects.city', 'projects.state', 'projects.project_message')
                        ->orderBy('projects.id', 'desc')->get()->take(6);


                        $set['UBUYAPI_V1'] = $available_request;

                    } else {
                        $set['UBUYAPI_V1'][]=array('msg' =>'Sorry, Please create a business account to see recent tasks','success'=>'0');
                    }
                    
                }
                else {
                    $set['UBUYAPI_V1'][]=array('msg' =>'Account not found','success'=>'0');
                }

            }
            header( 'Content-Type: application/json; charset=utf-8' );
            echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
            die();
        }
        
  
    
        public function apiStates()
        {
        
            $state = State::select('id','name')->get();
            $set['UBUYAPI_V1'] = $state;
            header( 'Content-Type: application/json; charset=utf-8' );
            echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
            die();
        }
        

        public function singleProjectApi(){
            
            if (isset($_GET['project_id']) && isset($_GET['pro_id'])) {
                $project_id = filter_input(INPUT_GET, 'project_id', FILTER_SANITIZE_STRING);
                $pro_id = filter_input(INPUT_GET, 'pro_id', FILTER_SANITIZE_STRING);

                
                $user = User::where('id', $pro_id)->first();

                $project = Project::where('id', $project_id)->first();
                $bid_project = ProjectBid::where('project_id',$project_id)
                         ->where('user_id',$pro_id)->first();

                
                if ($bid_project == true) {
                   $bid_status = 1;
                } else {
                    $bid_status = 0;
                    # code...
                }
                
                
                if ($project) {

                    $date = Carbon::parse($project->created_at); // now date is a carbon instance

                    $project_info[]=array(
                    
                        'project_id' => $project->id,
                        'user_id' => $project->user_id,
                        'cus_name' => $project->cus_name,
                        'project_title' => $project->sub_category_name,
                        'project_message' => $project->project_message,
                        'project_address' => $project->address,
                        'project_date' => $date->diffForHumans(),
                        'pro_bid_status' => $bid_status,

                        );

                    $row['project'] = $project_info;


                    $details = DB::table("responses")
                            ->where('responses.user_id','=', $project->user_id)
                            ->where('responses.project_id','=', $project->id)
                            ->where('responses.sub_category_id','=', $project->sub_category_id)
                            ->join('questions', 'responses.question_id', '=', 'questions.id')
                            ->join('choices', 'responses.choice_id', '=', 'choices.id')
                            ->select('questions.text AS ques_text', 'choices.text AS choice_text')
                        ->get();
                        
                        if ($details) {
                           $row['details'] = $details;
                        }

                        $set['UBUYAPI_V1'] = $row;
                } else {
                  
                        $set['UBUYAPI_V1'][]=array('msg' =>'Sorry, project not found','success'=>'0');
                }
                
            }
            header( 'Content-Type: application/json; charset=utf-8' );
            echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
            die();
          
    
        }
        public function sendBidApi(){
            
            if (isset($_GET['project_id']) && isset($_GET['pro_id']) && isset($_GET['cus_id']) && isset($_GET['bid_message']) && isset($_GET['bid_amount'])
            && isset($_GET['bid_duration'])) {
                $project_id = filter_input(INPUT_GET, 'project_id', FILTER_SANITIZE_STRING);
                $user_id = filter_input(INPUT_GET, 'pro_id', FILTER_SANITIZE_STRING);
                $cus_id = filter_input(INPUT_GET, 'cus_id', FILTER_SANITIZE_STRING);
                $bid_message = filter_input(INPUT_GET, 'bid_message', FILTER_SANITIZE_STRING);
                $bid_amount = filter_input(INPUT_GET, 'bid_amount', FILTER_SANITIZE_STRING);
                $bid_duration = filter_input(INPUT_GET, 'bid_duration', FILTER_SANITIZE_STRING);

                $text = preg_replace('/\+?[0-9][0-9()\-\s+]{4,20}[0-9]/', '[blocked]', $bid_message);
                
                $user = User::where('id', $user_id)->first();

                if ($user) {

                    $bid = [
                        'project_id' => $project_id,
                        'user_id' => $user_id,
                        'cus_id' => $cus_id,
                        'bid_message' => $text,
                        'bid_amount' => $bid_amount,
                        'bid_duration' => $bid_duration,
                    ];
                 $bid_saved =    ProjectBid::create($bid);
                 if ($bid_saved) {
                    $update_bid[]=array(
                        'bid_id' => $bid_saved->id,
                        'msg' => "Bid Sent",
                    );
                 } else {
                    $update_bid[]=array(
                        'msg' => "Error saving bid please fill all forms",
                    );
                 }
                 
                   


                        $set['UBUYAPI_V1'] = $update_bid;
                } else {
                  
                        $set['UBUYAPI_V1'][]=array('msg' =>'Sorry, user not found','success'=>'0');
                }
                
            }
            header( 'Content-Type: application/json; charset=utf-8' );
            echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
            die();
          
    
        }
        public function saveServices(){
            
            if (isset($_GET['sub_category_id']) && isset($_GET['user_id']) && isset($_GET['service_name'])) {
                $subcat_id = filter_input(INPUT_GET, 'sub_category_id', FILTER_SANITIZE_STRING);
                $user_id = filter_input(INPUT_GET, 'user_id', FILTER_SANITIZE_STRING);
                $service_name = filter_input(INPUT_GET, 'service_name', FILTER_SANITIZE_STRING);

                
                $user = User::where('id', $user_id)->first();

                if ($user) {

                    $service = [
                        'sub_category_id' => $subcat_id,
                        'user_id' => $user_id,
                        'service_name' => $service_name,
                    ];
                 $service_saved =    Service::create($service);
                 if ($service_saved) {
                    $json[]=array(
                        'service_id' => $service_saved->id,
                        'msg' => "Service added",
                    );
                 } else {
                    $json[]=array(
                        'msg' => "Error saving service please select a service",
                    );
                 }
                 
                   


                        $set['UBUYAPI_V1'] = $json;
                } else {
                  
                        $set['UBUYAPI_V1'][]=array('msg' =>'Sorry, user not found','success'=>'0');
                }
                
            }
            header( 'Content-Type: application/json; charset=utf-8' );
            echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
            die();
          
    
        }

        public function updateBidApi(){
            
            if (isset($_GET['bid_id'])  && isset($_GET['pro_id'])  && isset($_GET['bid_message']) && isset($_GET['bid_amount'])
            && isset($_GET['bid_duration'])) {
                $bid_id = filter_input(INPUT_GET, 'bid_id', FILTER_SANITIZE_STRING);
                $user_id = filter_input(INPUT_GET, 'pro_id', FILTER_SANITIZE_STRING);
                $bid_message = filter_input(INPUT_GET, 'bid_message', FILTER_SANITIZE_STRING);
                $bid_amount = filter_input(INPUT_GET, 'bid_amount', FILTER_SANITIZE_STRING);
                $bid_duration = filter_input(INPUT_GET, 'bid_duration', FILTER_SANITIZE_STRING);

                $text = preg_replace('/\+?[0-9][0-9()\-\s+]{4,20}[0-9]/', '[blocked]', $bid_message);

                
                $user = User::where('id', $user_id)->first();
                $bid = ProjectBid::where('id', $bid_id)->first();

                if ($bid_duration == null) {
                    $bid_duration = $bid->bid_duration;
                } 
                if ($bid_message == null) {
                    $bid_message = $bid->bid_message;     
                }
                if ($bid_amount == null) {
                    $bid_amount = $bid->bid_amount;     
                }
                

                if ($bid) {
                    $bid_update = [
                           
                        'bid_message' => $text,
                        'bid_amount' => $bid_amount,
                        'bid_duration' => $bid_duration,
                    ];
                 $bid_saved =    ProjectBid::where('id', $bid->id)->update($bid_update);

                 if ($bid_saved) {
                    $update_bid[]=array(
                        'bid_id' => $bid->id,
                        'msg' => "Bid updated",
                    );
                 } else {
                    $update_bid[]=array(
                        'msg' => "Error updating bid please fill all forms",
                    );
                 }
                 
                 $set['UBUYAPI_V1'] = $update_bid;
                } else {
                  
                        $set['UBUYAPI_V1'][]=array('msg' =>'Sorry, bid not found','success'=>'0');
                }
                
            }
            header( 'Content-Type: application/json; charset=utf-8' );
            echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
            die();
          
    
        }
        public function apiUpdateProfile(){
            
            if (isset($_GET['user_id'])  && isset($_GET['first_name'])  && isset($_GET['last_name']) && isset($_GET['email'])
            && isset($_GET['number'])) {
                $user_id = filter_input(INPUT_GET, 'user_id', FILTER_SANITIZE_STRING);
                $first_name = filter_input(INPUT_GET, 'first_name', FILTER_SANITIZE_STRING);
                $last_name = filter_input(INPUT_GET, 'last_name', FILTER_SANITIZE_STRING);
                $email = filter_input(INPUT_GET, 'email', FILTER_SANITIZE_STRING);
                $number = filter_input(INPUT_GET, 'number', FILTER_SANITIZE_STRING);


                
                $user = User::where('id', $user_id)->first();

               
                

                if ($user) {
                    if ($first_name == null) {
                        $first_name = $user->first_name;
                    } 
                    if ($last_name == null) {
                        $last_name = $user->$last_name;     
                    }
                    if ($email == null) {
                        $email = $user->email;     
                    }
                    if ($number == null) {
                        $number = $user->number;     
                    }
                    $user_update = [
                           
                        'last_name' => $last_name,
                        'first_name' => $first_name,
                        'email' => $email,
                        'number' => $number,
                    ];
                 $update_saved =    User::where('id', $user->id)->update($user_update);

                 if ($update_saved) {
                    $update_user[]=array(
                        'user_id' => $user->id,
                        'msg' => "User updated",
                    );
                 } else {
                    $update_user[]=array(
                        'msg' => "Error updating user please fill all forms",
                    );
                 }
                 
                 $set['UBUYAPI_V1'] = $update_user;
                } else {
                  
                        $set['UBUYAPI_V1'][]=array('msg' =>'Sorry, user not found','success'=>'0');
                }
                
            }
            header( 'Content-Type: application/json; charset=utf-8' );
            echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
            die();
          
    
        }


        public function ProPassUpdate()
        {
            if (isset($_GET['user_id'])  && isset($_GET['old_password'])  && isset($_GET['new_password']) && isset($_GET['new_password_confirmation'])) {

            $user_id = filter_input(INPUT_GET, 'user_id', FILTER_SANITIZE_STRING);
                $old_password = filter_input(INPUT_GET, 'old_password', FILTER_SANITIZE_STRING);
                $new_password = filter_input(INPUT_GET, 'new_password', FILTER_SANITIZE_STRING);
                $new_password_confirmation = filter_input(INPUT_GET, 'new_password_confirmation', FILTER_SANITIZE_STRING);
    
            
           $user = User::where('id', $user_id)->first();                 
    
            if($user)
            {
    
                if(Hash::check($old_password, $user->password))
                {
                    $user->password = Hash::make($new_password);
                    $user->save();
                    $pass_json[]=array(
                        'msg' => "password changed",
                    );
                }else {
                    # code...
                    $pass_json[]=array(
                        'msg' => "Error updating user password, please check old password",
                    );
                }
            }
        }else{

            $pass_json[]=array(
             'msg' => "Error updating user password, please check old password",
             );
        }

            $set['UBUYAPI_V1'] = $pass_json;
            header( 'Content-Type: application/json; charset=utf-8' );
            echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
            die();
     
        }

        public function proServices(){
            if (isset($_GET['user_id'])) {

                $user_id = filter_input(INPUT_GET, 'user_id', FILTER_SANITIZE_STRING);
            $json = Service::where('user_id', $user_id)->get();
            }
            else {
                $json[]=array(
                    'msg' => "User not found",
                    );
            }
            $set['UBUYAPI_V1'] = $json;
            header( 'Content-Type: application/json; charset=utf-8' );
            echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
            die();
          
    
        }
        public function allServices(){
            $json = SubCategory::select('id', 'name')->get();

            $set['UBUYAPI_V1'] = $json;
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


        public function singleBidApi(){
            if (isset($_GET['project_id'])  && isset($_GET['pro_id'])) {

            $id = filter_input(INPUT_GET, 'project_id', FILTER_SANITIZE_STRING);
            $pro_id = filter_input(INPUT_GET, 'pro_id', FILTER_SANITIZE_STRING);

              $bid = ProjectBid::where('project_id',$id)
                         ->where('user_id',$pro_id)->first();

            if ($bid) {
                 $profile = Profile::where('user_id','=', $bid->user_id)->first();
            $user = User::where('id','=', $bid->user_id)->first();
            
                if ($user->image) {
                    $image = 'https://ubuy.ng/uploads/images/profile_pics/'.$user->image;
                } else{
                    $image = 'https://ubuy.ng/mvp_ui/imagess/c/iconhat_user_icon.svg';

                }
                $date = Carbon::parse($bid->created_at); // now date is a carbon instance

                if ($bid->status == 0) {
                    $bid_status = "Pending";
                } else {
                    $bid_status = "Opened";
                }
                
            
            $bid_data[] = array(
                'bid_id' => $bid->id,
                'project_id' => $bid->project_id,
                'pro_id' => $bid->user_id,
                'cus_id' => $bid->cus_id,
                'user_image' => $image,
                'pro_name' => $profile->business_name,
                'bid_status' => $bid_status,
                'bid_message' => $bid->bid_message,
                'bid_amount' => '₦'.$bid->bid_amount,
                'bid_date' => $date->diffForHumans()

            );

            
        } else {
            $bid_data[]=array(
                'msg' => "Sorry bid not found",
            );
        }
        } else {
            $bid_data[]=array(
                'msg' => "Sorry bid not found",
            );
        }
        $set['UBUYAPI_V1'] = $bid_data;
            
            header( 'Content-Type: application/json; charset=utf-8' );
            echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
            die();
          
    
        }
        public function proProjectFilesApi(){
            if (isset($_GET['project_id'])  && isset($_GET['bid_id'])) {

            $id = filter_input(INPUT_GET, 'project_id', FILTER_SANITIZE_STRING);
            $bid_id = filter_input(INPUT_GET, 'bid_id', FILTER_SANITIZE_STRING);

              $files = ProjectFile::where('project_id',$id)
                         ->where('bid_id',$bid_id)->get();

            if ($files) {
              
                foreach ($files as $file) {
                    if ($file->file_type == 'Image') {
                        $file_data[] = array(
                            'file_id' => $file->id,
                            'project_id' => $file->project_id,
                            'bid_id' => $file->bid_id,
                            'file_url' => 'https://ubuy.ng/project_files/'.$file->project_id.'/'.$file->file_name,
                            'is_image' => 1,
                            'file_type' => $file->file_type,
                            'file_name' => $file->file_name,
                        );
                    }else {
                        $file_data[] = array(
                            'file_id' => $file->id,
                            'project_id' => $file->project_id,
                            'bid_id' => $file->bid_id,
                            'file_url' => 'https://ubuy.ng/project_files/'.$file->project_id.'/'.$file->file_name,
                            'is_image' => 0,
                            'file_type' => $file->file_type.' File',
                            'file_name' => $file->file_name,
                        );
                    }
                   
        
                }
            
            } else {
                $file_data[]=array(
                    'msg' => "Sorry no files uploaded for this project",
                );
            }
            } else {
                $file_data[]=array(
                    'msg' => "Sorry file not found",
                );
            }
            $set['UBUYAPI_V1'] = $file_data;
            
            header( 'Content-Type: application/json; charset=utf-8' );
            echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
            die();
          
    
        }

        public function BidChatStatusApi(){
            $id = filter_input(INPUT_GET, 'bid_id', FILTER_SANITIZE_STRING);

            $bid =  ProjectBid::where('id','=', $id)->first();
            $project =  Project::where('id','=', $bid->project_id)->first();
            $profile = Profile::where('user_id','=', $bid->user_id)->first();
            $user = User::where('id','=', $bid->user_id)->first();
            $cus_user = User::where('id','=', $bid->cus_id)->first();
            
                if ($user->image) {
                    $image = 'https://ubuy.ng/uploads/images/profile_pics/'.$user->image;
                } else{
                    $image = 'https://ubuy.ng/mvp_ui/imagess/c/iconhat_user_icon.svg';

                }
                if ($cus_user->image) {
                    $cus_image = 'https://ubuy.ng/uploads/images/profile_pics/'.$cus_user->image;
                } else{
                    $cus_image = 'https://ubuy.ng/mvp_ui/imagess/c/iconhat_user_icon.svg';

                }
                $date = Carbon::parse($bid->created_at); // now date is a carbon instance

                if ($bid->bid_status == 0) {
                    $bid_status = "Bid Pending";
                } else {
                    $bid_status = "chat opened";
                }
                
            
            $chat_status[] = array(
                'bid_id' => $bid->id,
                'project_id' => $bid->project_id,
                'pro_id' => $bid->user_id,
                'cus_id' => $bid->cus_id,
                'user_image' => $image,
                'cus_image' => $cus_image,
                'cus_name' => $cus_user->first_name.' '.$cus_user->last_name,
                'pro_name' => $profile->business_name,
                'bid_status' => $bid_status,
                'bid_message' => $bid->bid_message,
                'bid_amount' => '₦'.$bid->bid_amount,

            );

            $set['UBUYAPI_V1'] = $chat_status;
            header( 'Content-Type: application/json; charset=utf-8' );
            echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
            die();
          
    
        }
        public function profileMainApi(){
            $pro_id = filter_input(INPUT_GET, 'user_id', FILTER_SANITIZE_STRING);

            
            $profile = Profile::where('user_id','=', $pro_id)->first();
            if ($profile) {
                # code...
                $user = User::where('id','=', $profile->user_id)->first();
                $licence = ProCredential::where('user_id', '=', $user->id)->first();
                $user_checker = $user->verify_confirm;

                    if ($user->image) {
                        $image = 'https://ubuy.ng/uploads/images/profile_pics/'.$user->image;
                    } else{
                        $image = 'https://ubuy.ng/mvp_ui/imagess/c/iconhat_user_icon.svg';
    
                    }
                

                    if ($user_checker == 0 && $licence == true) {
                        $verify_status = '50%';
                    }elseif ($user_checker == 1 && $licence == true) {
                        $verify_status = '50%';
                     }elseif ($user_checker == 2 && $licence == true) {
                        $verify_status = '100%';
                     }else{
                        $verify_status = '0%';
                     }
                
                $pro[] = array(
                    'pro_id' => $user->id,
                    'user_image' => $image,
                    'pro_name' => $profile->business_name,
                    'pro_credit' => '0',
                    'pro_address' => $profile->city,
                    'verify_status' => $verify_status,
    
                );
            }else {
                $pro[]=array(
                    'msg' => "Error, profile not found",
                    );
            }

            $set['UBUYAPI_V1'] = $pro;
            header( 'Content-Type: application/json; charset=utf-8' );
            echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
            die();
          
    
        }
        public function verifyToken(){
            $pro_id = filter_input(INPUT_GET, 'user_id', FILTER_SANITIZE_STRING);

            
            $user = User::where('id','=', $pro_id)->first();
            if ($user) {
                # code...
                $licence = ProCredential::where('user_id', '=', $user->id)->first();
                $verify_code = rand(145252,954856);

                   
                    if ($user->verify_confirm == 2) {
                        $verify_status = 'Verified';
                        $pro[] = array(
                            'pro_id' => $user->id,
                            'verify_status' => $verify_status,
                            'verify_code' => $user->verify_code,
                            'verify_image' => 'https://ubuy.ng/uploads/images/verification/'.$user->verify_image,
                            'msg' => 'Profile verified',
            
                        );
                    }elseif ($user->verify_image == true & $user->verify_confirm == 1) {
                        $verify_status = 'pending';
                        $pro[] = array(
                            'pro_id' => $user->id,
                            'verify_status' => $verify_status,
                            'verify_code' => $user->verify_code,
                            'verify_image' => 'https://ubuy.ng/uploads/images/verification/'.$user->verify_image,
                            'msg' => 'Pending verification',
            
                        );
                    }else{
                        $verify_status = 'not verified';
                        $pro[] = array(
                            'pro_id' => $user->id,
                            'verify_status' => $verify_status,
                            'verify_code' => $verify_code,
                            'verify_image' => null,
                            'msg' => 'Not verified',
            
                        );
                    }
                
               
            }else {
                $pro[]=array(
                    'msg' => "Error, profile not found",
                    );
            }

            $set['UBUYAPI_V1'] = $pro;
            header( 'Content-Type: application/json; charset=utf-8' );
            echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
            die();
          
    
        }
        public function verifyChecker(){
            $pro_id = filter_input(INPUT_GET, 'user_id', FILTER_SANITIZE_STRING);

            
            $user = User::where('id','=', $pro_id)->first();
            if ($user) {
                # code...
                $licence = ProCredential::where('user_id', '=', $user->id)->first();

                   
                    if ($user->verify_confirm == 2) {
                        $verify_status = 'Verified';
                    }elseif ($user->verify_image == true & $user->verify_confirm == 1) {
                        $verify_status = 'pending';
                    }else{
                        $verify_status = 'not verified';
                    }


                    if ($user->verify_confirm == 2) {
                        $id_status = 'Verified';
                        
                        
                    }elseif ($licence == true & $user->verify_confirm == 1) {
                        $id_status = 'pending';
                    }else{
                        $id_status = 'not verified';
                    }


                
                    $pro[] = array(
                        'pro_id' => $user->id,
                        'face_status' => $verify_status,
                        'id_status' => $id_status,
        
                    );
               
            }else {
                $pro[]=array(
                    'msg' => "Error, profile not found",
                    );
            }

            $set['UBUYAPI_V1'] = $pro;
            header( 'Content-Type: application/json; charset=utf-8' );
            echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
            die();
          
    
        }

        // save pro credentials
public function storeProCrd(Request $request){
   
   

   $user_id = $request->user_id;
   $licence_type = $request->licence_type;
   $licence_number = $request->licence_number;
   $licence_state = $request->licence_state;
   $licence_username = $request->licence_username;
   $base_img = $request->base_img;

   if ($user_id && $licence_type && $licence_number && $licence_state && $licence_username && $base_img) {
             $name = null;
           if($base_img){
                $name = time().'.' . explode('/', explode(':', substr($base_img, 0, strpos(
                    $base_img, ';'
                )))[1])[1];
                $image = $base_img;
                $thumbnailPath = 'public/uploads/images/pro_licence/'.$name;
                $resized_thumb = Image::make($base_img)->stream('jpg', 90);
                    Storage::disk('public')->put($thumbnailPath, $resized_thumb->__toString());                                        
             }
        
                $data = [
                    'licence_type' => $request->licence_type,
                    'licence_number' => $request->licence_number,
                    'licence_state' => $request->licence_state,
                    'licence_username' => $request->licence_username,
                    'user_id' => $request->user_id,
                ];

                if ($name){
                    $data['licence_photo'] = $name;
                }

                ProCredential::create($data);
                $pro[]=array(
                    'msg' => "Id saved",
                    );
   }else{
    $pro[]=array(
        'msg' => "Error, profile not found",
        );
    }
        

            $set['UBUYAPI_V1'] = $pro;
            header( 'Content-Type: application/json; charset=utf-8' );
            echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
            die();
    // return back()->with('licence_saved', 'Your licence has been uploaded');
}
public function saveVerifyPic(Request $request){
   
   $user_id = $request->user_id;
   $verify_code = $request->verify_code;
   $base_img = $request->base_img;

   if ($user_id && $verify_code && $base_img) {


           $base_img = $request->base_img;
           $name = null;
           if($base_img){
                $name = time().'.' . explode('/', explode(':', substr($base_img, 0, strpos(
                    $base_img, ';'
                )))[1])[1];
                $image = $base_img;
                $thumbnailPath = 'public/uploads/images/verification/'.$name;
                $resized_thumb = Image::make($base_img)->stream('jpg', 90);
                    Storage::disk('public')->put($thumbnailPath, $resized_thumb->__toString());                                        
             }
        
             $data = [
                'verify_code' => $request->verify_code,
                'verify_confirm' => 1,
            ];
    
            if ($name){
                $data['verify_image'] = $name;
            }
            
            User::where('id', $user_id)->update($data);
            $pro[]=array(
                    'msg' => "verification saved",
                    );

   }else {
            $pro[]=array(
                'msg' => "Error, profile not found",
                );
            }

            $set['UBUYAPI_V1'] = $pro;
            header( 'Content-Type: application/json; charset=utf-8' );
            echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
            die();
    // return back()->with('licence_saved', 'Your licence has been uploaded');
}
public function updateDistance(){
   $user_id = filter_input(INPUT_GET, 'user_id', FILTER_SANITIZE_STRING);
   $distance = filter_input(INPUT_GET, 'distance', FILTER_SANITIZE_STRING);

   $profile = Profile::where('user_id', $user_id)->first();
   if ($profile) {
             $data = [
                'distance' => $distance
            ];
            Profile::where('user_id', $user_id)->update($data);
            $pro[]=array(
                    'msg' => "Distance Changed",
                    );

   }else {
            $pro[]=array(
                'msg' => "Error, profile not found",
                );
            }

            $set['UBUYAPI_V1'] = $pro;
            header( 'Content-Type: application/json; charset=utf-8' );
            echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
            die();
    // return back()->with('licence_saved', 'Your licence has been uploaded');
}
public function saveFiles(Request $request){
   
   $base64string = $request->file;

   $image = $request->file;
   $file = $request->file;
   $pro_id = $request->user_id;
   $project_id = $request->project_id;
   $bid_id = $request->bid_id;
   
   $project = Project::where('id', $project_id)->first();
   $profile = Profile::where('user_id', $pro_id)->first();

   $pro_name = $profile->business_name;
   $project_name = $project->sub_category_name;
   $cus_id = $project->user_id;

     

   //    $valid_extensions = ['jpg','jpeg','png', 'gif', 'docx', 'pdf', 'txt', 'doc', 'xls', 'xlsx', 'ppt', 'pptx', 'xml', 'zip'];
   //    $files_extensions = ['docx', 'pdf', 'txt', 'doc', 'xls', 'xlsx', 'ppt', 'pptx', 'xml', 'zip'];
   //    $image_extensions = ['jpg','jpeg','png', 'gif'];
   //    $doc_extensions = ['docx','doc'];
   //    $pdf_extensions = ['pdf'];
   //    $excel_extensions = ['xls', 'xlsx'];
   //    $ppt_extensions = ['ppt', 'pptx',];
   //    $zip_extensions = ['zip',];
   //    $other_extensions = ['xml', 'txt'];


   /* *****************/ //breaker /********** */
   $valid_extensions = ['jpg','jpeg','png', 'gif', 'pdf','zip', 'application/msword','application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'application/vnd.ms-powerpoint', 'application/vnd.openxmlformats-officedocument.presentationml.presentation'];
   $files_extensions = ['docx', 'pdf', 'txt', 'doc', 'xls', 'xlsx', 'ppt', 'pptx', 'xml', 'zip'];
   $image_extensions = ['jpg','jpeg','png', 'gif'];
   $doc_extensions = ['application/msword','application/vnd.openxmlformats-officedocument.wordprocessingml.document' ];
   $pdf_extensions = ['pdf'];
   $excel_extensions = ['application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'];
   $ppt_extensions = ['application/vnd.ms-powerpoint', 'application/vnd.openxmlformats-officedocument.presentationml.presentation'];
   $zip_extensions = ['zip'];
   $other_extensions = ['xml', 'plain'];


   $upload_extension = explode('/', mime_content_type($file))[1];


   if ( !in_array($upload_extension, $valid_extensions) ){
    $upload_response[] =array(
        'msg' => 'that file type is not allowed. kindly zip the file and try again',

    );
    

}else {

    if (in_array($upload_extension, $image_extensions) ){

        $name = $project_name.time().'.' . explode('/', explode(':', substr($file, 0, strpos($file, ';')))[1])[1];
        $image = $file;
        $thumbnailPath = '/public/project_files/'.$project_id.'/'.$name;
        $resized_thumb = Image::make($file)->stream('jpg', 90);
            Storage::disk('public')->put($thumbnailPath, $resized_thumb->__toString());  
            
            /* *** //store the file in the database and send message to customer // */
                       
    $data = [
        'project_id' => $project_id,
        'bid_id' => $bid_id,
        'pro_id' => $pro_id,
        'cus_id' => $cus_id,
        'project_name' => $project_name,
        'file_type' => 'Zip',
        'sender_name' => $pro_name,
        'file_name' => $base64,
    ];

  
    $message = $pro_name.' Sent you a file, click the files button below to preview file. file name is '. $base64  ;
    $chat = new Message;
    $chat->message = $message;
    $chat->sender_id = $pro_id;
    $chat->receiver_id = $cus_id;
    $chat->bid_id = $bid_id;
    $chat->project_id = $project_id;
    $chat->save();

    ProjectFile::create($data);
    $upload_response[] =array(
        'msg' => 'File Uploaded',
    );

    }elseif (in_array($upload_extension, $doc_extensions)) {
        $base64 = $project_name.time().".docx";
            if (strpos($file, ',') !== false) {
                @list($encode, $file) = explode(',', $file);
            }
            $destinationPath = "/public/project_files/".$project_id.'/'. $base64;             
            $base = base64_decode($file);  
            Storage::disk('public')->put($destinationPath, $base);  
            
            /* *** //store the file in the database and send message to customer // */
                       
    $data = [
        'project_id' => $project_id,
        'bid_id' => $bid_id,
        'pro_id' => $pro_id,
        'cus_id' => $cus_id,
        'project_name' => $project_name,
        'file_type' => 'Zip',
        'sender_name' => $pro_name,
        'file_name' => $base64,
    ];

  
    $message = $pro_name.' Sent you a file, click the files button below to preview file. file name is '. $base64  ;
    $chat = new Message;
    $chat->message = $message;
    $chat->sender_id = $pro_id;
    $chat->receiver_id = $cus_id;
    $chat->bid_id = $bid_id;
    $chat->project_id = $project_id;
    $chat->save();

    ProjectFile::create($data);
    $upload_response[] =array(
        'msg' => 'File Uploaded',
    );
        
    }elseif(in_array($upload_extension, $pdf_extensions)){
        $base64 = $project_name.time().'.pdf';
            if (strpos($file, ',') !== false) {
                @list($encode, $file) = explode(',', $file);
            }
            $destinationPath = "/public/project_files/".$project_id.'/'. $base64;             
            $base = base64_decode($file);  
            Storage::disk('public')->put($destinationPath, $base);
            
            /* *** //store the file in the database and send message to customer // */
                       
    $data = [
        'project_id' => $project_id,
        'bid_id' => $bid_id,
        'pro_id' => $pro_id,
        'cus_id' => $cus_id,
        'project_name' => $project_name,
        'file_type' => 'Zip',
        'sender_name' => $pro_name,
        'file_name' => $base64,
    ];

  
    $message = $pro_name.' Sent you a file, click the files button below to preview file. file name is '. $base64  ;
    $chat = new Message;
    $chat->message = $message;
    $chat->sender_id = $pro_id;
    $chat->receiver_id = $cus_id;
    $chat->bid_id = $bid_id;
    $chat->project_id = $project_id;
    $chat->save();

    ProjectFile::create($data);
    $upload_response[] =array(
        'msg' => 'File Uploaded',
    );

    }elseif(in_array($upload_extension, $excel_extensions)){
        $base64 = $project_name.time().'.xlsx';
            if (strpos($file, ',') !== false) {
                @list($encode, $file) = explode(',', $file);
            }
            $destinationPath = "/public/project_files/".$project_id.'/'. $base64;             
            $base = base64_decode($file);  
            Storage::disk('public')->put($destinationPath, $base); 
            
            /* *** //store the file in the database and send message to customer // */
                       
    $data = [
        'project_id' => $project_id,
        'bid_id' => $bid_id,
        'pro_id' => $pro_id,
        'cus_id' => $cus_id,
        'project_name' => $project_name,
        'file_type' => 'Zip',
        'sender_name' => $pro_name,
        'file_name' => $base64,
    ];

  
    $message = $pro_name.' Sent you a file, click the files button below to preview file. file name is '. $base64  ;
    $chat = new Message;
    $chat->message = $message;
    $chat->sender_id = $pro_id;
    $chat->receiver_id = $cus_id;
    $chat->bid_id = $bid_id;
    $chat->project_id = $project_id;
    $chat->save();

    ProjectFile::create($data);
    $upload_response[] =array(
        'msg' => 'File Uploaded',
    );

    }elseif(in_array($upload_extension, $ppt_extensions)){
        $base64 = $project_name.time().'.pptx';
            if (strpos($file, ',') !== false) {
                @list($encode, $file) = explode(',', $file);
            }
            $destinationPath = "/public/project_files/".$project_id.'/'. $base64;             
            $base = base64_decode($file);  
            Storage::disk('public')->put($destinationPath, $base); 
            
            /* *** //store the file in the database and send message to customer // */
                       
    $data = [
        'project_id' => $project_id,
        'bid_id' => $bid_id,
        'pro_id' => $pro_id,
        'cus_id' => $cus_id,
        'project_name' => $project_name,
        'file_type' => 'Zip',
        'sender_name' => $pro_name,
        'file_name' => $base64,
    ];

  
    $message = $pro_name.' Sent you a file, click the files button below to preview file. file name is '. $base64  ;
    $chat = new Message;
    $chat->message = $message;
    $chat->sender_id = $pro_id;
    $chat->receiver_id = $cus_id;
    $chat->bid_id = $bid_id;
    $chat->project_id = $project_id;
    $chat->save();

    ProjectFile::create($data);

    $upload_response[] =array(
        'msg' => 'File Uploaded',
    );

    }elseif(in_array($upload_extension, $zip_extensions)){
        $base64 = $project_name.time().'.zip';
            if (strpos($file, ',') !== false) {
                @list($encode, $file) = explode(',', $file);
            }            
            $destinationPath = "/public/project_files/".$project_id.'/'. $base64;             
            $base = base64_decode($file);  
            Storage::disk('public')->put($destinationPath, $base);   
            
            
    $data = [
        'project_id' => $project_id,
        'bid_id' => $bid_id,
        'pro_id' => $pro_id,
        'cus_id' => $cus_id,
        'project_name' => $project_name,
        'file_type' => 'Zip',
        'sender_name' => $pro_name,
        'file_name' => $base64,
    ];

  
    $message = $pro_name.' Sent you a file, click the files button below to preview file. file name is '. $base64  ;
    $chat = new Message;
    $chat->message = $message;
    $chat->sender_id = $pro_id;
    $chat->receiver_id = $cus_id;
    $chat->bid_id = $bid_id;
    $chat->project_id = $project_id;
    $chat->save();

    ProjectFile::create($data);
    $upload_response[] =array(
        'msg' => 'file uploaded',
    );
    }else {
        $upload_response[] =array(
            'msg' => 'that file type is not allowed. kindly zip the file and try again',
        );

    }


        
}

$set['UBUYAPI_V1'] = $upload_response;
header( 'Content-Type: application/json; charset=utf-8' );
echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
die();
}



public function saveProjectFile (Request $request)
{
  $project_id = $request->project_id;

    $thumbnail = null;
    if ($request->hasFile('files')){
        $image = $request->file('files');
        $file = $request->file('files');

        $valid_extensions = ['jpg','jpeg','png', 'gif', 'docx', 'pdf', 'txt', 'doc', 'xls', 'xlsx', 'ppt', 'pptx', 'xml', 'zip'];
        $files_extensions = ['docx', 'pdf', 'txt', 'doc', 'xls', 'xlsx', 'ppt', 'pptx', 'xml', 'zip'];
        $image_extensions = ['jpg','jpeg','png', 'gif'];
        $doc_extensions = ['docx','doc'];
        $pdf_extensions = ['pdf'];
        $excel_extensions = ['xls', 'xlsx'];
        $ppt_extensions = ['ppt', 'pptx',];
        $zip_extensions = ['zip',];
        $other_extensions = ['xml', 'txt'];

        /* @TODO: check if the item is an image */

        if (in_array(strtolower($image->getClientOriginalExtension()), $image_extensions)) {

        if ( ! in_array(strtolower($image->getClientOriginalExtension()), $valid_extensions) ){
            return redirect()->back()->withInput($request->input())->with('error', "'jpg','jpeg','png', 'gif', 'docx', 'pdf', 'txt', 'doc', 'xls', 'xlsx', 'ppt', 'pptx', 'xml', 'zip' files is allowed") ;
        }
        $file_base_name = str_replace('.'.$image->getClientOriginalExtension(), '', $image->getClientOriginalName());
        if (in_array(strtolower($image->getClientOriginalExtension()), $image_extensions)) {
             $resized_thumb = Image::make($image)->resize(512, 512)->stream();
        }else {
          $resized_thumb = '512';
        }

      //   echo   $resized_thumb;
        $thumbnail = strtolower(str_slug($file_base_name)).'.' . $image->getClientOriginalExtension();

        $thumbnailPath = '/uploads/project_files/'.$project_id.'/'.$thumbnail;

        try{
          if (in_array(strtolower($image->getClientOriginalExtension()), $image_extensions)) {
            Storage::disk('public')->put($thumbnailPath, $resized_thumb->__toString());
          }else{
              Storage::disk('public')->put($thumbnailPath, $resized_thumb);

          }
        } catch (\Exception $e){
          echo $e->getMessage();
            return redirect()->back()->withInput($request->input())->with('error', $e->getMessage()) ;
        }
    }
  elseif(in_array(strtolower($file->getClientOriginalExtension()), $files_extensions)) {

      if ( ! in_array(strtolower($file->getClientOriginalExtension()), $files_extensions) ){
          return redirect()->back()->withInput($request->input())->with('error', "'docx', 'pdf', 'txt', 'doc', 'xls', 'xlsx', 'ppt', 'pptx', 'xml', 'zip' files is allowed") ;
      }
      $file_base_name = str_replace('.'.$file->getClientOriginalExtension(), '', $file->getClientOriginalName());

       //   echo   $resized_thumb;
       $main_file = strtolower(str_slug($file_base_name)).'.' . $file->getClientOriginalExtension();

       $filePath = '/project_files/'.$project_id.'/'.$main_file;

       try{
         if (in_array(strtolower($file->getClientOriginalExtension()), $files_extensions)) {
          Storage::disk('public')->put($filePath, file_get_contents($file));
          
          
          //  Storage::disk('public')->put($filePath, $resized_thumb->__toString());
      }else{
          Storage::disk('public')->put($filePath, file_get_contents($file));

         }
       } catch (\Exception $e){
         echo $e->getMessage();
           return redirect()->back()->withInput($request->input())->with('error', $e->getMessage()) ;
       }

  }
  }

    /* @TODO: file check ends here */

    if (in_array(strtolower($image->getClientOriginalExtension()), $image_extensions)) {
      $file_type = 'Image';
      }elseif (in_array(strtolower($image->getClientOriginalExtension()), $doc_extensions)) {
          $file_type = 'Doc';
      }
      elseif (in_array(strtolower($image->getClientOriginalExtension()), $pdf_extensions)) {
          $file_type = 'Pdf';
      }
      elseif (in_array(strtolower($image->getClientOriginalExtension()), $excel_extensions)) {
          $file_type = 'Excel';
      }
      elseif (in_array(strtolower($image->getClientOriginalExtension()), $zip_extensions)) {
          $file_type = 'Zip';
      }
      elseif(in_array(strtolower($image->getClientOriginalExtension()), $other_extensions)) {
          $file_type = 'Others';
      } 
      
      
    $data = [
      'project_id' => $request->project_id,
      'bid_id' => $request->bid_id,
      'pro_id' => $request->pro_id,
      'cus_id' => $request->cus_id,
      'project_name' => $request->project_name,
      'file_type' => $file_type,
      'sender_name' => $request->sender_name,
  ];
 
  if ($thumbnail){
      $data['file_name'] = $thumbnail;
  }
  if ($main_file){
      $data['file_name'] = $main_file;
  }

  $message = $request->sender_name.' Sent you a file, click the files button below to preview file. file name is '. $thumbnail  ;
  $chat = new Message;
  $chat->message = $message;
  $chat->sender_id = $request->pro_id;
  $chat->receiver_id = $request->cus_id;
  $chat->bid_id = $request->bid_id;
  $chat->project_id = $request->project_id;
  $chat->save();

  ProjectFile::create($data);

  echo 'success';

  // return back()->with('success', trans('app.category_updated'));
}

        public function ProPersonalDetails(){
            $pro_id = filter_input(INPUT_GET, 'user_id', FILTER_SANITIZE_STRING);

            
            $user = User::where('id','=', $pro_id)->first();
            
                if ($user->image) {
                    $image = 'https://ubuy.ng/uploads/images/profile_pics/'.$user->image;
                } else{
                    $image = 'https://ubuy.ng/mvp_ui/imagess/c/iconhat_user_icon.svg';

                }
               
            
            $pro[] = array(
                'pro_id' => $user->id,
                'user_image' => $image,
                'user_firstname' => $user->first_name,
                'user_lastname' => $user->last_name,
                'user_number' => $user->number,
                'user_email' => $user->email,
            

            );

            $set['UBUYAPI_V1'] = $pro;
            header( 'Content-Type: application/json; charset=utf-8' );
            echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
            die();
          
    
        }

        public function service_destroy()
        {
            $user_id = filter_input(INPUT_GET, 'user_id', FILTER_SANITIZE_STRING);
            $service_id = filter_input(INPUT_GET, 'service_id', FILTER_SANITIZE_STRING);

    
            $delete = Service::where('id', $service_id)->delete();
            if ($delete){
                $json[]=array(
                    'msg' => "service deleted",
                    );
            }
            
            
            $set['UBUYAPI_V1'] = $json;
            header( 'Content-Type: application/json; charset=utf-8' );
            echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
            die();
        }

        public function bankCodes(){
            $client = new Client(['http_errors' => false]);
            $r = $client->request('GET', 'https://api.ravepay.co/v2/banks/ng?public_key='.env('FLUTTERWAVE_PUBLIC_KEY'), [
                'headers' => [
                    'Accept' => 'application/json'
                ]
            ]);
             $statuscode = $r->getStatusCode();
            if ($statuscode == 200) {            
                 $response = $r->getBody()->getContents();
                   $obj = json_decode($response);
                $banks = collect($obj->data->Banks);
            }else {
                $banks[]= array(
                    'msg' => "Error, problem getting banks",
                );
            }
            
            $set['UBUYAPI_V1'] = $banks;
            header( 'Content-Type: application/json; charset=utf-8' );
            echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
            die();
        }

        public function getBusiLocate()
        {
            if (isset($_GET['user_id'])) {
                $user_id = filter_input(INPUT_GET, 'user_id', FILTER_SANITIZE_STRING);
                $user_profile = Profile::where('user_id', $user_id)->first();

                $data[]=array(
                        'pro_address' => $user_profile->pro_address,
                        'pro_state' => $user_profile->pro_state,
                        'lat' => $user_profile->lat,
                        'lng' => $user_profile->lng,
                );
            }

            $set['UBUYAPI_V1'] = $data;
            header( 'Content-Type: application/json; charset=utf-8' );
            echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
            die();
        }
        public function getBusiDetail()
        {
            if (isset($_GET['user_id'])) {
                $user_id = filter_input(INPUT_GET, 'user_id', FILTER_SANITIZE_STRING);
                $user_profile = Profile::where('user_id', $user_id)->first();

                $data[]=array(
                    'business_name' =>  $user_profile->business_name,
                    'business_des' => $user_profile->about_profile,
                    'pro_address' => $user_profile->pro_address,
                    'pro_state' => $user_profile->pro_state,
                    'lat' => $user_profile->lat,
                    'lng' => $user_profile->lng,
                );
            }

            $set['UBUYAPI_V1'] = $data;
            header( 'Content-Type: application/json; charset=utf-8' );
            echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
            die();
        }
        public function updateBusiLocate(){    

            if (isset($_GET['user_id'])) {
                $user_id = filter_input(INPUT_GET, 'user_id', FILTER_SANITIZE_STRING);
                $profile_state = filter_input(INPUT_GET, 'state', FILTER_SANITIZE_STRING);
                $profile_address = filter_input(INPUT_GET, 'address', FILTER_SANITIZE_STRING);
                $profile_lat = filter_input(INPUT_GET, 'lat', FILTER_SANITIZE_STRING);
                $profile_lng = filter_input(INPUT_GET, 'lng', FILTER_SANITIZE_STRING);
                
                $userauth = User::where('id', '=', $user_id)->first();
    
                $user_profile = Profile::where('user_id', $userauth->id)->first();
    
    
                if($user_profile){   

                    if ($profile_address) {
                        $profile_address_data = $profile_address;
                    }else {
                        $profile_address_data = $user_profile->pro_address;
                    }
                    if ($profile_state) {
                        $profile_state_data = $profile_state;
                    }else {
                        $profile_state_data = $user_profile->pro_state;
                    }
                    if ($profile_lat) {
                        $profile_lat_data = $profile_lat;
                    }else {
                        $profile_lat_data = $user_profile->lat;
                    }
                    if ($profile_lng) {
                        $profile_lng_data = $profile_lng;
                    }else {
                        $profile_lng_data = $user_profile->lng;
                    }                
    
                    $data = [
                        'pro_address' => $profile_address_data,
                        'pro_state' => $profile_state_data,
                        'lat' => $profile_lat_data,
                        'lng' => $profile_lng_data,
                    ];   
            
                    Profile::where('user_id', $user_id)->update($data);
    
                     Auth::login($userauth);
                    $user = auth()->user();
            
                    $set['UBUYAPI_V1'][]=array(
                    'user_id' => $user->id,
                    'msg' => 'Address updated',
                    'success' => '1'
                );
                }
                else if (!$user_profile) {
                    $set['UBUYAPI_V1'][]=array('msg' =>"Sorry we can't find your business account",'success'=>'0');
    
                } 
                
            } else{
                $set['UBUYAPI_V1'][]=array('msg' =>'An Error as occoured, please check your details and try again','success'=>'0');
    
            }
            header( 'Content-Type: application/json; charset=utf-8' );
            echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
            die();          
        }
        public function updateBusi(){    

            if (isset($_GET['user_id'])) {
                $user_id = filter_input(INPUT_GET, 'user_id', FILTER_SANITIZE_STRING);
                $business_name = filter_input(INPUT_GET, 'business_name', FILTER_SANITIZE_STRING);
                $business_des = filter_input(INPUT_GET, 'business_des', FILTER_SANITIZE_STRING);
                
                $userauth = User::where('id', '=', $user_id)->first();
    
                $user_profile = Profile::where('user_id', $userauth->id)->first();
    
    
                if($user_profile){   

                    if ($business_name) {
                        $business_name_data = $business_name;
                    }else {
                        $business_name_data = $user_profile->business_name;
                    }
                    if ($business_des) {
                        $business_des_data = $business_des;
                    }else {
                        $business_des_data = $user_profile->about_profile;
                    }      
    
                    $data = [
                        'business_name'    => $business_name_data,
                    'about_profile'     => $business_des_data,
                    ];   
            
            Profile::where('user_id', $user_id)->update($data);
                
                    $set['UBUYAPI_V1'][]=array(
                    'business_name' => $business_name,
                    'business_des' => $business_des,
                    'msg' => 'Business profile updated',
                    'success' => '1'
                );
                }
                else if (!$user_profile) {
                    $set['UBUYAPI_V1'][]=array('msg' =>"Sorry we can't find your business account",'success'=>'0');
    
                } 
                
            } else{
                $set['UBUYAPI_V1'][]=array('msg' =>'An Error as occoured, please check your details and try again','success'=>'0');
    
            }
            header( 'Content-Type: application/json; charset=utf-8' );
            echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
            die();          
        }


        public function PostSaveBank()
            {

                if (isset($_GET['user_id'])  && isset($_GET['bank_code'])  && isset($_GET['bank_name']) && isset($_GET['account_name'])
                && isset($_GET['account_number'])) {

                    $user_id = filter_input(INPUT_GET, 'user_id', FILTER_SANITIZE_STRING);
                        $bank_code = filter_input(INPUT_GET, 'bank_code', FILTER_SANITIZE_STRING);
                        $bank_name = filter_input(INPUT_GET, 'bank_name', FILTER_SANITIZE_STRING);
                        $account_name = filter_input(INPUT_GET, 'account_name', FILTER_SANITIZE_STRING);
                        $account_number = filter_input(INPUT_GET, 'account_number', FILTER_SANITIZE_STRING);

            
                        $user = Auth::loginUsingId($user_id);

                        if ($user) {
                            $business = Profile::where('user_id', '=', $user->id)->first();
                            $user_bank = BankDetail::where('user_id', '=', $user->id)->first();
                    
                            if ($user_bank) {
                                if($user_bank->account_number == $account_number){
                                    $savebank[]= array(
                                        "msg" => 'Error!! Account number already exists'
                                    );
                                }
                                else{
                                                        
                                        /*         sending the data to flutterwave and getting response back
                                        */
                                        $client = new Client(['http_errors' => false]);
                                        $body = array(
                                            "account_bank"=>$bank_code,
                                            "account_number"=>$account_number,
                                            "business_name"=> $business->business_name,
                                            "business_email"=>$user->email,
                                            "business_contact"=>$user->first_name." ".$user->last_name,
                                            "business_contact_mobile"=>$user->number,
                                            "business_mobile"=>$user->number,
                                            "country"=>"NG",
                                            "meta"=>[
                                                    "metaname" => "UbuySecureID",
                                                    "metavalue"=>"ubuy_".$user->uuid
                                                ],
                                            "seckey"=>env('FLUTTERWAVE_SECRET_KEY')
                                        );
                                        $r = $client->request('POST', 'https://api.ravepay.co/v2/gpx/subaccounts/create', [
                                            'headers' => [
                                                'Accept' => 'application/json'
                                            ],
                                            'form_params' => $body,
                                        ]);
                                        $statuscode = $r->getStatusCode();
                                        if ($statuscode == 200) {                    
                                            $response = $r->getBody()->getContents();
                                            $obj = json_decode($response);
                                
                                        $data = [
                                            'user_id' => $user->id,
                                            'name' =>  $obj->data->fullname,
                                            'bank_code' =>  $obj->data->account_bank,
                                            'account_number' =>  $obj->data->account_number,
                                            'sub_account_id' =>  $obj->data->subaccount_id,
                                            'bank_name' =>  $obj->data->bank_name,
                                        ];
                    
                                    $details = BankDetail::where('id', $user_bank->id)->update($data);
                                    if ($details) {
                                        $savebank[]= array(
                                            "msg" => 'Bank Account updated',
                                        );
                                    }
                                        }else{
                                            $statusmsg = $r->getBody()->getContents();
                                            $obj = json_decode($statusmsg);
                                            $savebank[]= array(
                                                "msg" => 'Error!! '.$r->getStatusCode()." ".$obj->message.". Contact customer support",
                                            );
                                        }
                    
                                }
                            } else {
                                /*         sending the data to flutterwave and getting response back
                                */
                                    $client = new Client(['http_errors' => false]);
                                    $body = array(
                                        "account_bank"=>$bank_code,
                                        "account_number"=>$account_number,
                                        "business_name"=> $business->business_name,
                                        "business_email"=>$user->email,
                                        "business_contact"=>$user->first_name." ".$user->last_name,
                                        "business_contact_mobile"=>$user->number,
                                        "business_mobile"=>$user->number,
                                        "country"=>"NG",
                                        "meta"=>[
                                                "metaname" => "UbuySecureID",
                                                "metavalue"=>"ubuy_".$user->uuid
                                            ],
                                        "seckey"=>env('FLUTTERWAVE_SECRET_KEY')
                                    );
                                    $r = $client->request('POST', 'https://api.ravepay.co/v2/gpx/subaccounts/create', [
                                        'headers' => [
                                            'Accept' => 'application/json'
                                        ],
                                        'form_params' => $body,
                                    ]);
                                    $statuscode = $r->getStatusCode();
                                    if ($statuscode == 200) {                    
                                        $response = $r->getBody()->getContents();
                                        $obj = json_decode($response);
                                    
                                        
                    
                                    $data = [
                                        'user_id' => $user->id,
                                        'name' =>  $obj->data->fullname,
                                        'bank_code' =>  $obj->data->account_bank,
                                        'account_number' =>  $obj->data->account_number,
                                        'sub_account_id' =>  $obj->data->subaccount_id,
                                        'bank_name' =>  $obj->data->bank_name,
                                    ];
                    
                                $details = BankDetail::create($data);
                                $savebank[]= array(
                                    "bank_id" => $details->id,
                                    "user_id" => $details->user_id,
                                    "pro_name" => $details->name,
                                    "bank_code" => $details->bank_code,
                                    "account_number" => $details->account_number,
                                    "bank_name" => $details->bank_name,
                                );
                                    }else{
                                        $statusmsg = $r->getBody()->getContents();
                                        $obj = json_decode($statusmsg);
                                        $savebank[]= array(
                                            "msg" => 'Error!! '.$r->getStatusCode()." ".$obj->message,
                                        );
                                        // abort(403, "Error ".$statuscode = $r->getStatusCode()." ".$obj->message);
                                    }
                            }
                            
                        } else {
                            $savebank[]= array(
                                "msg" => 'Error, Account not found',
                            );
                        }
                        

                }else {
                    
                    $savebank[]= array(
                        "msg" => 'Error, please enter all fields'
                    );
                }
                $set['UBUYAPI_V1'] = $savebank;
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

        function ProcallMessage(){
            $id = filter_input(INPUT_GET, 'bid_id', FILTER_SANITIZE_STRING);

            $bid = DB::table('project_bids')->where('id',$id)->first();
            
            $user = DB::table('users')->where('id',$bid->cus_id)->first();
            $profile = DB::table('profiles')->where('user_id',$bid->user_id)->first();
           $user->first_name;
            $auth_id=$bid->user_id;
            $pro_user = DB::table('users')->where('id',$bid->cus_id)->first();
      
            $chats = Message::where('bid_id',$bid->id)
                         ->where('sender_id',$user->id)
                         ->where('receiver_id',$auth_id)
                         ->Orwhere('sender_id',$auth_id)
                         ->where('receiver_id',$user->id)
                         ->where('bid_id',$bid->id)
                         ->get();
                         if(count($chats) == 0){
                            $set['UBUYAPI_V1'][]=array(
                            'msg' => "No chat available here",
                            );
                      }else {
                            foreach($chats as $chat){
                                if($chat->sender_id != $auth_id){
                                   
                                     if($user->image != null) {
                                        $date = Carbon::parse($chat->created_at); // now date is a carbon instance
                
                                        $set['UBUYAPI_V1'][]=array(
                                            
                                            'pro_id' => $auth_id,
                                        'cus_id' => $bid->cus_id,
                                        'message' =>  $chat->message,
                                        'sender' => 'customer',
                                        'cus_image' => 'https://ubuy.ng/uploads/images/profile_pics/'.$user->image, 
                                        'date' => $date->diffForHumans(),
                                        );
                                  
                                      }else{
                                        $date = Carbon::parse($chat->created_at); // now date is a carbon instance
                
                                        $set['UBUYAPI_V1'][]=array(
                                            
                                            'pro_id' => $auth_id,
                                        'cus_id' => $bid->cus_id,
                                        'message' =>  $chat->message,
                                        'sender' => 'customer',
                                        'cus_image' => 'https://ubuy.ng/mvp_ui/images/icons/chat_user_icon.svg', 
                                        'date' => $date->diffForHumans(),
                                        );
                                      }
                                      
                 
                                }else{
                                    if ($chat->message_type == 'admin') {
                                       
                                        $date = Carbon::parse($chat->created_at); // now date is a carbon instance
                
                                        $set['UBUYAPI_V1'][]=array(
                                            
                                            'pro_id' => $auth_id,
                                        'cus_id' => $bid->cus_id,
                                        'sender' => 'admin',
                                        'message' =>  $chat->message,
                                        'cus_image' => 'https://ubuy.ng/favicon/apple-touch-icon.png', 
                                        'date' => $date->diffForHumans().' Admin - message ',
                                        );

                                        
                                    }else {
                                      if ($pro_user->image != null) {
                                        $date = Carbon::parse($chat->created_at); // now date is a carbon instance
                
                                        $set['UBUYAPI_V1'][]=array(
                                            
                                            'pro_id' => $auth_id,
                                        'cus_id' => $bid->cus_id,
                                        'message' =>  $chat->message,
                                        'sender' => 'pro',
                                        'cus_image' => 'https://ubuy.ng/uploads/images/profile_pics/'.$pro_user->image, 
                                        'date' => $date->diffForHumans(),
                                        );

                                       
                                          } else {
                                              $date = Carbon::parse($chat->created_at); // now date is a carbon instance
                
                                        $set['UBUYAPI_V1'][]=array(
                                            
                                            'pro_id' => $auth_id,
                                        'cus_id' => $bid->cus_id,
                                        'message' =>  $chat->message,
                                        'sender' => 'pro',
                                        'cus_image' => 'https://ubuy.ng/mvp_ui/images/icons/chat_user_icon.svg', 
                                        'date' => $date->diffForHumans(),
                                        );
                                          }
                                          
                                            
                                    }
                    
                                   
                            } 
                            };
                         };
      
                        //  function to update the last message
                     $last_message = Message::latest()->first();
                     if ($last_message != null) {
                        if ($last_message->sender_id != $auth_id) {
                            $setRead = [
                                'is_pro_seen' => 1,
                            ];
                            Message::where('sender_id', $last_message->sender_id)->update($setRead);
                        }
                        
                     }

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
                    $set['UBUYAPI_V1'] = "No messages";

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
public function Apitracker()
{

    if (isset($_GET['project_id'])) {
        $project_id = filter_input(INPUT_GET, 'project_id', FILTER_SANITIZE_STRING);

        $project = Project::where('id','=', $project_id)->first();


        if (!$project) {
            $set['UBUYAPI_V1'][]=array('msg' =>'Project not found','success'=>'0');
        }else if($project){


            $timelines = TaskTracker::where('project_id','=', $project->id)->orderBy('id', 'desc')->get();

            if ($timelines) {
                
                foreach($timelines as $timeline){
                   
                   

                    // using carbon to make date readable
                        $date = Carbon::parse($timeline->created_at); // now date is a carbon instance
                
                        // converting all data to json format
                        $timeline_json[]=array(
                            'id' => $timeline->id,
                            'project_id' => $timeline->project_id,
                            'user_id' => $timeline->user_id,
                            'pro_id' => $timeline->pro_id,
                            'bid_id' => $timeline->bid_id,
                            'track_type' => $timeline->track_type,
                            'pro_name' => $timeline->pro_name,
                            'message' => $timeline->message,
                            'created_at' => $date->diffForHumans(),
                        );
                }  
            } else {
                # code...
            }
            

            

        }
        
        $set['UBUYAPI_V1'] = $timeline_json;

    }

    header( 'Content-Type: application/json; charset=utf-8' );
    echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
    die();

}

public function proAbout()
{
    $pro_id = filter_input(INPUT_GET, 'pro_id', FILTER_SANITIZE_STRING);

    $pro = User::where('id','=', $pro_id)->first();

    

                if($pro->email_verify_code){
                    $email_badge = 'https://ubuy.ng/uploads/backend/badges/1579389914lwmcn-email-verified.png';
                }else{
                    $email_badge = null;
                }
                if($pro->number_verify_code){
                    $phone_badge = 'https://ubuy.ng/uploads/backend/badges/15793897703kpyh-phone-verified.png';
                }else{
                    $phone_badge = null;
                }
                if($pro->verify_confirm == 2){
                    $id_badge = 'https://ubuy.ng/uploads/backend/badges/1579389875ppgnz-id-verified.png';
                }else{
                    $id_badge = null;
                }
                     
            $badges[]=array(
                    
                'badge_pro_id' => $pro->id,
                'badge_email' => $email_badge,
                'badge_phone' => $phone_badge,
                'badge_id' => $id_badge,
                );

                $row['badges']=$badges;

                $pro_services = DB::table("services")
    ->where('services.user_id', '=', $pro_id)
    ->join('sub_categories', 'services.sub_category_id', '=', 'sub_categories.id')
    ->select('services.id as service_id', 'services.sub_category_id', 'services.created_at', 'sub_categories.image', 'services.service_name', 'services.service_projects', 'services.service_verified')
    ->orderBy('services.id', 'desc')->get();



                foreach ($pro_services as $service) {
                    $date = Carbon::parse($service->created_at); // now date is a carbon instance
                    $cat_image = 'https://ubuy.ng/uploads/backend/'.$service->image;
                    $services[]=array(
                    
                        'service_id' => $service->service_id,
                        'service_category_id' =>$service->sub_category_id,
                        'service_name' =>$service->service_name,
                        'service_projects' =>$service->service_projects,
                        'service_verified' =>$service->service_verified,
                        'service_image' =>$cat_image,
                     
                        );
                }

                $pro_ratings = DB::table("ratings")
                ->where('ratings.pro_id', '=', $pro_id)
                ->join('users', 'ratings.cus_id', '=', 'users.id')
                ->select('ratings.id as rating_id', 'ratings.rating', 'ratings.comment', 'ratings.rate_title', 'ratings.cus_id', 'ratings.project_name',
                 'ratings.rate_type', 'ratings.cus_name', 'users.image', 'ratings.created_at')
                ->orderBy('ratings.id', 'desc')->get();
                if ($pro_ratings == false) {
                    $ratings = null;

                } else{
                    foreach ($pro_ratings as $rating) {
                        $date = Carbon::parse($rating->created_at); // now date is a carbon instance
                        $cus_image = 'https://ubuy.ng/uploads/backend/'.$rating->image;
                        $ratings[]=array(
                        
                            'rating_id' => $rating->rating_id,
                            'rating' => $rating->rating,
                            'rating_comment' => $rating->comment,
                            'rating_title' => $rating->rate_title,
                            'cus_id' => $rating->cus_id,
                            'project_name' => $rating->project_name,
                            'rate_type' => $rating->rate_type,
                            'cus_name' => $rating->cus_name,
                            'cus_image' =>$cus_image,
                            'rate_date' =>$date->diffForHumans(),
                         
                            );
                            $row['rating']=$ratings;
                    }

                }

               


    $row['services']=$services;



    // echo $recomend;
 //    $set['UBUYAPI_V1'] = $row;
    $set['UBUYAPI_V1'] = $row;
    
    header( 'Content-Type: application/json; charset=utf-8' );
    echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
    die();
}
public function proPortfolio()
{
    $pro_id = filter_input(INPUT_GET, 'pro_id', FILTER_SANITIZE_STRING);

    $pro = User::where('id','=', $pro_id)->first();
    $pro_galleries = ProGallery::where('user_id','=', $pro_id)->get();

        foreach ($pro_galleries as $gallery) {
            $date = Carbon::parse($gallery->created_at); // now date is a carbon instance
            $gallery_image = 'https://ubuy.ng/uploads/images/galleries/'.$gallery->file;
            $galleries[]=array(
            
                'gallery_id' => $gallery->id,
                'gallery_service' => $gallery->service_id,
                'gallery_title' => $gallery->title,
                'gallery_img' => $gallery_image,
            
                );
        }
       
       
    $row['galleries']=$galleries;

    $pastProject = PastProject::where('user_id','=', $pro_id)->get();




                foreach ($pastProject as $portfolio) {
                    $date = Carbon::parse($portfolio->created_at); // now date is a carbon instance
                    if ($portfolio->image) {
                        $portfolio_img = 'https://ubuy.ng/uploads/images/galleries/'.$portfolio->image;
                    }else {
                        $portfolio_img = null;
                    }

                    $portfolios[]=array(
                        'port_id' => $portfolio->id,
                        'port_des' => $portfolio->project_description,
                        'port_img' => $portfolio_img,
                        'port_title' => $portfolio->sub_category_name.' task for '.$portfolio->cus_name
                        );
                }

    $row['portfolios']=$portfolios;



    // echo $recomend;
 //    $set['UBUYAPI_V1'] = $row;
    $set['UBUYAPI_V1'] = $row;
    
    header( 'Content-Type: application/json; charset=utf-8' );
    echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
    die();
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



public function apiStoreMessagePro()
{
    
    $message = filter_input(INPUT_GET, 'message', FILTER_SANITIZE_STRING);
    $sender_id = filter_input(INPUT_GET, 'sender_id', FILTER_SANITIZE_STRING);
    $receiver_id = filter_input(INPUT_GET, 'receiver_id', FILTER_SANITIZE_STRING);
    $bid_id = filter_input(INPUT_GET, 'bid_id', FILTER_SANITIZE_STRING);
    $project_id = filter_input(INPUT_GET, 'project_id', FILTER_SANITIZE_STRING);
    
    if ($message != null && $sender_id != null && $receiver_id != null && $bid_id != null && $project_id != null) {
        $text = preg_replace('/\+?[0-9][0-9()\-\s+]{4,20}[0-9]/', '[blocked]', $message);

        $chat = new Message;
        $chat->message = $text;
        $chat->sender_id = $sender_id;
        $chat->receiver_id = $receiver_id;
        $chat->bid_id = $bid_id;
        $chat->project_id = $project_id;
        $chat->save();
        if ($chat->id == true) {
            $set['UBUYAPI_V1'][]=array(
                'chat_id' => $chat->id,
                'chat' =>$chat->message,
                'msg' =>'message sent',
                'success'=>'1'
            );
           
        }else {
            $set['UBUYAPI_V1'][]=array(
                'msg' =>'message not sent',
                'success'=>'0'
            );
        }

    } else {
        $set['UBUYAPI_V1'][]=array(
            'msg' =>'message not sent, check if the ids and message are entered correctly',
            'success'=>'0'
        );
    }
    
    

    



    header( 'Content-Type: application/json; charset=utf-8' );
    echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
    die();
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
