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
use App\Profile;
use App\Models\Auth\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RequestController extends Controller
{

    public function index(){
        $title = 'Available Requests - Ubuy.ng';

        $user = auth()->user();

        $user_pro = Profile::select('id', 'lat', 'lng', 'pro_city', 'pro_state', 'distance')
        ->where('user_id','=', $user->id)
        ->first();

        $lat = $user_pro->lat;
        $lon = $user_pro->lng;
        $distance = $user_pro->distance;
        $city = $user_pro->pro_city;
        
  $available_request = DB::table("projects")
            ->whereRaw( DB::raw("3959 * acos(cos(radians(" . $lat . ")) 
                * cos(radians(projects.lat)) 
                * cos(radians(projects.lng) - radians(" . $lon . ")) 
                + sin(radians(" .$lat. ")) 
                * sin(radians(projects.lat))) < $distance "))
                ->join('services', 'projects.sub_category_id', '=', 'services.sub_category_id')
            ->join('profiles', 'profiles.user_id', '=', 'services.user_id')
            ->select('projects.id', 'services.user_id', 'projects.created_at', 'projects.sub_category_name', 'projects.sub_category_id','projects.city', 'projects.state', 'projects.project_message')
            ->orderBy('projects.id', 'desc')->get()->take(20);
  
            $cats = SubCategory::orderBy('id')->get();

        return view('admin.pro.request_search', compact('available_request', 'cats', 'title', 'distance', 'city'));
    }

  
    public function singleProject($project_id)
    {

        // $id = DB::table('categories')->where('slug', $slug)->value('id');

        $id = base64_decode(str_pad(strtr($project_id, '-_', '+/'), strlen($project_id) % 4, '=', STR_PAD_RIGHT))/786;

        $project = Project::find($id);
        
        return view('admin.update_projects', compact('project'));

        
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

    public function ProjectBids($project_id)
    {
        $id = base64_decode(str_pad(strtr($project_id, '-_', '+/'), strlen($project_id) % 4, '=', STR_PAD_RIGHT))/786;

        $project = Project::find($id);

        $bidders = Quote::data(['project_id' => $id, 'bid_status' => 0])->get();

        
        return view('admin.project_bids', compact('project', 'bidders'));

        
    }

   
}
