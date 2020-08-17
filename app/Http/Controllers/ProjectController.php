<?php

namespace App\Http\Controllers;

use DB;
use Flash;
use App\Project;
use App\ProjectBid;
use App\Response;
use App\JobRequest;
use App\Category;
use App\SubCategory;
use App\Quote;
use App\Models\Auth\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Mail\ProjectOffers;

class ProjectController extends Controller
{
    public function index(){

        $title = 'My projects - Ubuy.ng';
        $user = auth()->user();
        $projects = $user->projects;
        $bidders = DB::table("project_bids")
        ->join('profiles', 'project_bids.user_id', '=', 'profiles.user_id')
        ->select('profiles.profile_photo', 'profiles.id AS pro_id', 'project_bids.project_id')
        ->get();

        $data = [
             'home_cat' => Category::orderBy('id')->get()->take(7),
            ];
   
        return view('admin.customer_projects', $data, compact('projects', 'bidders', 'title'));
    }

    public function testProjectMail(){
        $id = 31;
        $project = Project::find($id);
        $details = DB::table("responses")
        ->where('responses.user_id','=', $project->user_id)
        ->where('responses.project_id','=', $project->id)
        ->where('responses.sub_category_id','=', $project->sub_category_id)
        ->join('questions', 'responses.question_id', '=', 'questions.id')
        ->join('choices', 'responses.choice_id', '=', 'choices.id')
        ->select('questions.text AS ques_text', 'choices.text AS choice_text')
       ->get();
        $user = auth()->user();
        return view('emails.pros.project_email', compact('user', 'project', 'details'));
    }
    public function testMultipleEmails(){
        $id = 31;
        $project = Project::find($id);
        
        $users =  DB::table('profiles')
        ->where('profiles.pro_state','=', $project->state)
        ->join('services', 'profiles.user_id', '=', 'services.user_id')
        ->join('users', 'profiles.user_id', '=', 'users.id')
        ->where('services.sub_category_id', '=', 602)
        ->select('users.email', 'users.id', 'users.first_name')
       ->get();
       $details = DB::table("responses")
       ->where('responses.user_id','=', $project->user_id)
       ->where('responses.project_id','=', $project->id)
       ->where('responses.sub_category_id','=', $project->sub_category_id)
       ->join('questions', 'responses.question_id', '=', 'questions.id')
       ->join('choices', 'responses.choice_id', '=', 'choices.id')
       ->select('questions.text AS ques_text', 'choices.text AS choice_text')
      ->get();
       
    foreach ($users as $user) {
        if (\Cache::has('user-is-online-' . $user->id)){
            // \Mail::send(new ProjectOffers($user, $details, $project));
            // echo "User " . $user->email . " is online.";
            $notify = true;
        }

    else{
        \Mail::send(new ProjectOffers($user, $details, $project));
        echo "User " . $user->first_name . " is offline.";
    }

    }

  
    }

    public function singleProject($project_id)
    {

        // $id = DB::table('categories')->where('slug', $slug)->value('id');

        $id = base64_decode(str_pad(strtr($project_id, '-_', '+/'), strlen($project_id) % 4, '=', STR_PAD_RIGHT))/786;

        $project = Project::find($id);

        $title = $project->sub_category_name.' Project - Ubuy.ng';

        
        return view('admin.update_projects', compact('project', 'title'));

        
    }

    public function singleProjectUpdate(Request $request, $project_id)
    {

        $id = base64_decode(str_pad(strtr($project_id, '-_', '+/'), strlen($project_id) % 4, '=', STR_PAD_RIGHT))/786;

        $rules = [
            'status' => 'required'
        ];
        $this->validate($request, $rules);

        $data = [
            'status' => $request->status,
        ];
        Project::where('id', $id)->update($data);
        return redirect(route('dash_projects'))->with('success');
    }
    public function acceptOffer(Request $request)
    {

        $project_id = $request->project_id;
        $bid_id = $request->bid_id;
     
        $projectData = [
            'status' => 2,
            'pro_id' => $request->user_id,
            'pro_name' => $request->bus_name,
        ];
        $bidData = [
            'project_name' =>  $request->project_name,
            'cus_name' =>  $request->cus_name,
            'bid_status' =>  2,
        ];
        Project::where('id', $project_id)->update($projectData);
        ProjectBid::where('id', $bid_id)->update($bidData);
        return redirect(route('dash_projects'))->with('success');
    }

    public function ProjectBids($project_id)
    {
        $id = base64_decode(str_pad(strtr($project_id, '-_', '+/'), strlen($project_id) % 4, '=', STR_PAD_RIGHT))/786;

        $project = Project::find($id);

        $title = $project->sub_category_name.' Bids - Ubuy.ng';

        $bidders = DB::table("project_bids")
        ->where('project_bids.project_id','=', $project->id)
        ->join('profiles', 'project_bids.user_id', '=', 'profiles.user_id')
        ->join('projects', 'projects.id', '=', 'project_bids.project_id')
        ->select('project_bids.id AS bid_id', 'profiles.id AS pro_id', 
        'profiles.user_id AS pro_user_id', 'projects.id AS proj_id',
         'project_bids.bid_amount', 'project_bids.bid_message', 
         'profiles.profile_photo', 'profiles.business_name', 
         'project_bids.bid_status')
       ->get();
     
                $users =  DB::table('profiles')
                ->where('profiles.pro_state','=', $project->state)
                ->join('services', 'profiles.user_id', '=', 'services.user_id')
                ->join('users', 'profiles.user_id', '=', 'users.id')
                ->where('services.sub_category_id', '=', 602)
                ->select('users.email', 'users.id', 'users.first_name')
                ->get();
                $details = DB::table("responses")
                ->where('responses.user_id','=', $project->user_id)
                ->where('responses.project_id','=', $project->id)
                ->where('responses.sub_category_id','=', $project->sub_category_id)
                ->join('questions', 'responses.question_id', '=', 'questions.id')
                ->join('choices', 'responses.choice_id', '=', 'choices.id')
                ->select('questions.text AS ques_text', 'choices.text AS choice_text')
                ->get();
                
            foreach ($users as $user) {
                if (\Cache::has('user-is-online-' . $user->id)){
                    // \Mail::send(new ProjectOffers($user, $details, $project));
                    // echo "User " . $user->email . " is online.";
                    $notify = true;
                }

            else{
                \Mail::send(new ProjectOffers($user, $details, $project));
                // echo "User " . $user->first_name . " is offline.";
            }

            }

        
        return view('admin.project_bids', compact('project', 'bidders', 'title'));

        
    }

   
}
