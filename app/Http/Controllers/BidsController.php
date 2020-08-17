<?php

namespace App\Http\Controllers;

use DB;
use Flash;
use App\Choice;
use App\Profile;
use App\Project;
use App\Message;
use App\Question;
use App\Response;
use App\JobRequest;
use App\Category;
use App\SubCategory;
use App\Conversation;
use App\ResponseItem;
use App\Models\Auth\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProjectController extends Controller
{
    public function index(){

        $user = auth()->user();
        $projects = $user->projects;
        
        $data = [
            'projectCount' => $user->response_items->count(),
            'completeCount' => $user->response_complete_items->count(),
            'home_cat' => Category::orderBy('id')->get()->take(7),
            ];
        // $topics = Topic::orderBy('id', 'desc')->get();
        // $orders = Order::orderBy('id', 'desc')->get();
        // $challenges = Challenge::orderBy('id', 'desc')->get();
        // $users = User::orderBy('points', 'desc')->get();
        // $notifies = Notification::orderBy('notify_id', 'desc')->get();
        // $sliders = Slider::orderBy('id', 'desc')->get();
       

        // return view('admin.dashboard', $data);
        return view('admin.customer_projects', $data, compact('projects'));
    }
    public function singleProject($project_id)
    {

        // $id = DB::table('categories')->where('slug', $slug)->value('id');

        $id = base64_decode(str_pad(strtr($project_id, '-_', '+/'), strlen($project_id) % 4, '=', STR_PAD_RIGHT))/786;

        $project = Project::find($id);
        
        return view('admin.update_projects', compact('project'));

        
    }
    public function ProjectBids($project_id)
    {
        $id = base64_decode(str_pad(strtr($project_id, '-_', '+/'), strlen($project_id) % 4, '=', STR_PAD_RIGHT))/786;

        $project = Project::find($id);
        
        return view('admin.project_bids', compact('project'));

        
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
}
