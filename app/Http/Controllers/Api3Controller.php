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
use App\State;
use App\AppHeader;
use App\NewProjectBid;
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
use App\NewProject;
use App\Skill;
use App\ProjectSkill;
use App\SafetyLog;
use App\Dispute;
use App\DisputeFile;
use App\DisputeCategory;
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

class Api3Controller extends Controller
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
           $category = Category::get();
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

       /* this is a debugger to save draft tasks */
       public function SaveDraftDebug(){
            $user_id = filter_input(INPUT_GET, 'user_id', FILTER_SANITIZE_STRING);
            $qtitle = filter_input(INPUT_GET, 'project_title', FILTER_SANITIZE_STRING);
            $project_id = filter_input(INPUT_GET, 'project_id', FILTER_SANITIZE_STRING);
            $skill_id = filter_input(INPUT_GET, 'skill_id', FILTER_SANITIZE_STRING);

            $skill = Skill::where('id', $skill_id)->first();

            if ($qtitle) {
            $title = $qtitle;
            }else{
                $title = $skill->skill_title." draft task";
            }

            $user = User::find($user_id)->first();
            $has_q_draft = NewProject::where('user_id', $user_id)->where('status', 0)->first();

            if ($has_q_draft) {
                $draft = [
                    'project_title' => $title,
                ];

            
                NewProject::where('user_id', $user_id)->where('status', 0)->update($draft);

                $skillData = [
                    'project_id' => $has_q_draft->id,
                    'skill_id' => $skill->id,
                    'skill_title' => $skill->skill_title,
                ];

                $check_skill = ProjectSkill::where('project_id', $has_q_draft->id)->where('skill_id', $skill->id)->first();

                if (!$check_skill) {
                    ProjectSkill::create($skillData);
                }
                

                $draft_data = $has_q_draft;

            }else{
                $draft = [
                    'user_id' => $user_id,
                    'project_title' => $title,
                    'phone_number' => $user->number,
                    'cus_name' => $user->first_name." ".$user->last_name,
                ];

                $draft_data = NewProject::create($draft);

                $skillData = [
                    'project_id' => $draft_data->id,
                    'skill_id' => $skill->id,
                    'skill_title' => $skill->skill_title,
                ];
                $check_skill = ProjectSkill::where('project_id', $draft_data->id)->where('skill_id', $skill->id)->first();

                if (!$check_skill) {
                    ProjectSkill::create($skillData);
                }
            }
            
            

            $set['UBUYAPI_V2'][]=array(
                'project_id' =>$draft_data->id,
                'success'=>'1');
        header( 'Content-Type: application/json; charset=utf-8' );
        echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
        die();

       }
       public function debugSave(Request $request)
       {
           /* our variables to store */
           $user_id = $request->user_id;
        //    get user data
           $user = User::find($user_id)->first();
    
        //    $project_id = $request->project_id;
           $task_title = $request->task_title;
           $des = $request->des;
           $budget = $request->budget;
           $cat_id = $request->cat_id;
    
           $sub_id = $request->sub_id;
        //    get sub category data
        $subcat = SubCategory::where('id', $sub_id)->first();

           $user_name = $user->first_name." ".$user->last_name;
           $user_number = $user->number;
           $pay_type = $subcat->payment_type;

            $has_q_draft = NewProject::where('user_id', $user_id)->where('status', 0)->first();
            
           if($has_q_draft){
            $project_data = [
                'user_id' => $user_id,
                'project_title' => $task_title,
                'sub_category_id' => $sub_id,
                'phone_number' => $user_number,
                'cus_name' => $user_name,
                'project_message' => $des,
                'upay_type' => $pay_type,
            ];

            $project_id = $has_q_draft->id;

            NewProject::where('user_id', $user_id)->where('status', 0)->update($project_data);
           }else{

            $project_data = [
                'user_id' => $user_id,
                'project_title' => $task_title,
                'sub_category_id' => $sub_id,
                'phone_number' => $user_number,
                'cus_name' => $user_name,
                'project_message' => $des,
                'upay_type' => $pay_type,
            ];

            $project_response_data = NewProject::create($project_data);

            $project_id = $project_response_data->id;

           }


           $set['UBUYAPI_V2'][]=array(
            'user_id' =>$user_id,
            'project_id' =>$project_id,
            'des' =>$des,
            'budget' =>$budget,
            'cat_id' =>$cat_id,
            'sub_id' =>$sub_id,
            'success'=>'1');

                header( 'Content-Type: application/json; charset=utf-8' );
                echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
                die();

       }
       public function saveEdit(Request $request)
       {
           /* our variables to store */
           $user_id = $request->user_id;
        //    get user data
           $user = User::find($user_id)->first();
    
           $task_title = $request->task_title;
           $des = $request->des;
           $budget = $request->budget;
           $cat_id = $request->cat_id;
           $project_id = $request->project_id;
    
           $sub_id = $request->sub_id;
        //    get sub category data
        $subcat = SubCategory::where('id', $sub_id)->first();

           $user_name = $user->first_name." ".$user->last_name;
           $user_number = $user->number;
           $pay_type = $subcat->payment_type;

            $has_project = NewProject::where('id', $project_id)->first();
            
           if($has_project){
            $project_data = [
                'user_id' => $user_id,
                'project_title' => $task_title,
                'sub_category_id' => $sub_id,
                'phone_number' => $user_number,
                'cus_name' => $user_name,
                'project_message' => $des,
                'upay_type' => $pay_type,
            ];

            $project_id = $has_project->id;

            NewProject::where('id', $project_id)->update($project_data);

            $set['UBUYAPI_V2'][]=array(
                'user_id' =>$user_id,
                'project_id' =>$project_id,
                'des' =>$des,
                'budget' =>$budget,
                'cat_id' =>$cat_id,
                'sub_id' =>$sub_id,
                'success'=>'1');
           }else{
               $set['UBUYAPI_V2'][]= array(
                'msg' => "Error updating user please fill all forms"
               );
           }


          

                header( 'Content-Type: application/json; charset=utf-8' );
                echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
                die();

       }

       public function deleteProjectSkill(){
            $project_id = filter_input(INPUT_GET, 'project_id', FILTER_SANITIZE_STRING);
            $skill_title = filter_input(INPUT_GET, 'skill_title', FILTER_SANITIZE_STRING);

          ProjectSkill::where('project_id', $project_id)->where('skill_title', $skill_title)->delete();
            
            $set['UBUYAPI_V2'][]=array(
                'skill_id' =>$skill_title,
                'success'=>'1');
        header( 'Content-Type: application/json; charset=utf-8' );
        echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
        die();

       }

       public function singleProjectSB(){
        $project_id = filter_input(INPUT_GET, 'project_id', FILTER_SANITIZE_STRING);
        $version_ = filter_input(INPUT_GET, 'version_', FILTER_SANITIZE_STRING);

        if($version_ == 0){

            $project = Project::where('id', $project_id)->first();
            if($project){

                $projectBid = ProjectBid::where('project_id', $project_id)->get();
            }
            
        }elseif($version_ == 1){
            
            $project = NewProject::where('id', $project_id)->first();
            if($project){

                $projectBid = NewProjectBid::where('project_id', $project_id)->get();
            }
            
            
        }
        
        
        
        $projectSkill = ProjectSkill::where('project_id', $project_id)->get();
        // $row['project'] = $project;
        $row['project_skill'] = $projectSkill;

        /* loop for bids */
        foreach($projectBid as $bid){
            $bidder = User::where('id',$bid->user_id)->first();

            $date = Carbon::parse($bid->created_at); // now date is a carbon instance

            if ($bidder->image) {
                $bidder_image = 'https://ubuy.ng/uploads/images/profile_pics/'.$bidder->image;
            }else{
                $bidder_image = 'https://ubuy.ng/mvp_ui/images/icons/chat_user_icon.png';
            }

            $row['project_bids'][] = array(
                'bid_id' => $bid->id,
                'pro_id' => $bidder->id,
                'bid_message' => $bid->bid_message,
                'bid_amount' => $bid->bid_amount,
                'bid_status' => $bid->status,
                'project_id' => $bid->project_id,
                'created_at' => $date->diffForHumans(),
                'pro_name' => $bidder->first_name.' '.$bidder->last_name,
                'profile_photo' => $bidder_image,
                'material_fee' => $bid->material_fee,
                'service_fee' => $bid->service_fee,
                'bid_type' => $bid->bid_type,
                'version_' => $bid->version_,
            );

        }
        
        $set['UBUYAPI_V2']=$row;
            
            header( 'Content-Type: application/json; charset=utf-8' );
            echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
            die();
       }
       public function singleArchiveProjectSB(){
        $project_id = filter_input(INPUT_GET, 'project_id', FILTER_SANITIZE_STRING);
        $version_ = filter_input(INPUT_GET, 'version_', FILTER_SANITIZE_STRING);

        if($version_ == 0){

            $project = Project::where('id', $project_id)->first();
            if($project){

                $projectBid = ProjectBid::where('project_id', $project_id)->get();
            }
            
        }elseif($version_ == 1){
            
            $project = NewProject::where('id', $project_id)->first();
            if($project){

                $projectBid = NewProjectBid::where('project_id', $project_id)->get();
            }
            
            
        }
        
        
        
        $projectSkill = ProjectSkill::where('project_id', $project_id)->get();
        // $row['project'] = $project;
        $row['project_skill'] = $projectSkill;

        /* loop for bids */
        foreach($projectBid as $bid){
            $bidder = User::where('id',$bid->user_id)->first();

            $date = Carbon::parse($bid->created_at); // now date is a carbon instance

            if ($bidder->image) {
                $bidder_image = 'https://ubuy.ng/uploads/images/profile_pics/'.$bidder->image;
            }else{
                $bidder_image = 'https://ubuy.ng/mvp_ui/images/icons/chat_user_icon.png';
            }

            $row['project_bids'][] = array(
                'bid_id' => $bid->id,
                'pro_id' => $bidder->id,
                'bid_message' => $bid->bid_message,
                'bid_amount' => $bid->bid_amount,
                'bid_status' => $bid->status,
                'project_id' => $bid->project_id,
                'created_at' => $date->diffForHumans(),
                'pro_name' => $bidder->first_name.' '.$bidder->last_name,
                'profile_photo' => $bidder_image,
                'material_fee' => $bid->material_fee,
                'service_fee' => $bid->service_fee,
                'bid_type' => $bid->bid_type,
                'version_' => $bid->version_,
            );

        }
        
        $set['UBUYAPI_V2']=$row;
            
            header( 'Content-Type: application/json; charset=utf-8' );
            echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
            die();
       }


       public function completedProject(){
            $project_id = filter_input(INPUT_GET, 'project_id', FILTER_SANITIZE_STRING);
            $version_ = filter_input(INPUT_GET, 'version_', FILTER_SANITIZE_STRING);

            if($version_ == 0){
                $project = Project::where('id', $project_id)->first();
            }elseif($version_ == 1){
                $project = NewProject::where('id', $project_id)->first();
            }

           

            if($project){
                $pro = User::where('id', $project->pro_id)->first();
                
                if ($pro->image) {
                    $pro_image = 'https://ubuy.ng/uploads/images/profile_pics/'.$pro->image;
                }else{
                    $pro_image = 'https://ubuy.ng/mvp_ui/images/icons/chat_user_icon.png';
                }


                $set['UBUYAPI_V2'][]=array(
                    'full_name' => $pro->first_name.' '.$pro->last_name,
                    'image' => $pro_image,
                    'success'=>'1'
                );
            }else{
                $set['UBUYAPI_V2'][]=array(
                    'msg' =>'Sorry, project not found',
                    'success'=>'0'
                );
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


    public function apiPendingProjects()
        {
        
            if (isset($_GET['user_id'])) {
                $user_id = filter_input(INPUT_GET, 'user_id', FILTER_SANITIZE_STRING);

                $user = Auth::loginUsingId($user_id);

                if (!$user) {
                    $set['UBUYAPI_V2'][]=array('msg' =>'Account not found','success'=>'0');
                }else if($user){
                // $projects = $user->projectsSubCat->get();

                $v3_projects = DB::table("new_projects") 
                ->where('new_projects.user_id', '=', $user_id)
                ->where('new_projects.status', '=', 1)
                ->select('new_projects.id as project_id', 'new_projects.user_id as user_id',  'new_projects.project_message', 'new_projects.created_at', 'new_projects.sub_category_name','new_projects.status', 'new_projects.sub_category_id','new_projects.address', 'new_projects.budget', 'new_projects.project_title')
                ->orderBy('new_projects.id', 'desc')->get();
                
                /* 
                here we check if v3 projects are on db and display them else 
                we set v4_checker to null
                 */

             if($v3_projects){
                $v3_checker = null;
                foreach($v3_projects as $project){
                    // counting bids in project here
                    $bids = NewProjectBid::where('project_id','=', $project->project_id)->get();

                    /* now we get the latest 3 bids for the data */

                    $bid_1 = DB::table("new_project_bids")
                    ->where('new_project_bids.project_id', '=', $project->project_id)
                    ->join('users', 'users.id', '=', 'new_project_bids.user_id')
                    ->select('new_project_bids.id as bid_id', 'new_project_bids.user_id as pro_id',  'new_project_bids.bid_message', 'new_project_bids.bid_amount', 'users.image as profile_photo', 'new_project_bids.bid_status', 'new_project_bids.project_id')
                    ->skip(0)->first();

                    $bid_2 = DB::table("new_project_bids")
                    ->where('new_project_bids.project_id', '=', $project->project_id)
                    ->join('users', 'users.id', '=', 'new_project_bids.user_id')
                    ->select('new_project_bids.id as bid_id', 'new_project_bids.user_id as pro_id',  'new_project_bids.bid_message', 'new_project_bids.bid_amount', 'users.image as profile_photo', 'new_project_bids.bid_status', 'new_project_bids.project_id')
                    ->skip(1)->first();

                    $bid_3 = DB::table("new_project_bids")
                    ->where('new_project_bids.project_id', '=', $project->project_id)
                    ->join('users', 'users.id', '=', 'new_project_bids.user_id')
                    ->select('new_project_bids.id as bid_id', 'new_project_bids.user_id as pro_id',  'new_project_bids.bid_message', 'new_project_bids.bid_amount', 'users.image as profile_photo', 'new_project_bids.bid_status', 'new_project_bids.project_id')
                    ->skip(2)->first();


                    if ($bid_1) {
                         
                        if ($bid_1->profile_photo) {
                            $bidder_1_image = 'https://ubuy.ng/uploads/images/profile_pics/'.$bid_1->profile_photo;
                        }else{
                            $bidder_1_image = 'https://ubuy.ng/mvp_ui/images/icons/chat_user_icon.png';
                        }
                    }else{
                        $bidder_1_image = null;
                    }
                    if ($bid_2) {
                         
                        if ($bid_2->profile_photo) {
                            $bidder_2_image = 'https://ubuy.ng/uploads/images/profile_pics/'.$bid_2->profile_photo;
                        }else{
                            $bidder_2_image = 'https://ubuy.ng/mvp_ui/images/icons/chat_user_icon.png';
                        }
                    }else{
                        $bidder_2_image = null;
                    }
                    if ($bid_3) {
                         
                        if ($bid_3->profile_photo) {
                            $bidder_3_image = 'https://ubuy.ng/uploads/images/profile_pics/'.$bid_3->profile_photo;
                        }else{
                            $bidder_3_image = 'https://ubuy.ng/mvp_ui/images/icons/chat_user_icon.png';
                        }
                    }else{
                        $bidder_3_image = null;
                    }

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

                    /* now we get the top 4 skills*/
                   
                    $skill_1 = DB::table("project_skills")
                    ->where('project_skills.project_id', '=', $project->project_id)
                    ->select('project_skills.skill_title')
                    ->skip(0)->first();
                    $skill_2 = DB::table("project_skills")
                    ->where('project_skills.project_id', '=', $project->project_id)
                    ->select('project_skills.skill_title')
                    ->skip(1)->first();
                    $skill_3 = DB::table("project_skills")
                    ->where('project_skills.project_id', '=', $project->project_id)
                    ->select('project_skills.skill_title')
                    ->skip(2)->first();
                    $skill_4 = DB::table("project_skills")
                    ->where('project_skills.project_id', '=', $project->project_id)
                    ->select('project_skills.skill_title')
                    ->skip(3)->first();


                    if ($skill_1) {
                         
                        $project_skill_1 = $skill_1->skill_title;
                    }else{
                        $project_skill_1 = null;
                    }
                    if ($skill_2) {
                         
                        $project_skill_2 = $skill_2->skill_title;
                    }else{
                        $project_skill_2 = null;
                    }
                    if ($skill_3) {
                         
                        $project_skill_3 = $skill_3->skill_title;
                    }else{
                        $project_skill_3 = null;
                    }
                    if ($skill_4) {
                         
                        $project_skill_4 = $skill_4->skill_title;
                    }else{
                        $project_skill_4 = null;
                    }
                   

                    // using carbon to make date readable
                        $date = Carbon::parse($project->created_at); // now date is a carbon instance
                
                        // converting all data to json format
                        $v3_project_pending[]=array(
                            'project_id' => $project->project_id,
                            'sub_category_id' => $project->sub_category_id,
                            'user_id' => $project->user_id,
                            'sub_category_name' => $project->sub_category_name,
                            'project_title' => $project->project_title,
                            'address' => $project->address,
                            'bid_count' => $bid_count,
                            'brief' => $project->project_message,
                            'budget' => $project->budget,
                            'bid_status' => $bid_status,
                            'bidder_1_image' => $bidder_1_image,
                            'bidder_2_image' => $bidder_2_image,
                            'bidder_3_image' => $bidder_3_image,
                            'project_skill_1' => $project_skill_1,
                            'project_skill_2' => $project_skill_2,
                            'project_skill_3' => $project_skill_3,
                            'project_skill_4' => $project_skill_4,
                            'p_version' => 1,
                            'created_at' => $date->diffForHumans(),
                        );
                        $row['v3_project_pending']=$v3_project_pending;
                   

                   
                    $set['UBUYAPI_V2'] = $row;
                }                                                                                                                                                               
            } elseif($v3_projects->isEmpty()){
                $v3_checker = null;
                $set['UBUYAPI_V2'][]=array(
                    'msg' =>'No v3 projects found',
                    'success'=>'0'
                );

             }


             $v2_projects = DB::table("projects")
             ->where('projects.user_id', '=', $user_id)
             ->select('projects.id as project_id', 'projects.user_id as user_id',  'projects.project_message', 'projects.created_at', 'projects.sub_category_name','projects.status', 'projects.sub_category_id','projects.address')
             ->orderBy('projects.id', 'desc')->get();

             if($v2_projects->isEmpty()){
                 $v2_checker = null;
                $set['UBUYAPI_V2'][]=array('msg' =>'No v2 projects found','success'=>'0');


             }
         else if($v2_projects){

            $v2_checker = true;
             foreach($v2_projects as $project){
                 // counting bids in project here
                 $bids = ProjectBid::where('project_id','=', $project->project_id)->get();

                 /* now we get the latest 3 bids for the data */

                 $bid_1 = DB::table("project_bids")
                 ->where('project_bids.project_id', '=', $project->project_id)
                 ->join('users', 'users.id', '=', 'project_bids.user_id')
                 ->select('project_bids.id as bid_id', 'project_bids.user_id as pro_id',  'project_bids.bid_message', 'project_bids.bid_amount', 'users.image as profile_photo', 'project_bids.bid_status', 'project_bids.project_id')
                 ->skip(0)->first();

                 $bid_2 = DB::table("project_bids")
                 ->where('project_bids.project_id', '=', $project->project_id)
                 ->join('users', 'users.id', '=', 'project_bids.user_id')
                 ->select('project_bids.id as bid_id', 'project_bids.user_id as pro_id',  'project_bids.bid_message', 'project_bids.bid_amount', 'users.image as profile_photo', 'project_bids.bid_status', 'project_bids.project_id')
                 ->skip(1)->first();

                 $bid_3 = DB::table("project_bids")
                 ->where('project_bids.project_id', '=', $project->project_id)
                 ->join('users', 'users.id', '=', 'project_bids.user_id')
                 ->select('project_bids.id as bid_id', 'project_bids.user_id as pro_id',  'project_bids.bid_message', 'project_bids.bid_amount', 'users.image as profile_photo', 'project_bids.bid_status', 'project_bids.project_id')
                 ->skip(2)->first();


                 if ($bid_1) {
                      
                     if ($bid_1->profile_photo) {
                         $bidder_1_image = 'https://ubuy.ng/uploads/images/profile_pics/'.$bid_1->profile_photo;
                     }else{
                         $bidder_1_image = 'https://ubuy.ng/mvp_ui/images/icons/chat_user_icon.png';
                     }
                 }else{
                     $bidder_1_image = null;
                 }
                 if ($bid_2) {
                      
                     if ($bid_2->profile_photo) {
                         $bidder_2_image = 'https://ubuy.ng/uploads/images/profile_pics/'.$bid_2->profile_photo;
                     }else{
                         $bidder_2_image = 'https://ubuy.ng/mvp_ui/images/icons/chat_user_icon.png';
                     }
                 }else{
                     $bidder_2_image = null;
                 }
                 if ($bid_3) {
                      
                     if ($bid_3->profile_photo) {
                         $bidder_3_image = 'https://ubuy.ng/uploads/images/profile_pics/'.$bid_3->profile_photo;
                     }else{
                         $bidder_3_image = 'https://ubuy.ng/mvp_ui/images/icons/chat_user_icon.png';
                     }
                 }else{
                     $bidder_3_image = null;
                 }


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
                        $v2_project_pending[]=array(
                            'project_id' => $project->project_id,
                            'sub_category_id' => $project->sub_category_id,
                            'user_id' => $project->user_id,
                            'sub_category_name' => $project->sub_category_name,
                            'address' => $project->address,
                            'bid_count' => $bid_count,
                            'brief' => $project->project_message,
                            'bid_status' => $bid_status,
                            'bidder_1_image' => $bidder_1_image,
                            'bidder_2_image' => $bidder_2_image,
                            'bidder_3_image' => $bidder_3_image,
                            'p_version' => 0,
                            'created_at' => $date->diffForHumans(),
                         );
                         $row['v2_project_pending']=$v2_project_pending;
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

    public function apiInProgressProjects()
    {
        
        if (isset($_GET['user_id'])) {
            $user_id = filter_input(INPUT_GET, 'user_id', FILTER_SANITIZE_STRING);

            $user = Auth::loginUsingId($user_id);

            if (!$user) {
                $set['UBUYAPI_V2'][]=array('msg' =>'Account not found','success'=>'0');
            }else if($user){
                // $projects = $user->projectsSubCat->get();

                $v3_projects = DB::table("new_projects") 
                ->where('new_projects.user_id', '=', $user_id)
                ->where('new_projects.status', '=', 2)
                ->orderBy('new_projects.id', 'desc')->get();
                
                /* 
                here we check if v3 projects are on db and display them else 
                we set v3_checker to null
                 */

                if($v3_projects){
                    $v3_checker = null;
                    foreach($v3_projects as $project){

                        /* now we get the latest 3 bids for the data */

                        $selected_pro = DB::table("new_project_bids")
                        ->where('new_project_bids.project_id', '=', $project->id)
                        ->where('new_project_bids.bid_status', '=', 2)
                        ->join('users', 'users.id', '=', 'new_project_bids.user_id')
                        ->select('new_project_bids.id as bid_id', 'new_project_bids.user_id as pro_id',  'new_project_bids.bid_message', 'new_project_bids.bid_duration', 'new_project_bids.bid_amount', 'users.image as profile_photo', 'users.first_name', 'users.last_name', 'new_project_bids.bid_status', 'new_project_bids.project_id')
                        ->first();

                    


                        if ($selected_pro) {
                            
                            if ($selected_pro->profile_photo) {
                                $selected_pro_image = 'https://ubuy.ng/uploads/images/profile_pics/'.$selected_pro->profile_photo;
                            }else{
                                $selected_pro_image = 'https://ubuy.ng/mvp_ui/images/icons/chat_user_icon.png';
                            }
                        }else{
                            $selected_pro_image = null;
                        }
                        


                        // // using carbon to make date readable
                            $started_date = Carbon::parse($project->started_at); // now date is a carbon instance
                            $duration = '+'.$selected_pro->bid_duration.' days';
                            $deadline_date =   Carbon::parse(date('Y-m-d', strtotime($duration, strtotime($project->started_at))));

                    
                            // converting all data to json format
                            $v3_project_inprogress[]=array(
                                'project_id' => $project->id,
                                'sub_category_id' => $project->sub_category_id,
                                'user_id' => $project->user_id,
                                'sub_category_name' => $project->sub_category_name,
                                'project_title' => $project->project_title,
                                'address' => $project->address,
                                'brief' => $project->project_message,
                                'task_amount' => $selected_pro->bid_amount,
                                'task_status' => $project->status,
                                'selected_pro_image' => $selected_pro_image,
                                'pro_name' => $selected_pro->first_name.' '.$selected_pro->last_name,
                                'p_version' => 1,
                                'started_at' => $started_date->diffForHumans(),
                                'deadline_at' => $deadline_date->diffForHumans(),
                            );
                            $row['v3_project_inprogress']=$v3_project_inprogress;
                    

                    
                    }                                                                                                                                                               
                } elseif($v3_projects->isEmpty()){
                    $v3_checker = null;
                    $row['v3_project_inprogress'][]=array(
                        'msg' =>'No v3 projects found',
                        'success'=>'0'
                    );

                }


               $v2_projects = DB::table("projects")
                ->where('projects.user_id', '=', $user_id)
                ->where('projects.status', '=', 2)
                ->orderBy('projects.id', 'desc')->get();


                if($v2_projects->isEmpty()){
                    $v2_checker = null;
                    $row['v2_project_inprogress'][]=array('msg' =>'No v2 projects found','success'=>'0');


                }else if($v2_projects){

                    $v2_checker = true;
                    foreach($v2_projects as $project){

                            /* now we get the latest 3 bids for the data */

                            $selected_pro = DB::table("project_bids")
                            ->where('project_bids.project_id', '=', $project->id)
                            ->where('project_bids.user_id', '=', $project->pro_id)
                            ->join('users', 'users.id', '=', 'project_bids.user_id')
                            ->select('project_bids.id as bid_id', 'project_bids.user_id as pro_id',  'project_bids.bid_message', 'project_bids.bid_duration', 'project_bids.bid_amount', 'users.image as profile_photo', 'users.first_name', 'users.last_name', 'project_bids.bid_status', 'project_bids.project_id')
                            ->first();

                        


                            if ($selected_pro) {
                                
                                if ($selected_pro->profile_photo) {
                                    $selected_pro_image = 'https://ubuy.ng/uploads/images/profile_pics/'.$selected_pro->profile_photo;
                                }else{
                                    $selected_pro_image = 'https://ubuy.ng/mvp_ui/images/icons/chat_user_icon.png';
                                }
                                }



                            
                        // // using carbon to make date readable
                        $started_date = Carbon::parse($project->started_at); // now date is a carbon instance
                        $duration = '+'.$selected_pro->bid_duration.' days';
                        $deadline_date =   Carbon::parse(date('Y-m-d', strtotime($duration, strtotime($project->started_at))));


                            // using carbon to make date readable
                        $date = Carbon::parse($project->created_at); // now date is a carbon instance
                
                        $v2_project_inprogress[]=array(
                            'project_id' => $project->id,
                            'sub_category_id' => $project->sub_category_id,
                            'user_id' => $project->user_id,
                            'sub_category_name' => $project->sub_category_name,
                            'address' => $project->address,
                            'brief' => $project->project_message,
                            'task_amount' => $selected_pro->bid_amount,
                            'task_status' => $project->status,
                            'selected_pro_image' => $selected_pro_image,
                            'pro_name' => $selected_pro->first_name.' '.$selected_pro->last_name,
                            'p_version' => 0,
                            'started_at' => $started_date->diffForHumans(),
                            'deadline_at' => $deadline_date->diffForHumans(),
                        );

                        $row['v2_project_inprogress']=$v2_project_inprogress;
                        
                    }                                                                                                                                                               
                } 
                
                
                $set['UBUYAPI_V2'] = $row;
            }
            
        }else{
            $set['UBUYAPI_V2'][]=array('msg' =>'Account not found','success'=>'0');
        }
        header( 'Content-Type: application/json; charset=utf-8' );
        echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
        die();
        }


    public function apiCompletedProjects()
        {
        
            if (isset($_GET['user_id'])) {
                $user_id = filter_input(INPUT_GET, 'user_id', FILTER_SANITIZE_STRING);

                $user = Auth::loginUsingId($user_id);

                if (!$user) {
                    $set['UBUYAPI_V2'][]=array('msg' =>'Account not found','success'=>'0');
                }else if($user){
                // $projects = $user->projectsSubCat->get();

           $v3_projects = DB::table("new_projects") 
                ->where('new_projects.user_id', '=', $user_id)
                ->where('new_projects.status', '=', 3)
                ->orderBy('new_projects.id', 'desc')->get();
                
                /* 
                here we check if v3 projects are on db and display them else 
                we set v3_checker to null
                 */

             if($v3_projects){
                $v3_checker = null;
                foreach($v3_projects as $project){

                    /* now we get the latest 3 bids for the data */

                    $selected_pro = DB::table("new_project_bids")
                    ->where('new_project_bids.project_id', '=', $project->id)
                    ->where('new_project_bids.user_id', '=', $project->pro_id)
                    ->join('users', 'users.id', '=', 'new_project_bids.user_id')
                    ->select('new_project_bids.id as bid_id', 'new_project_bids.user_id as pro_id',  'new_project_bids.bid_message', 'new_project_bids.bid_duration', 'new_project_bids.bid_amount', 'users.image as profile_photo', 'users.first_name', 'users.last_name', 'new_project_bids.bid_status', 'new_project_bids.project_id')
                    ->first();

                


                    if ($selected_pro) {
                         
                        if ($selected_pro->profile_photo) {
                            $selected_pro_image = 'https://ubuy.ng/uploads/images/profile_pics/'.$selected_pro->profile_photo;
                        }else{
                            $selected_pro_image = 'https://ubuy.ng/mvp_ui/images/icons/chat_user_icon.png';
                        }
                    }else{
                        $selected_pro_image = null;
                    }
                    


                    // // using carbon to make date readable
                        $started_date = Carbon::parse($project->started_at); // now date is a carbon instance
                        $duration = '+'.$selected_pro->bid_duration.' days';
                        $deadline_date =   Carbon::parse(date('Y-m-d', strtotime($duration, strtotime($project->started_at))));

                
                        // converting all data to json format
                        $v3_project_completed[]=array(
                            'project_id' => $project->id,
                            'sub_category_id' => $project->sub_category_id,
                            'user_id' => $project->user_id,
                            'sub_category_name' => $project->sub_category_name,
                            'project_title' => $project->project_title,
                            'address' => $project->address,
                            'brief' => $project->project_message,
                            'task_amount' => $selected_pro->bid_amount,
                            'task_status' => $project->status,
                            'selected_pro_image' => $selected_pro_image,
                            'pro_name' => $selected_pro->first_name.' '.$selected_pro->last_name,
                            'p_version' => 1,
                            'started_at' => $started_date->diffForHumans(),
                            'deadline_at' => $deadline_date->diffForHumans(),
                        );
                        $row['v3_project_completed']=$v3_project_completed;
                   

                   
                }                                                                                                                                                               
            } elseif($v3_projects->isEmpty()){
                $v3_checker = null;
                $row['v3_project_completed'][]=array(
                    'msg' =>'No v3 projects found',
                    'success'=>'0'
                );

             }

             /* starting v2 completed tasks */

             $v2_projects = DB::table("projects")
             ->where('projects.user_id', '=', $user_id)
             ->where('projects.status', '=', 3)
             ->orderBy('projects.id', 'desc')->get();

             if($v2_projects->isEmpty()){
                 $v2_checker = null;
                $row['v2_project_completed'][]=array('msg' =>'No v2 projects found','success'=>'0');


             }
            else if($v2_projects){

                $v2_checker = true;
                foreach($v2_projects as $project){

                    /* now we get the latest 3 bids for the data */

                    $selected_pro = DB::table("project_bids")
                  ->where('project_bids.project_id', '=', $project->id)
                  ->where('project_bids.user_id', '=', $project->pro_id)
                  ->join('users', 'users.id', '=', 'project_bids.user_id')
                  ->select('project_bids.id as bid_id', 'project_bids.user_id as pro_id',  'project_bids.bid_message', 'project_bids.bid_duration', 'project_bids.bid_amount', 'users.image as profile_photo', 'users.first_name', 'users.last_name', 'project_bids.bid_status', 'project_bids.project_id')
                  ->first();

                


                    if ($selected_pro) {
                        
                        if ($selected_pro->profile_photo) {
                            $selected_pro_image = 'https://ubuy.ng/uploads/images/profile_pics/'.$selected_pro->profile_photo;
                        }else{
                            $selected_pro_image = 'https://ubuy.ng/mvp_ui/images/icons/chat_user_icon.png';
                        }
                    }



                        
                    // // using carbon to make date readable
                    $started_date = Carbon::parse($project->started_at); // now date is a carbon instance
                    if (!$selected_pro->bid_duration) {
                        $duration = '+ 0 days';
                    }else{

                        $duration = '+'.$selected_pro->bid_duration.' days';
                    }
                    $deadline_date =   Carbon::parse(date('Y-m-d', strtotime($duration, strtotime($project->started_at))));


                    // using carbon to make date readable
                    $date = Carbon::parse($project->created_at); // now date is a carbon instance
            
                    $v2_project_completed[]=array(
                        'project_id' => $project->id,
                        'sub_category_id' => $project->sub_category_id,
                        'user_id' => $project->user_id,
                        'sub_category_name' => $project->sub_category_name,
                        'address' => $project->address,
                        'brief' => $project->project_message,
                        'task_amount' => $selected_pro->bid_amount,
                        'task_status' => $project->status,
                        'selected_pro_image' => $selected_pro_image,
                        'pro_name' => $selected_pro->first_name.' '.$selected_pro->last_name,
                        'p_version' => 0,
                        'started_at' => $started_date->diffForHumans(),
                        'deadline_at' => $deadline_date->diffForHumans(),
                    );
                    $row['v2_project_completed']=$v2_project_completed;

                }
            
                    $set['UBUYAPI_V2'] = $row;
            }                                                                                                                                                               
         } 


            }
            
            header( 'Content-Type: application/json; charset=utf-8' );
            echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
            die();
        }
 
        
    public function apiArchivedProjects()
    {
    
        if (isset($_GET['user_id'])) {
            $user_id = filter_input(INPUT_GET, 'user_id', FILTER_SANITIZE_STRING);

            $user = Auth::loginUsingId($user_id);

            if (!$user) {
                $set['UBUYAPI_V2'][]=array('msg' =>'Account not found','success'=>'0');
            }else if($user){
            // $projects = $user->projectsSubCat->get();

            $v3_projects = DB::table("new_projects") 
            ->where('new_projects.user_id', '=', $user_id)
            ->where('new_projects.status', '=', 4)
            ->select('new_projects.id as project_id', 'new_projects.user_id as user_id',  'new_projects.project_message', 'new_projects.created_at', 'new_projects.sub_category_name','new_projects.status', 'new_projects.sub_category_id','new_projects.address', 'new_projects.budget', 'new_projects.project_title')
            ->orderBy('new_projects.id', 'desc')->get();
            
            /* 
            here we check if v3 projects are on db and display them else 
            we set v4_checker to null
             */

         if($v3_projects){
            $v3_checker = null;
            foreach($v3_projects as $project){
                // counting bids in project here
                $bids = NewProjectBid::where('project_id','=', $project->project_id)->get();

                /* now we get the latest 3 bids for the data */

                $bid_1 = DB::table("new_project_bids")
                ->where('new_project_bids.project_id', '=', $project->project_id)
                ->join('users', 'users.id', '=', 'new_project_bids.user_id')
                ->select('new_project_bids.id as bid_id', 'new_project_bids.user_id as pro_id',  'new_project_bids.bid_message', 'new_project_bids.bid_amount', 'users.image as profile_photo', 'new_project_bids.bid_status', 'new_project_bids.project_id')
                ->skip(0)->first();

                $bid_2 = DB::table("new_project_bids")
                ->where('new_project_bids.project_id', '=', $project->project_id)
                ->join('users', 'users.id', '=', 'new_project_bids.user_id')
                ->select('new_project_bids.id as bid_id', 'new_project_bids.user_id as pro_id',  'new_project_bids.bid_message', 'new_project_bids.bid_amount', 'users.image as profile_photo', 'new_project_bids.bid_status', 'new_project_bids.project_id')
                ->skip(1)->first();

                $bid_3 = DB::table("new_project_bids")
                ->where('new_project_bids.project_id', '=', $project->project_id)
                ->join('users', 'users.id', '=', 'new_project_bids.user_id')
                ->select('new_project_bids.id as bid_id', 'new_project_bids.user_id as pro_id',  'new_project_bids.bid_message', 'new_project_bids.bid_amount', 'users.image as profile_photo', 'new_project_bids.bid_status', 'new_project_bids.project_id')
                ->skip(2)->first();


                if ($bid_1) {
                     
                    if ($bid_1->profile_photo) {
                        $bidder_1_image = 'https://ubuy.ng/uploads/images/profile_pics/'.$bid_1->profile_photo;
                    }else{
                        $bidder_1_image = 'https://ubuy.ng/mvp_ui/images/icons/chat_user_icon.png';
                    }
                }else{
                    $bidder_1_image = null;
                }
                if ($bid_2) {
                     
                    if ($bid_2->profile_photo) {
                        $bidder_2_image = 'https://ubuy.ng/uploads/images/profile_pics/'.$bid_2->profile_photo;
                    }else{
                        $bidder_2_image = 'https://ubuy.ng/mvp_ui/images/icons/chat_user_icon.png';
                    }
                }else{
                    $bidder_2_image = null;
                }
                if ($bid_3) {
                     
                    if ($bid_3->profile_photo) {
                        $bidder_3_image = 'https://ubuy.ng/uploads/images/profile_pics/'.$bid_3->profile_photo;
                    }else{
                        $bidder_3_image = 'https://ubuy.ng/mvp_ui/images/icons/chat_user_icon.png';
                    }
                }else{
                    $bidder_3_image = null;
                }

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

                /* now we get the top 4 skills*/
               
                $skill_1 = DB::table("project_skills")
                ->where('project_skills.project_id', '=', $project->project_id)
                ->select('project_skills.skill_title')
                ->skip(0)->first();
                $skill_2 = DB::table("project_skills")
                ->where('project_skills.project_id', '=', $project->project_id)
                ->select('project_skills.skill_title')
                ->skip(1)->first();
                $skill_3 = DB::table("project_skills")
                ->where('project_skills.project_id', '=', $project->project_id)
                ->select('project_skills.skill_title')
                ->skip(2)->first();
                $skill_4 = DB::table("project_skills")
                ->where('project_skills.project_id', '=', $project->project_id)
                ->select('project_skills.skill_title')
                ->skip(3)->first();


                if ($skill_1) {
                     
                    $project_skill_1 = $skill_1->skill_title;
                }else{
                    $project_skill_1 = null;
                }
                if ($skill_2) {
                     
                    $project_skill_2 = $skill_2->skill_title;
                }else{
                    $project_skill_2 = null;
                }
                if ($skill_3) {
                     
                    $project_skill_3 = $skill_3->skill_title;
                }else{
                    $project_skill_3 = null;
                }
                if ($skill_4) {
                     
                    $project_skill_4 = $skill_4->skill_title;
                }else{
                    $project_skill_4 = null;
                }
               

                // using carbon to make date readable
                    $date = Carbon::parse($project->created_at); // now date is a carbon instance
            
                    // converting all data to json format
                    $v3_project_pending[]=array(
                        'project_id' => $project->project_id,
                        'sub_category_id' => $project->sub_category_id,
                        'user_id' => $project->user_id,
                        'sub_category_name' => $project->sub_category_name,
                        'project_title' => $project->project_title,
                        'address' => $project->address,
                        'bid_count' => $bid_count,
                        'brief' => $project->project_message,
                        'budget' => $project->budget,
                        'bid_status' => $bid_status,
                        'bidder_1_image' => $bidder_1_image,
                        'bidder_2_image' => $bidder_2_image,
                        'bidder_3_image' => $bidder_3_image,
                        'project_skill_1' => $project_skill_1,
                        'project_skill_2' => $project_skill_2,
                        'project_skill_3' => $project_skill_3,
                        'project_skill_4' => $project_skill_4,
                        'p_version' => 1,
                        'created_at' => $date->diffForHumans(),
                    );
                    $row['v3_project_pending']=$v3_project_pending;
               

               
                $set['UBUYAPI_V2'] = $row;
            }                                                                                                                                                               
        } elseif($v3_projects->isEmpty()){
            $v3_checker = null;
            $set['UBUYAPI_V2'][]=array(
                'msg' =>'No v3 projects found',
                'success'=>'0'
            );

         }


         $v2_projects = DB::table("projects")
         ->where('projects.user_id', '=', $user_id)
         ->where('projects.status', '=', 4)
         ->select('projects.id as project_id', 'projects.user_id as user_id',  'projects.project_message', 'projects.created_at', 'projects.sub_category_name','projects.status', 'projects.sub_category_id','projects.address')
         ->orderBy('projects.id', 'desc')->get();

         if($v2_projects->isEmpty()){
             $v2_checker = null;
            $set['UBUYAPI_V2'][]=array('msg' =>'No v2 projects found','success'=>'0');


         }
     else if($v2_projects){

        $v2_checker = true;
         foreach($v2_projects as $project){
             // counting bids in project here
             $bids = ProjectBid::where('project_id','=', $project->project_id)->get();

             /* now we get the latest 3 bids for the data */

             $bid_1 = DB::table("project_bids")
             ->where('project_bids.project_id', '=', $project->project_id)
             ->join('users', 'users.id', '=', 'project_bids.user_id')
             ->select('project_bids.id as bid_id', 'project_bids.user_id as pro_id',  'project_bids.bid_message', 'project_bids.bid_amount', 'users.image as profile_photo', 'project_bids.bid_status', 'project_bids.project_id')
             ->skip(0)->first();

             $bid_2 = DB::table("project_bids")
             ->where('project_bids.project_id', '=', $project->project_id)
             ->join('users', 'users.id', '=', 'project_bids.user_id')
             ->select('project_bids.id as bid_id', 'project_bids.user_id as pro_id',  'project_bids.bid_message', 'project_bids.bid_amount', 'users.image as profile_photo', 'project_bids.bid_status', 'project_bids.project_id')
             ->skip(1)->first();

             $bid_3 = DB::table("project_bids")
             ->where('project_bids.project_id', '=', $project->project_id)
             ->join('users', 'users.id', '=', 'project_bids.user_id')
             ->select('project_bids.id as bid_id', 'project_bids.user_id as pro_id',  'project_bids.bid_message', 'project_bids.bid_amount', 'users.image as profile_photo', 'project_bids.bid_status', 'project_bids.project_id')
             ->skip(2)->first();


             if ($bid_1) {
                  
                 if ($bid_1->profile_photo) {
                     $bidder_1_image = 'https://ubuy.ng/uploads/images/profile_pics/'.$bid_1->profile_photo;
                 }else{
                     $bidder_1_image = 'https://ubuy.ng/mvp_ui/images/icons/chat_user_icon.png';
                 }
             }else{
                 $bidder_1_image = null;
             }
             if ($bid_2) {
                  
                 if ($bid_2->profile_photo) {
                     $bidder_2_image = 'https://ubuy.ng/uploads/images/profile_pics/'.$bid_2->profile_photo;
                 }else{
                     $bidder_2_image = 'https://ubuy.ng/mvp_ui/images/icons/chat_user_icon.png';
                 }
             }else{
                 $bidder_2_image = null;
             }
             if ($bid_3) {
                  
                 if ($bid_3->profile_photo) {
                     $bidder_3_image = 'https://ubuy.ng/uploads/images/profile_pics/'.$bid_3->profile_photo;
                 }else{
                     $bidder_3_image = 'https://ubuy.ng/mvp_ui/images/icons/chat_user_icon.png';
                 }
             }else{
                 $bidder_3_image = null;
             }


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
             }
            

             // using carbon to make date readable
                 $date = Carbon::parse($project->created_at); // now date is a carbon instance
         
                 $v2_project_pending[]=array(
                    'project_id' => $project->project_id,
                    'sub_category_id' => $project->sub_category_id,
                    'user_id' => $project->user_id,
                    'sub_category_name' => $project->sub_category_name,
                    'address' => $project->address,
                    'bid_count' => $bid_count,
                    'brief' => $project->project_message,
                    'bid_status' => $bid_status,
                    'bidder_1_image' => $bidder_1_image,
                    'bidder_2_image' => $bidder_2_image,
                    'bidder_3_image' => $bidder_3_image,
                    'p_version' => 0,
                    'created_at' => $date->diffForHumans(),
                 );
                 $row['v2_project_pending']=$v2_project_pending;
            
             
         }                                                                                                                                                               
     } 


        }
        $set['UBUYAPI_V2'] = $row;
        header( 'Content-Type: application/json; charset=utf-8' );
        echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
        die();
     }
}


/* Here we start the api for safety toolkit 
*
* This would have all callbacks for the safety toolkit which includes all reports and logs
*
*/

    public function apiAllCategories(){
        $cats = Category::get();

        $row["top_categories"][] = $cats;

        $pros = DB::table("ratings")
        ->join('users', 'users.id', '=', 'ratings.pro_id')
        ->select('users.id as id', 'users.image', 'users.first_name', 'users.last_name')
        ->orderBy('users.id', 'desc')->get();
        

                    foreach ($pros as $pro) {
                        // getting the pro user details                       

                        $pro_projects = Project::where('pro_id', $pro->id)->count();
                        if ($pro_projects >= 1) {
                            if ($pro->image) {
                                $profile_image = "https://ubuy.ng/uploads/images/profile_pics/".$pro->image;
                            }else{
                
                                $profile_image = 'https://ubuy.ng/mvp_ui/images/icons/chat_user_icon.png';
                            }
                              // getting the pros first service
                        $pro_service = DB::table("services")
                        ->where('services.user_id', '=', $pro->id)
                        ->join('sub_categories', 'sub_categories.id', '=', 'services.sub_category_id')
                        ->select('sub_categories.name')
                        ->first();
            
                        

                            $row["invite_premium"][] = array(
                                'user_id' => $pro->id,
                                'pro_name' => $pro->first_name.' '.$pro->last_name,
                                'project_count' => $pro_projects,
                                'profile_image' => $profile_image,
                                'pro_service' => $pro_service->name,
                                'premium_pro' => 1,
                            );
                            
                        }
            
                    }


                       
                    $set['UBUYAPI_V2'] = $row;
            
        
        header( 'Content-Type: application/json; charset=utf-8' );
        echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
        die();
    }

    public function apiSingleCategories(){
        if (isset($_GET['cat_id'])) {
            $cat_id = filter_input(INPUT_GET, 'cat_id', FILTER_SANITIZE_STRING);

            $cat = Category::where('id', $cat_id)->first();

            $subs = SubCategory::where('category_id', $cat->id)->first();
            $row["sub_categories"][] = $subs;

            $pros = DB::table("ratings")
            ->join('users', 'users.id', '=', 'ratings.pro_id')
            ->select('users.id as id', 'users.image', 'users.first_name', 'users.last_name')
            ->orderBy('users.id', 'desc')->get();
            
    
                        foreach ($pros as $pro) {
                            // getting the pro user details                       
    
                            $pro_projects = Project::where('pro_id', $pro->id)->count();
                            if ($pro_projects >= 1) {
                                if ($pro->image) {
                                    $profile_image = "https://ubuy.ng/uploads/images/profile_pics/".$pro->image;
                                }else{
                    
                                    $profile_image = 'https://ubuy.ng/mvp_ui/images/icons/chat_user_icon.png';
                                }
                                  // getting the pros first service
                            $pro_service = DB::table("services")
                            ->where('services.user_id', '=', $pro->id)
                            ->join('sub_categories', 'sub_categories.id', '=', 'services.sub_category_id')
                            ->select('sub_categories.name')
                            ->first();
                
                            
    
                                $row["invite_premium"][] = array(
                                    'user_id' => $pro->id,
                                    'pro_name' => $pro->first_name.' '.$pro->last_name,
                                    'project_count' => $pro_projects,
                                    'profile_image' => $profile_image,
                                    'pro_service' => $pro_service->name,
                                    'premium_pro' => 1,
                                );
                                
                            }
                
                        }
    
        }

        $set['UBUYAPI_V2'] = $row;
            
        
        header( 'Content-Type: application/json; charset=utf-8' );
        echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
        die();
    }

    public function apiAllPros(){
        $pros = DB::table("ratings")
        ->join('users', 'users.id', '=', 'ratings.pro_id')
        ->select('users.id as id', 'users.image', 'users.first_name', 'users.last_name')
        ->orderBy('users.id', 'desc')->get();
        

            foreach ($pros as $pro) {
                // getting the pro user details                       

                $pro_projects = Project::where('pro_id', $pro->id)->count();
                if ($pro_projects >= 1) {
                    if ($pro->image) {
                        $profile_image = "https://ubuy.ng/uploads/images/profile_pics/".$pro->image;
                    }else{
        
                        $profile_image = 'https://ubuy.ng/mvp_ui/images/icons/chat_user_icon.png';
                    }
                        // getting the pros first service
                $pro_service = DB::table("services")
                ->where('services.user_id', '=', $pro->id)
                ->join('sub_categories', 'sub_categories.id', '=', 'services.sub_category_id')
                ->select('sub_categories.name')
                ->first();
    
                

                    $row["premium_pros"][] = array(
                        'user_id' => $pro->id,
                        'pro_name' => $pro->first_name.' '.$pro->last_name,
                        'project_count' => $pro_projects,
                        'profile_image' => $profile_image,
                        'pro_service' => $pro_service->name,
                        'premium_pro' => 1,
                    );
                    
                }
    
            }

            $set['UBUYAPI_V2'] = $row;
    

        header( 'Content-Type: application/json; charset=utf-8' );
        echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
        die();
    }
    public function apiSearchPros(){

        $query = filter_input(INPUT_GET, 'query', FILTER_SANITIZE_STRING);

        $result = SubCategory::where('name','LIKE','%'.$query.'%')->first();


        $pros = DB::table("services")
        ->where('services.sub_category_id', '=', $result->id)
        ->join('profiles', 'profiles.user_id', 'services.user_id')
        ->select('profiles.user_id as id', 'profiles.business_name', 'profiles.profile_photo')
                    ->orderBy('profiles.id', 'desc')->get();
        

            foreach ($pros as $pro) {
                // getting the pro user details                       

                $pro_projects = Project::where('pro_id', $pro->id)->count();
                if ($pro_projects >= 1) {
                    if ($pro->image) {
                        $profile_image = "https://ubuy.ng/uploads/images/profile_pics/".$pro->image;
                    }else{
        
                        $profile_image = 'https://ubuy.ng/mvp_ui/images/icons/chat_user_icon.png';
                    }
                        // getting the pros first service
                $pro_service = DB::table("services")
                ->where('services.user_id', '=', $pro->id)
                ->join('sub_categories', 'sub_categories.id', '=', 'services.sub_category_id')
                ->select('sub_categories.name')
                ->first();
    
                

                    $row["premium_pros"][] = array(
                        'user_id' => $pro->id,
                        'pro_name' => $pro->first_name.' '.$pro->last_name,
                        'project_count' => $pro_projects,
                        'profile_image' => $profile_image,
                        'pro_service' => $pro_service->name,
                        'premium_pro' => 1,
                    );
                    
                }
    
            }

            $set['UBUYAPI_V2'] = $row;
    

        header( 'Content-Type: application/json; charset=utf-8' );
        echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
        die();
    }
/* Here we start the api for safety toolkit 
*
* This would have all callbacks for the safety toolkit which includes all reports and logs
*
*/
// get a single project toolkit
        public function SingleProjectSafety()
        {
            $project_id = filter_input(INPUT_GET, 'project_id', FILTER_SANITIZE_STRING);
            $bid_id = filter_input(INPUT_GET, 'bid_id', FILTER_SANITIZE_STRING);

            $toolkit = SafetyLog::where('project_id', $project_id)->where('bid_id', $bid_id)->first();

            if ($toolkit) {
                $set['UBUYAPI_V2'] = $toolkit;
            }else{
                $set['UBUYAPI_V2'][]=array('msg' =>'Safety toolkit is not activated in this task', 'success'=>'0');
            }

            header( 'Content-Type: application/json; charset=utf-8' );
            echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
            die();

        }

//  send an alert to the server

public function AlertProjectSafety()
{
    $project_id = filter_input(INPUT_GET, 'project_id', FILTER_SANITIZE_STRING);
    $bid_id = filter_input(INPUT_GET, 'bid_id', FILTER_SANITIZE_STRING);

    $toolkit = SafetyLog::where('project_id', $project_id)->where('bid_id', $bid_id)->first();

    /* TODO:: here we send a responds to slack */
    if ($toolkit) {
        $date = date('m/d/Y h:i:s a', time());

        $toolkit->update(['cus_alert_at' => $date]);

        $set['UBUYAPI_V2'][]=array(
            'msg' =>'An Alert has been sent to Ubuy.',
             'success'=>'0'
            );
    }else{
        $set['UBUYAPI_V2'][]=array(
            'msg' =>'Safety toolkit is not activated in this task',
             'success'=>'0'
            );
    }

    header( 'Content-Type: application/json; charset=utf-8' );
    echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
    die();

}

/* send an alert when the user calls 112 */
public function CallAlertProjectSafety()
{
    $project_id = filter_input(INPUT_GET, 'project_id', FILTER_SANITIZE_STRING);
    $bid_id = filter_input(INPUT_GET, 'bid_id', FILTER_SANITIZE_STRING);

    $toolkit = SafetyLog::where('project_id', $project_id)->where('bid_id', $bid_id)->first();

    /* TODO:: here we send a responds to slack */
    if ($toolkit) {
        $date = date('m/d/Y h:i:s a', time());

        $toolkit->update(['cus_911_at' => $date]);

        $set['UBUYAPI_V2'][]=array(
            'msg' =>'An Alert has been sent to Ubuy.',
             'success'=>'0'
            );
    }else{
        $set['UBUYAPI_V2'][]=array(
            'msg' =>'Safety toolkit is not activated in this task',
             'success'=>'0'
            );
    }

    header( 'Content-Type: application/json; charset=utf-8' );
    echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
    die();

}


/* Safety ends here */

    /* HERE WE START WORKING  ON DISPUT RESOLUTION
    *
    * THIS WOULD HANDLE ALL RESPONDS
    *
    */

    public function DisputeAddRecord(Request $request){

        $user_id = $request->user_id;
        $project_id = $request->project_id;
        $project_ref = $request->project_ref;
        $description = $request->details;
        $cat_id = $request->cat_id;

        $has_disputes = Dispute::where('disputed_by', $user_id)->where('project_id', $project_id)->where('status', 0)->first();
        
        if(!$has_disputes){

            $project = NewProject::where('id', $project_id)->first();
            $bid = NewProjectBid::where('project_id', $project->id)->where('user_id', $project->pro_id)->first();
            $bid_id = $bid->id;
            $pro_id = $project->pro_id;
            $disputed_by = $user_id;

            $data = [
                'project_ref_id' => $project_ref,
                'project_id' => $project_id,
                'cus_id' => $user_id,
                'bid_id' => $bid_id,
                'pro_id' => $pro_id,
                'description' => $description,
                'disputed_by' => $disputed_by,
                'category_id' => $cat_id,
            ];

         $dispute =   Dispute::create($data);
               

            $set['UBUYAPI_V2'][]= $dispute;
  
        }else{
             
            
            $set['UBUYAPI_V2'][]=array(
                'msg' => "You've opened a previous dispute for this task.",
                 'success'=>'0'
                );   
        }

        header( 'Content-Type: application/json; charset=utf-8' );
        echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
        die();
    
    }

    public function DisputeCatTask(){
        $user_id = filter_input(INPUT_GET, 'user_id', FILTER_SANITIZE_STRING);

        $cats = DisputeCategory::get();

        foreach($cats as $cat){
            $row['category'][] = array(
                'id' => $cat->id,
                'name' => $cat->name,
            );
        }



    
        $projects = NewProject::where('user_id', $user_id)->get();

        if($projects){
            
        foreach($projects as $project){
            $row['projects'][] = array(
                'project_id' => $project->id,
                'project_title' => $project->project_title,
                'project_ref' => $project->unique_ref_id,
            );
        }
        }else{
            $row['project'] = array(
                'msg' => 'No projects found',
                'success' => 0,
            );
        }

        $set['UBUYAPI_V2'] = $row;

        header( 'Content-Type: application/json; charset=utf-8' );
        echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
        die();
    }

    public function DisputeUnResolved(){
        $user_id = filter_input(INPUT_GET, 'user_id', FILTER_SANITIZE_STRING);

        $disputes = Dispute::where('disputed_by', $user_id)->where('status', 0)->get();
        
        if($disputes){
            foreach($disputes as $dispute){
                $date = Carbon::parse($dispute->created_at); // now date is a carbon instance
                        $project = NewProject::where('id', $dispute->project_id)->first();
                        $cat = DisputeCategory::where('id', $dispute->category_id)->first();
                $row["open_disputes"][] = array(
                    'id' =>          $dispute->id,
                    'dispute_des' => $dispute->description,
                    'dispute_cat' => $cat->name,
                    'dispute_task'=> $project->project_title,
                    'dispute_ref' => $dispute->project_ref_id,
                    'dispute_status' => $dispute->status,
                    'dispute_date'=> $date->diffForHumans(),
                   
                );
            }
        }else{
            $set['UBUYAPI_V2'][]=array(
                'msg' =>'No Disputes found',
                'success'=>'0'
            );
        }
        
        $set['UBUYAPI_V2'] = $row;
        header( 'Content-Type: application/json; charset=utf-8' );
        echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
        die();
    
    }

    
  public function saveDisputeFile (Request $request)
  {
    $project_id = $request->project_id;
    $user_id = $request->user_id;
    $dispute_id = $request->dispute_id;
    

      $thumbnail = null;
      if ($request->hasFile('file')){
          $image = $request->file('file');
          $file = $request->file('file');

          $valid_extensions = ['jpg','jpeg','png', 'gif', 'docx', 'pdf', 'txt', 'doc', 'xls', 'xlsx', 'ppt', 'pptx', 'xml', 'zip'];
          $files_extensions = ['docx', 'pdf', 'txt', 'doc', 'xls', 'xlsx', 'ppt', 'pptx', 'xml', 'zip'];
          $image_extensions = ['jpg','jpeg','png'];
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
            $main_file = strtolower(str_slug($file_base_name)).'.' . $image->getClientOriginalExtension();
  
            $thumbnailPath = '/public/uploads/project_files/'.$project_id.'/'.$main_file;
  
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
  
           $filePath = '/public/uploads/project_files/'.$project_id.'/'.$main_file;
  
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
        }
        elseif (in_array(strtolower($image->getClientOriginalExtension()), $doc_extensions)) {
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
        'project_id' => $project_id,
        'user_id' => $user_id,
        'dispute_id' => $dispute_id,
        'file' => $main_file,
    ];
   
   

   $file_data = DisputeFile::create($data);

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
  // Save dispute files


/* disputes ends here */
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

    public function CancelTask(Request $request)
    {

        $project_id = $request->project_id;
        $user_id = $request->user_id;
        $version_ = $request->version_;

        if ($version_ == 0) {
            $project = Project::find($project_id);
        } elseif($version_ == 1) {
            $project = NewProject::find($project_id);
        }
        
        $status = 4;
        $project->update(['status' => $status]);

        $set['UBUYAPI_V2'] = $project;
        header( 'Content-Type: application/json; charset=utf-8' );
        echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
        die();
    }

    public function reopenProject(){
        $project_id = filter_input(INPUT_GET, 'project_id', FILTER_SANITIZE_STRING);
        $version_ = filter_input(INPUT_GET, 'version_', FILTER_SANITIZE_STRING);

        if ($version_ == 0) {
            $project = Project::where('id', $project_id)->first();
        }elseif($version_ == 1){
            $project = Project::where('id', $project_id)->first();
        }

        $created_at = Carbon::today()->toDateTimeString();
        $status = 1;
        $project->update(['created_at' => $created_at]);
        $project->update(['status' => $status]);


        $set['UBUYAPI_V2'] = $project;
        header( 'Content-Type: application/json; charset=utf-8' );
        echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
        die();
    }

    public function CompleteTask(Request $request)
    {

        $project_id = $request->project_id;
        $user_id = $request->user_id;
        $version_ = $request->version_;

        if ($version_ == 0) {
            $project = Project::find($project_id);
        } elseif($version_ == 1) {
            $project = NewProject::find($project_id);
        }
        
        $status = 3;
        $project->update(['status' => $status]);

        $set['UBUYAPI_V2'] = $project;
        header( 'Content-Type: application/json; charset=utf-8' );
        echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
        die();
    }

    /* here we start rating the pros */

    public function RatePro(Request $request)
    {

        $project_id = $request->project_id;
        $cus_id = $request->user_id;
        $pro_id = $request->pro_id;
        $rating = $request->rating;
        $comment = $request->comment;

        if ($rating != null) {
            $project = NewProject::where('id', $project_id)->first();

            $rateData = [
                'project_id' => $project_id,
                'cus_id' => $cus_id,
                'pro_id' => $pro_id,
                'rating' => $rating,
                'comment' => $comment,
                'project_name' => $project->project_title,
                'cus_name' => $project->cus_name,
              
            ];

            $rating_checker = Rating::where('project_id', $project_id)->where('pro_id', $pro_id)->first();

            if (!$rating_checker) {
                Rating::create($rateData);

                $set['UBUYAPI_V2'][]=array(
                    'msg' =>'Rating Successful',
                    'success'=>'1');
            }
        } else{
            $set['UBUYAPI_V2'][]=array(
                'msg' =>'Please add a comment',
                'success'=>'0');
        }
        
       

       
        header( 'Content-Type: application/json; charset=utf-8' );
        echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
        die();
    }

/* HERE WE START WORKING  ON CREATING A LIST FOR PROS
    *
    * THIS WOULD HANDLE ALL RESPONDS FOR PROS LIST
    *
    */

    public function apiInvitePros(){
        $project_id = filter_input(INPUT_GET, 'project_id', FILTER_SANITIZE_STRING);
        $version_ = filter_input(INPUT_GET, 'version_', FILTER_SANITIZE_STRING);

        // check the versions and make api calls
        if ($version_ == 0) {
            
            // check if the project exists
            $project = Project::where('id', $project_id)->first();
            
        }elseif ($version_ == 1) {
            $project = NewProject::where('id', $project_id)->first();
        }


        // get the sub category the project belongs to
        
        $sub = SubCategory::where('id', $project->sub_category_id)->first();

        // maths now
        $lat = $project->lat;
        $lon = $project->lng;
        $distance = 200;

      if ($sub->payment_type == 0) {
        $pros = DB::table("services")
        ->where('services.sub_category_id', '=', $sub->id)
        ->join('profiles', function ($join) use ($lat, $lon, $distance) {
                  $join->on('profiles.user_id', '=', 'services.user_id')
                        ->whereRaw( DB::raw("3959 * acos(cos(radians(" . $lat . ")) 
        * cos(radians(profiles.lat)) 
        * cos(radians(profiles.lng) - radians(" . $lon . ")) 
        + sin(radians(" .$lat. ")) 
        * sin(radians(profiles.lat))) < $distance "));
                    })->select('profiles.user_id as id', 'profiles.business_name', 'profiles.profile_photo')
                    ->orderBy('profiles.id', 'desc')->get();

        foreach ($pros as $pro) {
            // getting the pro user details
            $user = User::where('id', $pro->id)->first();
           
            if ($user->image) {
                $profile_image = "https://ubuy.ng/uploads/images/profile_pics/".$user->image;
            }else{

                $profile_image = 'https://ubuy.ng/mvp_ui/images/icons/chat_user_icon.png';
            }
            
            $pro_projects = Project::where('pro_id', $user->id)->count();

            if ($pro_projects >= 1) {
                $row["invite_premium"][] = array(
                    'user_id' => $user->id,
                    'pro_name' => $user->first_name.' '.$user->last_name,
                    'project_count' => $pro_projects,
                    'profile_image' => $profile_image,
                    'premium_pro' => 1,
                );
            }else {
                $row["invite_pro"][] = array(
                    'user_id' => $user->id,
                    'pro_name' => $user->first_name.' '.$user->last_name,
                    'project_count' => $pro_projects,
                    'profile_image' => $profile_image,
                    'premium_pro' => 0,
                );
            }

        }
        
        
      }else {
        $pros = DB::table("services")
        ->where('services.sub_category_id', '=', $sub->id)
        ->join('profiles', 'profiles.user_id', 'services.user_id')
        ->select('profiles.user_id as id', 'profiles.business_name', 'profiles.profile_photo')
                    ->orderBy('profiles.id', 'desc')->get();

                    foreach ($pros as $pro) {
                        // getting the pro user details
                        $user = User::where('id', $pro->id)->first();
                       
                        if ($user->image) {
                            $profile_image = "https://ubuy.ng/uploads/images/profile_pics/".$user->image;
                        }else{
            
                            $profile_image = 'https://ubuy.ng/mvp_ui/images/icons/chat_user_icon.png';
                        }
                        
                        $pro_projects = Project::where('pro_id', $user->id)->count();
            
                        if ($pro_projects >= 1) {
                            $row["invite_premium"][] = array(
                                'user_id' => $user->id,
                                'pro_name' => $user->first_name.' '.$user->last_name,
                                'project_count' => $pro_projects,
                                'profile_image' => $profile_image,
                                'premium_pro' => 1,
                            );
                        }else {
                            $row["invite_pro"][] = array(
                                'user_id' => $user->id,
                                'pro_name' => $user->first_name.' '.$user->last_name,
                                'project_count' => $pro_projects,
                                'profile_image' => $profile_image,
                                'premium_pro' => 0,
                            );
                        }
            
                    }
      }
        // maths ends

        $set['UBUYAPI_V2'] = $row;
        header( 'Content-Type: application/json; charset=utf-8' );
        echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
        die();
    }

    /* PROS LIST API ENDS HERE */

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
    public function apiCategoriesState()
        {
        
            $category = Category::get();
            $state = State::get();

            $row['category'] = $category;
            $row['state'] = $state;
            $set['UBUYAPI_V2'] = $row;
            header( 'Content-Type: application/json; charset=utf-8' );
            echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
            die();
        }
    public function SubCatSkills()
        {
            $sub_id = filter_input(INPUT_GET, 'sub_id', FILTER_SANITIZE_STRING);

        
            $skills = Skill::where('subcategory_id', $sub_id)->get();
            $set['UBUYAPI_V2'] = $skills;
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

            $Category =  SubCategory::where('category_id','=', $id)->select('id', 'name','category_id', 'description', 'image')->get();


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


   public function BidProfile()
   {
    $bid_id = filter_input(INPUT_GET, 'bid_id', FILTER_SANITIZE_STRING);
    $version_ = filter_input(INPUT_GET, 'version_', FILTER_SANITIZE_STRING);

    if($version_ == 0){

        $bid = ProjectBid::where('id', $bid_id)->first();
    }elseif($version_ == 1){
        
        $bid = NewProjectBid::where('id', $bid_id)->first();
    }

        if ($bid) {
            $user = User::find($bid->user_id);
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
                    $task_done__v2 = ProjectBid::where('user_id', $user->id)
                     ->where('bid_status', '=', 2)
                     ->count();
                    $task_done__v3 = NewProjectBid::where('user_id', $user->id)
                     ->where('bid_status', '=', 2)
                     ->count();

                     $task_done = $task_done__v2 + $task_done__v3;
                     $joined_Date = $date = Carbon::parse($user->created_at); // now date is a carbon instance
                     /* here we check the verification of the pro */
                     $email_checker = $user->email_verify_code;
                     $number_checker =  $user->number_verify_code;
                     $id_checker = $user->verify_confirm;
                     
                    //  declare email status
                     if ($email_checker) {
                         $email_check = 1;
                     }else{
                         $email_check = 0;
                     }

                    //  declare number status
                     if ($number_checker) {
                         $number_check = 1;
                     }else{
                         $number_check = 0;
                     }

                    //  declare id status
                     if ($id_checker == 2) {
                         $id_check = 1;
                     }else{
                         $id_check = 0;
                     }

                    //  check if all the 
                    if($id_checker && $number_checker && $email_checker){
                        $user_verified = 1;
                    }else{
                        $user_verified = 0;
                    }

                    /* now we declare the row data for the pros profile api */
                    $row['pro_profile'][]=array(
                                
                        'pro_id' =>  $user->id,
                        'pro_image' => $profile_image,
                        'pro_name' =>  $profile->business_name,
                        'pro_city' => $profile->pro_city,
                        'task_done' => $task_done,
                        'badge_email' => $email_check,
                        'badge_number' => $number_check,
                        'badge_id' => $id_check,
                        'user_verified' => $user_verified,
                        'pro_joined' => $date->diffForHumans(),
                    ); 

                    /* now we declare the row data for the pros services api*/
                    $services = DB::table("services")
                    ->where('services.user_id', '=', $user->id)
                    ->join('sub_categories', 'sub_categories.id', '=', 'services.sub_category_id')
                    ->select('sub_categories.name', 'sub_categories.image', 'sub_categories.id as id')->get();

                    foreach ($services as $service) {
                        $row['pro_services'][] = array(
                            'service_id' => $service->id,
                            'service_name' => $service->name,
                            'service_image' => 'https://ubuy.ng/uploads/backend/'.$service->image,
                        );
                    }
                    

                    /* now we declare the row data for the pros reviews api*/
                    // $ratings = DB::table("ratings")
                    // ->where('ratings.pro_id', '=', $user->id)
                    // ->join('users', 'users.id', '=', 'ratings.cus_id')
                    // ->select('ratings.comment', 'ratings.rate_title', 'ratings.id as id', 'ratings.cus_name', 'users.image')->get();

                    // $row['pro_ratings'][] = $ratings;

                    /* now we declare the row data for the pros portfolio api*/
                    $portfolios = DB::table("pro_galleries")->where('pro_galleries.user_id', '=', $user->id)->get();

                    foreach ($portfolios as $portfolio) {
                        $comment_count = DB::table('gallery_comments')->where('feed_id', '=', $portfolio->id)->count();
                        $likes_count = DB::table('gallery_likes')->where('feed_id', '=', $portfolio->id)->count();
                        
                        $row['pro_portfolio'][] = array(
                            'portfolio_id' => $portfolio->id,
                            'portfolio_title' => $portfolio->title,
                            'portfolio_file' => 'https://ubuy.ng/uploads/images/galleries/'.$portfolio->file,
                            'portfolio_likes' => $likes_count,
                            'portfolio_comments' => $comment_count,
                        );
                    }

                    $set['UBUYAPI_V2'] = $row;
                    
                    }else {
                        $set['UBUYAPI_V2'][]=array('msg' =>'This is a customer account or this pro has not setup a business profile','success'=>'0');
        
                }
             }else {
                $set['UBUYAPI_V2'][]=array('msg' =>'This profile does not have a valid profile registered','success'=>'0');
        
             }
        }else {
            $set['UBUYAPI_V2'][]=array('msg' =>'Error getting bid please \n check your querry','success'=>'0');
    
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
