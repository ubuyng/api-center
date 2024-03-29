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
use App\Question;
use App\Response;
use Notification;
use App\JobRequest;
use App\FavoritePro;
use App\Rating;
use App\Category;
use App\AppHeader;
use App\TaskTracker;
use App\SubCategory;
use App\Conversation;
use App\User;
use App\PastProject;
use App\ProGallery;
use App\ResponseItem;
use App\UpayTransaction;
use App\AppFeedback;
use App\ProjectFile;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
use App\Notifications\ClickPaymentSlack;
use App\Notifications\CancledPaymentSlack;
use App\Notifications\MadePaymentSlack;
use App\Notifications\ProTransactNotify;
use App\Notifications\CusTransactNotify;
use App\Notifications\HireArtisanSlack;
use App\Notifications\ProArtisanNotify;
use App\Notifications\CusArtisanNotify;


use Illuminate\Support\Facades\Hash;
use GuzzleHttp\Client;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;
use Closure;

class Api2Controller extends Controller
{

      /* 
   ** ======== API VERSION 2 SECTION STARTS HERE =======
   **
   */
       public function apiIndexV2()
       {
           $title = "UBUY APP";
           $header_slide = AppHeader::get();
           $recomend = DB::table("sub_categories")
                       ->join('api_recommends', 'api_recommends.subcategory_id', '=', 'sub_categories.id')
                       ->orderBy('sub_categories.count', 'desc')->get()->take(7);
           $design = SubCategory::where('category_id','=', 8)->get()->take(7);
           $business = SubCategory::where('category_id','=', 14)->get()->take(7);
           $personal = SubCategory::where('category_id','=', 15)->get()->take(7);
           $home = SubCategory::where('category_id','=', 1)->get()->take(7);
   
       
           $row['header_slide']=$header_slide;
           $row['recommend']=$recomend;
           $category = Category::where('app_debug', 1)->get();
           $row['category']=$category;
           $row['design_web']=$design;
           $row['business']=$business;
           $row['personal']=$personal;
           $row['home']=$home;
   
   
           // echo $recomend;
        //    $set['UBUYAPI_V2'] = $row;
           $set['UBUYAPI_V2'] = $row;
           
           // header( 'Content-Type: application/json; charset=utf-8' );
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
                 
                 $set['UBUYAPI_V2'] = $update_user;
                } else {
                  
                        $set['UBUYAPI_V2'][]=array('msg' =>'Sorry, user not found','success'=>'0');
                }
                
            }
            header( 'Content-Type: application/json; charset=utf-8' );
            echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
            die();
          
    
        }

          public function ProjectFilesApi(){
            if (isset($_GET['project_id'])  && isset($_GET['bid_id'])) {

            $id = filter_input(INPUT_GET, 'project_id', FILTER_SANITIZE_STRING);
            $bid_id = filter_input(INPUT_GET, 'bid_id', FILTER_SANITIZE_STRING);

              $files = ProjectFile::where('project_id',$id)
                         ->where('bid_id',$bid_id)->get();

            if ($files) {
              
                foreach ($files as $file) {
                    $date = Carbon::parse($file->created_at); // now date is a carbon instance
                    if ($file->file_type == 'Image') {
                        $file_data[] = array(
                            'file_id' => $file->id,
                            'project_id' => $file->project_id,
                            'bid_id' => $file->bid_id,
                            'file_url' => 'https://ubuy.ng/project_files/'.$file->project_id.'/'.$file->file_name,
                            'is_image' => 1,
                            'file_type' => $file->file_type,
                            'file_name' => $file->file_name,
                            'file_date' => $date->diffForHumans(),
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
                            'file_date' => $date->diffForHumans(),
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
            $set['UBUYAPI_V2'] = $file_data;
            
            header( 'Content-Type: application/json; charset=utf-8' );
            echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
            die();
          
    
        }


    public function apiProjects()
        {
        
            if (isset($_GET['user_id'])) {
                $user_id = filter_input(INPUT_GET, 'user_id', FILTER_SANITIZE_STRING);

                $user = Auth::loginUsingId($user_id);

                if (!$user) {
                    $set['UBUYAPI_V2'][]=array('msg' =>'Account not found','success'=>'0');
                }else if($user){
                // $projects = $user->projectsSubCat->get();

                $projects = DB::table("projects")
                ->where('projects.user_id', '=', $user_id)
                ->select('projects.id as project_id', 'projects.user_id as user_id',  'projects.project_message', 'projects.created_at', 'projects.sub_category_name','projects.status', 'projects.sub_category_id','projects.address')
                ->orderBy('projects.id', 'desc')->get();

                if($projects->isEmpty()){
                   $set['UBUYAPI_V2'][]=array('msg' =>'No projects found','success'=>'0');


                }
            else if($projects){

                foreach($projects as $project){
                    // counting bids in project here
                    $bids = ProjectBid::where('project_id','=', $project->project_id)->get();
                    $bid_count = count($bids);

                     /* chek bid status */
                     if ($bid_count == 0) {
                        //  awaiting bids
                        $bid_status = 0;
                    }elseif ($bid_count >= 1 && $project->status == 2) {
                        // selected pro
                        $bid_status = 4;
                    }elseif ($bid_count >= 1 && $project->status != 3) {
                        // receiving bids
                        $bid_status = 1;
                    }elseif ($bid_count >= 1 && $project->status == 3) {
                        // completed project
                        $bid_status = 2;
                    }elseif ($bid_count >= 0 && $project->status == 4) {
                        // paused project
                        $bid_status = 3;
                    }

                    /* checking project status */
                    // check if the project is older than 30 days
                    $mainDate = new \DateTime($project->created_at);
                    $now = new \DateTime();
                    if($mainDate->diff($now)->days > 30 && $project->status != 3) {
                        // expired and not completed
                        $project_progress = 2;
                    }elseif($mainDate->diff($now)->days > 30 && $project->status == 3){
                        // expired and completed
                        $project_progress = 1;
                    }elseif ($project->status == 3) {
                        // completed
                        $project_progress = 1;
                    }elseif ($mainDate->diff($now)->days < 30 && $project->status == 3) {
                        // not expired and completed
                        $project_progress = 1;
                    }elseif ($mainDate->diff($now)->days < 30 && $project->status != 3) {
                        // not expired and not completed
                        $project_progress = 0;
                    }else {
                        $project_progress = 0;
                    }
                   

                    // using carbon to make date readable
                        $date = Carbon::parse($project->created_at); // now date is a carbon instance
                
                        // converting all data to json format
                        if ($project_progress == 0) {
                            $project_open[]=array(
                                'project_id' => $project->project_id,
                                'sub_category_id' => $project->sub_category_id,
                                'user_id' => $project->user_id,
                                'sub_category_name' => $project->sub_category_name,
                                'address' => $project->address,
                                'bid_count' => $bid_count,
                                'brief' => $project->project_message,
                                'bid_status' => $bid_status,
                                'progress' => $project_progress,
                                'created_at' => $date->diffForHumans(),
                            );
                            $row['project_open']=$project_open;
                        }elseif ($project_progress == 1) {
                            $project_completed[]=array(
                                'project_id' => $project->project_id,
                                'sub_category_id' => $project->sub_category_id,
                                'user_id' => $project->user_id,
                                'sub_category_name' => $project->sub_category_name,
                                'address' => $project->address,
                                'brief' => $project->project_message,
                                'bid_count' => $bid_count,
                                'bid_status' => $bid_status,
                                'progress' => $project_progress,
                                'created_at' => $date->diffForHumans(),
                            );
                            $row['project_completed']=$project_completed;

                        }elseif ($project_progress == 2) {
                            $project_expired[]=array(
                                'project_id' => $project->project_id,
                                'sub_category_id' => $project->sub_category_id,
                                'user_id' => $project->user_id,
                                'sub_category_name' => $project->sub_category_name,
                                'address' => $project->address,
                                'bid_count' => $bid_count,
                                'brief' => $project->project_message,
                                'bid_status' => $bid_status,
                                'progress' => $project_progress,
                                'created_at' => $date->diffForHumans(),
                            );
                            $row['project_expired']=$project_expired;

                        }
                   


                   
                    $set['UBUYAPI_V2'] = $row;
                }                                                                                                                                                               
            } 


        }
        
        header( 'Content-Type: application/json; charset=utf-8' );
        echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
        die();
    }
}
// bids api
    public function apiProjectBids()
        {
        
            if (isset($_GET['project_id'])) {
                $project_id = filter_input(INPUT_GET, 'project_id', FILTER_SANITIZE_STRING);

                $project = Project::where('id','=', $project_id)->first();
 

                if (!$project) {
                    $set['UBUYAPI_V2'][]=array('msg' =>'Project not found','success'=>'0');
                }else if($project){
                // $projects = $user->projectsSubCat->get();

                $project_bids = DB::table("project_bids")
                ->where('project_bids.project_id', '=', $project->id)
                ->join('profiles', 'project_bids.user_id', '=', 'profiles.user_id')
                ->join('users', 'project_bids.user_id', '=', 'users.id')
                ->select('project_bids.id as bid_id', 'project_bids.user_id as pro_id','profiles.profile_photo', 'users.image', 'project_bids.cus_id as cus_id','profiles.business_name', 'project_bids.created_at', 'project_bids.bid_amount', 'project_bids.bid_message','project_bids.bid_status', 'project_bids.cus_id')
                ->orderBy('project_bids.id', 'desc')->get();

                if($project_bids->isEmpty()){
                    $set['UBUYAPI_V2'] = null;

                }
            else if($project_bids){

                foreach($project_bids as $bids){
                  
                    $date = Carbon::parse($bids->created_at); // now date is a carbon instance

                    /* Profiles photo checker and links */
                    if ($bids->profile_photo != null ) {
                        $profile_url = 'https://ubuy.ng/uploads/images/profile_pics/'.$bids->profile_photo;
                    } elseif($bids->profile_photo == null && $bids->image != null)  {
                        $profile_url = 'https://ubuy.ng/uploads/images/profile_pics/'.$bids->image;
                    }else {
                        $profile_url = null;

                    }

                    $all_bids[]=array(
                                    'bid_id' => $bids->bid_id,
                                    'bid_message' => $bids->bid_message,
                                    'bid_amount' => $bids->bid_amount,
                                    'profile_photo' => $profile_url,
                                    'pro_name' => $bids->business_name,
                                    'bid_status' => $bids->bid_status,
                                    'pro_id' => $bids->pro_id,
                                    'cus_id' => $bids->cus_id,
                                    'created_at' => $date->diffForHumans(),
                                );
                               

                    
                   


                   
                    $set['UBUYAPI_V2'] = $all_bids;
                }                                                                                                                                                               
            } 


        }
        
        header( 'Content-Type: application/json; charset=utf-8' );
        echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
        die();
    }
}
    public function apiUpay()
        {
        
            if (isset($_GET['user_id'])) {
                $user_id = filter_input(INPUT_GET, 'user_id', FILTER_SANITIZE_STRING);

              $upay = UpayTransaction::where('cus_id', $user_id)->where('status',1)->get();
              
              $balance = $upay->sum('amount');
              $upay_balance[]=array(
                  'upay_balance_total' => $balance,
              );
              $row['upay_balance']=$upay_balance;



               
                $upay_transact = DB::table("upay_transactions")
                ->where('upay_transactions.cus_id', '=', $user_id)
                ->join('profiles', 'upay_transactions.user_id', '=', 'profiles.user_id')
                ->join('projects', 'upay_transactions.project_id', '=', 'projects.id')
                ->select('upay_transactions.id as upay_id', 'projects.id as project_id', 'projects.sub_category_name', 'profiles.business_name', 'upay_transactions.amount', 'upay_transactions.created_at')
                ->orderBy('upay_transactions.id', 'desc')->get();

                if($upay_transact->isEmpty()){
                    $set['UBUYAPI_V2'] = null;

                }
            else if($upay_transact){

                foreach($upay_transact as $transact){
                  
                    $date = Carbon::parse($transact->created_at); // now date is a carbon instance

                    $upay_history[]=array(
                                    'upay_id' => $transact->upay_id,
                                    'upay_project_name' => $transact->sub_category_name,
                                    'upay_pro_name' => $transact->business_name,
                                    'upay_project_id' => $transact->project_id,
                                    'upay_amount' => $transact->amount,
                                    'upay_date' => $date->diffForHumans(),
                                );
                               
                                $row['upay_history']=$upay_history;
                                
                   


                   
                            }                                                                                                                                                               
                        } 
                        
                        
                    }
                    
        $set['UBUYAPI_V2'] = $row;
        header( 'Content-Type: application/json; charset=utf-8' );
        echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
        die();
    
}

    public function updateTask()
    {

        if (isset($_GET['project_id'])) {
            $project_id = filter_input(INPUT_GET, 'project_id', FILTER_SANITIZE_STRING);
            $status_update = filter_input(INPUT_GET, 'status', FILTER_SANITIZE_STRING);

            $project = Project::where('id','=', $project_id)->first();


            if (!$project) {
                $set['UBUYAPI_V2'][]=array('msg' =>'Project not found','success'=>'0');
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
            
            $set['UBUYAPI_V2'] = $update_task;

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

            $$bid = DB::table("project_bids")
            ->where('project_bids.id', '=', $bid->id)
            ->join('profiles', 'project_bids.user_id', '=', 'profiles.user_id')
            ->join('users', 'project_bids.user_id', '=', 'users.id')
            ->select('project_bids.id as bid_id', 'project_bids.user_id as pro_id','profiles.profile_photo', 'profiles.pro_city', 'profiles.project_done','users.image', 'project_bids.cus_id as cus_id','profiles.business_name', 'project_bids.created_at', 'project_bids.bid_amount', 'project_bids.bid_message','project_bids.bid_status', 'project_bids.cus_id', 'project_bids.project_id')
            ->first();


            $date = Carbon::parse($$bid->created_at); // now date is a carbon instance

            /* Profiles photo checker and links */
            if ($$bid->profile_photo != null ) {
                $profile_url = 'https://ubuy.ng/uploads/images/profile_pics/'.$$bid->profile_photo;
            } elseif($$bid->profile_photo == null && $$bid->image != null)  {
                $profile_url = 'https://ubuy.ng/uploads/images/profile_pics/'.$$bid->image;
            }else {
                $profile_url = null;

            }

            $task_done_1  = $$bid->project_done;

            $task_done_2 =  count(Project::where('pro_id','=', $$bid->pro_id)->get());
        
    
             $rating_checker = Rating::where('pro_id','=', $$bid->pro_id)->select('rating')->get();
             if ($rating_checker) {
                 $pro_rating = $rating_checker->avg('rating');
             } elseif ($rating_checker == null) {
                $pro_rating = 0;
            } 
             


            $task_counter = $task_done_1+$task_done_2;

            $all_bids[]=array(
                'bid_id' => $$bid->bid_id,
                'bid_message' => $$bid->bid_message,
                'bid_amount' => '₦'.$$bid->bid_amount,
                'profile_photo' => $profile_url,
                'pro_name' => $$bid->business_name,
                'bid_status' => $$bid->bid_status,
                'pro_id' => $$bid->pro_id,
                'cus_id' => $$bid->cus_id,
                'pro_city' => $$bid->pro_city,
                'project_id' => $$bid->project_id,
                'task_done' => $task_counter,
                'pro_rating' => $pro_rating,
                'created_at' => $date->diffForHumans(),
            );

            $bid_track_checker = TaskTracker::where('bid_id','=', $$bid->bid_id)->where('track_type','=', 'bid_opened')->first();

          if (!$bid_track_checker) {
            $track_message =  'you opened  ' . $$bid->business_name. ' bid';
            $tracker = [
                'user_id' => $$bid->cus_id,
                'project_id' => $$bid->project_id,
                'bid_id' => $$bid->bid_id,
                'track_type' => "bid_opened",
                'message' => $track_message,
            ];
            TaskTracker::create($tracker);
          } 
          
            
          
            
            $set['UBUYAPI_V2'] = $all_bids;
            header( 'Content-Type: application/json; charset=utf-8' );
            echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
            die();


           

        }
    }
    public function ApiBidStatus()
        {
            if (isset($_GET['bid_id'])) {
                $bid_id = filter_input(INPUT_GET, 'bid_id', FILTER_SANITIZE_STRING);

                $bid = ProjectBid::where('id','=', $bid_id)->first();
                if ($bid->status == 0) {
                    $bid->update(['bid_status' => '1']);

                }

                $user = DB::table('users')->where('id',$bid->user_id)->first();
                $profile = DB::table('profiles')->where('user_id',$bid->user_id)->first();
               $user->first_name;
                $auth_id=$bid->cus_id;
                $cus_user = DB::table('users')->where('id',$bid->cus_id)->first();
        
                 $chats = Message::where('bid_id',$bid->id)
                ->where('sender_id',$user->id)
                ->where('receiver_id',$auth_id)
                ->Orwhere('sender_id',$auth_id)
                ->where('receiver_id',$user->id)
                ->where('bid_id',$bid->id)
                ->get();
        
                if(count($chats) > 0){
                   $has_caht = 1;
                }elseif (count($chats) == 0) {
                    $has_caht = 0;
                   
                }

                $project = Project::where('id', $bid->project_id)->first();

                if ($project->status == 3) {
                  if ($project->pro_id == $bid->user_id) {
                     $status = 3;
                  } else {
                    $status = 3;
                  }
                  
                } elseif($project->status == 2) {
                    if ($project->pro_id == $bid->user_id) {
                        $status = 2;
                     } else {
                       $status = 3;
                     }
                }
                 elseif($project->status == 1) {
                    if ($project->pro_id == $bid->user_id) {
                        $status = 1;
                     } else {
                       $status = 1;
                     }
                }
                 elseif($project->status == 0) {
                    if ($project->pro_id == $bid->user_id) {
                        $status = 1;
                     } else {
                       $status = 1;
                     }
                }
                

            $all_bids[]=array(
                'bid_id' => $bid->id,
                'bid_status' => $status,
                'has_chat' => $has_caht,
            );  
            
          
            
            $set['UBUYAPI_V2'] = $all_bids;
            header( 'Content-Type: application/json; charset=utf-8' );
            echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
            die();


           

        }
    }
    public function chattest()
    {
        $id = 898;
        $bid = DB::table('project_bids')->where('id',$id)->first();
      
        $user = DB::table('users')->where('id',$bid->user_id)->first();
        $profile = DB::table('profiles')->where('user_id',$bid->user_id)->first();
       $user->first_name;
        $auth_id=$bid->cus_id;
        $cus_user = DB::table('users')->where('id',$bid->cus_id)->first();

         $chats = Message::where('bid_id',$bid->id)
        ->where('sender_id',$user->id)
        ->where('receiver_id',$auth_id)
        ->Orwhere('sender_id',$auth_id)
        ->where('receiver_id',$user->id)
        ->where('bid_id',$bid->id)
        ->get();

        if(count($chats) > 0){
            echo $chats;
        }elseif (count($chats) == 0) {
            echo 'no chats';
           
        }
        
    }

    public function apiNotify()
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
                            if ($notify->type == "App\Notifications\NewBid") {
                               
                                $row[]=array(
                                    'bid_id' => $notify->data['bid_id'],
                                    'project_id' =>  $notify->data['project_id'],
                                    'notify_msg' =>  $notify->data['pro_name'].' sent a bid for '.$notify->data['project_name'].' task',
                                    'created_at' => $date->diffForHumans(),
                                    'notify_type' => 1,
                                    'notify_url' => null
                                );

                            }elseif ( $notify->type == "App\Notifications\CusTransactNotify") {
                                $row[]=array(
                                    'project_id' =>  $notify->data['project_id'],
                                    'notify_msg' =>  'Payment for '.$notify->data['project_name'].' task was successful',
                                    'created_at' => $date->diffForHumans(),
                                    'notify_type' => 0,
                                    'bid_id' => null,
                                    'notify_url' => null


                                );
                            }
                           
                        }  
                    }
                }
          
            
            $set['UBUYAPI_V2'] = $row;
            header( 'Content-Type: application/json; charset=utf-8' );
            echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
            die();


           

        }
    }
    public function apiCategories()
        {
        
            $category = Category::get();
            $set['UBUYAPI_V2'] = $category;
            header( 'Content-Type: application/json; charset=utf-8' );
            echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
            die();
        }
    public function apitxRef()
    {
            $bid_id = filter_input(INPUT_GET, 'bid_id', FILTER_SANITIZE_STRING);


            $bid = ProjectBid::where('id', $bid_id)->first();
            if ($bid) {
                $project = Project::where('id', $bid->project_id)->first();
                $amount = $bid->bid_amount;
                $percent = "2.5";
                $transact_fee = ($percent/100)*$amount + 100;
                $transact_total = $amount + $transact_fee;
                

                $generated_ref = base64_encode(random_bytes(10));
                $ref[]=array(
                    'tex_ref' =>  $generated_ref.'_'.$project->sub_category_name,
                    'transact_amount' => '₦'.$amount,
                    'transact_percent' => $percent,
                    'transact_fee' => '₦'.$transact_fee,
                    'transact_total' => '₦'.$transact_total,
                    'transact_flutter_total' => $transact_total,
                    'transact_duration' => $bid->bid_duration.' days',
                    'upay_type' => $project->upay_type,

                );
            } else {
                # code...
            }
            
           
            $set['UBUYAPI_V2'] = $ref;
            header( 'Content-Type: application/json; charset=utf-8' );
            echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
            die();
        }
        

        public function allSubcat(){
            // $json = SubCategory::get();
            $json = SubCategory::select('id', 'name', 'secondary_name')->get();

            $set['UBUYAPI_V2'] = $json;
            header( 'Content-Type: application/json; charset=utf-8' );
            echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
            die();
          
    
        }


        public function seachCat(){
            $id = filter_input(INPUT_GET, 'cat_id', FILTER_SANITIZE_STRING);

            $Category =  SubCategory::where('category_id','=', $id)->select('id', 'name','category_id', 'description', 'image', 'payment_type')->get();


            $set['UBUYAPI_V2'] = $Category;
            header( 'Content-Type: application/json; charset=utf-8' );
            echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
            die();
          
    
        }


        public function fetch_subcat(){

            if (isset($_GET['phrase'])) {
                $phrase= filter_input(INPUT_GET, 'phrase', FILTER_SANITIZE_STRING);
                
                $json = SubCategory::where('name','LIKE','%'.$phrase.'%')->select('id', 'name', 'secondary_name')
                ->get();
    
                $set['UBUYAPI_V2'] = $json;
            header( 'Content-Type: application/json; charset=utf-8' );
            echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
            die();
            }
            $empty = "Couldn't get request";
            $set['UBUYAPI_V2'] = $empty;
            header( 'Content-Type: application/json; charset=utf-8' );
            echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
            die();
    
          
    
        }
        public function Suggestions(){

            $recomend = DB::table("sub_categories")
            ->join('api_recommends', 'api_recommends.subcategory_id', '=', 'sub_categories.id')
            ->select('sub_categories.id', 'sub_categories.category_id', 'sub_categories.name', 'sub_categories.icon')
            ->orderBy('sub_categories.count', 'desc')->get()->take(7);

            $set['UBUYAPI_V2'] = $recomend;
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
    
            $set['UBUYAPI_V2'] = $questions;
            header( 'Content-Type: application/json; charset=utf-8' );
            echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
            die();
    
        }

        public function singleQuestion($id)
        {
    
            $question = Question::find($id);
            $choices = $question->choices;
    
            $set['UBUYAPI_V2'] = $choices;
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
                    $set['UBUYAPI_V2'][]=array('msg' =>'Account not found','success'=>'0');
                }else if($user){
                // $projects = $user->projectsSubCat->get();

                $projects = DB::table("projects")
                ->join('sub_categories', 'projects.sub_category_id', '=', 'sub_categories.id')
                ->where('projects.user_id', '=', $user_id)
                ->select('projects.id', 'projects.user_id', 'projects.created_at', 'sub_categories.image', 'projects.sub_category_name', 'projects.sub_category_id','projects.address',  'projects.project_message')
                ->orderBy('projects.id', 'desc')->get();

                if($projects->isEmpty()){
                    $set['UBUYAPI_V2'] = "blank";

                }
            else if($projects){

                foreach($projects as $project){
                        $date = Carbon::parse($project->created_at); // now date is a carbon instance
                
                    $set['UBUYAPI_V2'][]=array(
                        
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
            // $set['UBUYAPI_V2'] = $projects;
        }
        
        header( 'Content-Type: application/json; charset=utf-8' );
        echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
        die();
     }
    }
    public function apiClickOnPayment()
    {
        $user_id = filter_input(INPUT_GET, 'user_id', FILTER_SANITIZE_STRING);
        $project = filter_input(INPUT_GET, 'project', FILTER_SANITIZE_STRING);
        $amount = filter_input(INPUT_GET, 'amount', FILTER_SANITIZE_STRING);
        $pro_name = filter_input(INPUT_GET, 'pro_name', FILTER_SANITIZE_STRING);


        $user = User::where('id', $user_id)->first();

        $project = $project;
        $amount = $amount;
        $cus_name = $user->first_name." ".$user->last_name;
        $pro_name = $pro_name;
        Notification::route('slack', env('SLACK_HOOK'))
        ->notify(new ClickPaymentSlack($project, $cus_name, $pro_name, $amount, $user));
                    // instance ends here

                    $response[]=array(
                        'notify_sent' => 1,
                    );
        $set['UBUYAPI_V2'] = $response;
        header( 'Content-Type: application/json; charset=utf-8' );
        echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
        die();
    }
    public function CancledOnPayment(Request $request)
    {
        $user = Auth::user();

        $project = $request->project;
        $amount = $request->amount;
        $cus_name = $user->first_name." ".$user->last_name;
        $pro_name = $request->pro_name;
        Notification::route('slack', env('SLACK_HOOK'))
        ->notify(new CancledPaymentSlack($project, $cus_name, $pro_name, $amount, $user));
                    // instance ends here

    }
    public function MadePaymentSlack(Request $request)
    {
        $user = Auth::user();
         $project_call = Project::where('id', '=', $request->project_id)->first();
         $bid = ProjectBid::where('id', '=', $request->bid_id)->first();
        $prouser = User::where('id', '=', $bid->user_id)->first();
        $proprofile = Profile::where('user_id', '=', $bid->user_id)->first();
        $cususer = $user;

        $project = $request->project;
        $amount = $request->amount;
        $cus_name = $user->first_name." ".$user->last_name;
        $pro_name = $request->pro_name;
        Notification::route('slack', env('SLACK_HOOK'))
        ->notify(new MadePaymentSlack($project, $cus_name, $pro_name, $amount, $user));
                    // instance ends here

                    /* 
                    
                    we will also do all update and create instance using this function 
                    
                    */
            $ProTransact = [
                'user_id' => $prouser->id,
                'cus_id' =>  $user->id,
                'txref' =>  $request->txtref,
                'bid_id' =>  $bid->id,
                'project_id' =>  $project_call->id,
                'amount' =>  $bid->bid_amount,
                'status' =>  1,
            ];

            $project_data = [
                'status' => 2,
                'pro_id' => $prouser->id,
                'pro_name' => $pro_name,
            ];
            $bid_data = [
                'bid_status' => 2
            ];
            Project::where('id', '=', $project_call->id)->update($project_data);
            ProjectBid::where('id', '=', $bid->id)->update($bid_data);
            UpayTransaction::create($ProTransact);

              /*   
            update instance ends here
            */

            /*   
            Sending pro notification via email, sms & databasehere
            */

            $when = now()->addMinutes(2);
            $prouser->notify((new ProTransactNotify($cususer, $prouser, $project_call, $bid))->delay($when));
            /*   
            Sending cus notification via email, sms & databasehere
            */

            $when = now()->addMinutes(2);
            $cususer->notify((new CusTransactNotify($cususer, $proprofile, $project_call, $bid))->delay($when));

            // final response to reload page with new details
            echo 2;

    }
    public function HireArtisanSlack(Request $request)
    {
        $user = Auth::user();
         $project_call = Project::where('id', '=', $request->project_id)->first();
         $bid = ProjectBid::where('id', '=', $request->bid_id)->first();
        $prouser = User::where('id', '=', $bid->user_id)->first();
        $proprofile = Profile::where('user_id', '=', $bid->user_id)->first();
        $cususer = $user;

        $project = $request->project;
        $amount = $request->amount;
        $cus_name = $user->first_name." ".$user->last_name;
        $pro_name = $request->pro_name;
        Notification::route('slack', env('SLACK_HOOK'))
        ->notify(new HireArtisanSlack($project, $cus_name, $pro_name, $amount, $user));
                    // instance ends here

                    /* 
                    
                    we will also do all update and create instance using this function 
                    
                    */
           
            $project_data = [
                'status' => 2,
                'pro_id' => $prouser->id,
                'pro_name' => $pro_name,
            ];
            $bid_data = [
                'bid_status' => 2
            ];
            Project::where('id', '=', $project_call->id)->update($project_data);
            ProjectBid::where('id', '=', $bid->id)->update($bid_data);

              /*   
            update instance ends here
            */

            /*   
            Sending pro notification via email, sms & databasehere
            */

            $when = now()->addMinutes(2);
            $prouser->notify((new ProArtisanNotify($cususer, $prouser, $project_call, $bid))->delay($when));
            /*   
            Sending cus notification via email, sms & databasehere
            */

            $when = now()->addMinutes(2);
            $cususer->notify((new CusArtisanNotify($cususer, $proprofile, $project_call, $bid))->delay($when));

            // final response to reload page with new details
            echo 2;

    }
public function Apitracker()
{

    if (isset($_GET['project_id'])) {
        $project_id = filter_input(INPUT_GET, 'project_id', FILTER_SANITIZE_STRING);

        $project = Project::where('id','=', $project_id)->first();


        if (!$project) {
            $set['UBUYAPI_V2'][]=array('msg' =>'Project not found','success'=>'0');
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
        
        $set['UBUYAPI_V2'] = $timeline_json;

    }

    header( 'Content-Type: application/json; charset=utf-8' );
    echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
    die();

}

public function proAbout()
{
    $pro_id = filter_input(INPUT_GET, 'pro_id', FILTER_SANITIZE_STRING);

    $pro = User::where('id','=', $pro_id)->first();
    $profile = Profile::where('user_id','=', $pro_id)->first();

    $about[]= array(
        'pro_about' => $profile->about_profile,
    );
    $row['about'] = $about;

    

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
 //    $set['UBUYAPI_V2'] = $row;
    $set['UBUYAPI_V2'] = $row;
    
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
 //    $set['UBUYAPI_V2'] = $row;
    $set['UBUYAPI_V2'] = $row;
    
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
                    $set['UBUYAPI_V2'][]=array('msg' =>'Account not found','success'=>'0');
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
                    $set['UBUYAPI_V2'] = $user->email;
                }
            else if($bids){
                    // $set['UBUYAPI_V2'] = $bids;

                foreach($bids as $bid){
                        $date = Carbon::parse($bid->created_at); // now date is a carbon instance

                        $bid_amount = "₦".$bid->bid_amount;
                    $set['UBUYAPI_V2'][]=array(
                        
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
            // $set['UBUYAPI_V2'] = $projects;
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
                    $set['UBUYAPI_V2'][]=array('msg' =>'Account not found','success'=>'0');
                }else if($auth_user){
                // $projects = $user->projectsSubCat->get();

              
                 $chats = Message::where('bid_id',$bid_id)
                              ->where('sender_id',$user->id)
                              ->where('receiver_id',$auth_id)
                              ->Orwhere('sender_id',$auth_id)
                              ->where('receiver_id',$user->id)
                              ->get();

                if($chats->isEmpty()){
                    $set['UBUYAPI_V2'] = $user->email;
                }
            else if($chats){
                    // $set['UBUYAPI_V2'] = $chats;

                    foreach($chats as $chat){
                        $date = Carbon::parse($chat->created_at); // now date is a carbon instance
                
                        if($chat->sender_id != $auth_id){
                            if ($profile->profile_photo) {
                                $chat_image = 'https://beta.ubuy.ng/uploads/images/profile_pics/'.$profile->profile_photo;
                            } else{
                                $chat_image = 'https://placehold.it/50/55C1E7/fff&text='. mb_substr($profile->business_name , 0, 1);

                            }

                    $set['UBUYAPI_V2'][]=array(
                        
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

                    $set['UBUYAPI_V2'][]=array(
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
            // $set['UBUYAPI_V2'] = $projects;
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

    $categories = Category::where('id', '!=', 6)->where('id', '!=', 8)->where('id', '!=', 15)->select('id', 'name', 'image')->get();
    // $categories = Category::where('id', '!=', 6)->where('id', '!=', 8)->select('id', 'name', 'image')->get();


       $set['UBUYAPI_V2'] = $categories;
       header( 'Content-Type: application/json; charset=utf-8' );
       echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
       die();
 
   }
   public function submitFeedback()
   {
    if (isset($_GET['message'])) {
        $user_id = filter_input(INPUT_GET, 'user_id', FILTER_SANITIZE_STRING);
        $message = filter_input(INPUT_GET, 'message', FILTER_SANITIZE_STRING);

        $user = User::where('id', $user_id)->first();
        $feedback = [
            'user_id' => $user->id,
            'full_name' => $user->first_name.' '.$user->last_name,
            'email' => $user->email,
            'number' => $user->number,
            'message' => $message,
        ];
        AppFeedback::create($feedback);
        $response[]=array(
            'msg' => 'Feeback sent'
        );

    }
       $set['UBUYAPI_V2'] = $response;
       header( 'Content-Type: application/json; charset=utf-8' );
       echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
       die();
 
   }


   public function proBidProfile()
   {
    $id = filter_input(INPUT_GET, 'pro_id', FILTER_SANITIZE_STRING);

        $user = User::find($id);
    if ($user) {
        $profile = Profile::where('user_id',$user->id)->first();

        $profile_image = null;

        if ($profile != null) {
              


            if ($profile_image == null) {
                // check if the pro has a user image instead
                $profile_image = "https://ubuy.ng/uploads/images/profile_pics/".$user->image;
                if($profile_image == null)  {
                    $profile_image = 'https://ubuy.ng/mvp_ui/images/icons/chat_user_icon.png';
                }
            }
            $task_done = ProjectBid::where('user_id', $user->id)
             ->where('bid_status', '=', 2)
             ->count();

            // sending final data to the api
            
            $set['UBUYAPI_V2'][]=array(
                        
                'pro_id' =>  $user->id,
                'pro_image' => $profile_image,
                'pro_name' =>  $profile->business_name,
                'pro_city' => $profile->pro_city,
                'task_done' => $task_done,
            ); 
            
            }else {
                $set['UBUYAPI_V2'][]=array('msg' =>'This is a customer account or this pro has not setup a business profile','success'=>'0');

        }
     }else {
        $set['UBUYAPI_V2'][]=array('msg' =>'This profile does not have a valid profile registered','success'=>'0');

     }

     header( 'Content-Type: application/json; charset=utf-8' );
     echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
     die();
  
   }
 
   
  // Save pro project files

  public function saveProjectFile (Request $request)
  {
    $project_id = $request->project_id;
    $bid_id = $request->bid_id;
    $bid = ProjectBid::where('id', $bid_id)->first();
    $project = Project::where('id', $project_id)->first();
    $customer = User::where('id', $project->user_id)->first();
    $pro = User::where('id', $bid->user_id)->first();

    //  if ($request->hasFile('picture')){

    // $set['UBUYAPI_V2'][]=array(
                        
    //     'msg' =>  'post sent a file',
    //     'bid_id' =>  $bid_id,
    //     'project_id' =>  $project_id,
    // );
// }else{

//     $set['UBUYAPI_V2'][]=array(
                        
//         'msg' =>  'post sent no file',
//         'bid_id' =>  $bid_id,
//         'project_id' =>  $project_id,
//     );

//     }; 
      $thumbnail = null;
      if ($request->hasFile('picture')){
          $image = $request->file('picture');

          $valid_extensions = ['jpg','jpeg','png',];
          $image_extensions = ['jpg','jpeg','png'];
    //       $doc_extensions = ['docx','doc'];
    //       $pdf_extensions = ['pdf'];
    //       $excel_extensions = ['xls', 'xlsx'];
    //       $ppt_extensions = ['ppt', 'pptx',];
    //       $zip_extensions = ['zip',];
    //       $other_extensions = ['xml', 'txt'];
          if ( ! in_array(strtolower($image->getClientOriginalExtension()), $valid_extensions) ){
              return redirect()->back()->withInput($request->input())->with('error', 'Only .jpg, .jpeg and .png is allowed extension') ;
          }
          $file_base_name = str_replace('.'.$image->getClientOriginalExtension(), '', $image->getClientOriginalName());
          if (in_array(strtolower($image->getClientOriginalExtension()), $image_extensions)) {
               $resized_thumb = Image::make($image)->resize(512, 512)->stream();
          }else {
            $resized_thumb = '512';
          }
        
          $thumbnail = strtolower(str_slug($file_base_name)).'.' . $image->getClientOriginalExtension();

          $thumbnailPath = '/public/project_files/'.$project_id.'/'.$thumbnail;

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

      if (in_array(strtolower($image->getClientOriginalExtension()), $image_extensions)) {
        $file_type = 'Image';
        }
        // elseif (in_array(strtolower($image->getClientOriginalExtension()), $doc_extensions)) {
    //         $file_type = 'Doc';
    //     }
    //     elseif (in_array(strtolower($image->getClientOriginalExtension()), $pdf_extensions)) {
    //         $file_type = 'Pdf';
    //     }
    //     elseif (in_array(strtolower($image->getClientOriginalExtension()), $excel_extensions)) {
    //         $file_type = 'Excel';
    //     }
    //     elseif (in_array(strtolower($image->getClientOriginalExtension()), $zip_extensions)) {
    //         $file_type = 'Zip';
    //     }
    //     elseif(in_array(strtolower($image->getClientOriginalExtension()), $other_extensions)) {
    //         $file_type = 'Others';
    //     } 
        
    if (!$project->project_name) {
        $project_name = $project->sub_category_name;
    } else {
        $project_name = $project->project_title;
    }
    
    
      $data = [
        'project_id' => $project_id,
        'bid_id' => $bid_id,
        'pro_id' => $bid->user_id,
        'cus_id' => $bid->cus_id,
        'project_name' => $project_name,
        'file_type' => $file_type,
        'sender_name' => $customer->first_name,
    ];
   
    if ($thumbnail){
        $data['file_name'] = $thumbnail;
    }

    $message = $customer->first_name.' Sent you a file, click the files button below to preview file. file name is '. $thumbnail  ;
    $chat = new Message;
    $chat->message = $message;
    $chat->sender_id = $bid->cus_id;
    $chat->receiver_id = $bid->user_id;
    $chat->bid_id = $bid_id;
    $chat->project_id = $project_id;
    $chat->save();

   $file_data = ProjectFile::create($data);

    if ($file_data) {
        $set['UBUYAPI_V2'][]=array(
                        
            'msg' =>  'File sent',
            'success' =>  1,
            
        );
    } else {
        $set['UBUYAPI_V2'][]=array(
                        
            'msg' =>  'upload failed',
            'success' =>  0,
        );
    }
    
    header( 'Content-Type: application/json; charset=utf-8' );
    echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
    die();
}
  // Save pro files

    
}
