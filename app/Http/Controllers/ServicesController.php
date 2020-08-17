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

class ServicesController extends Controller
{
    public function index(){
        $title = 'My Services - Ubuy.ng';

        $user = auth()->user();
        $services = $user->services;
     
            return view('admin.pros_services', compact('services', 'title'));
    }
    public function AddServices(){
        $user = auth()->user();
        $services = $user->services;
        $subcats = SubCategory::orderBy('id')->get();
     
            return view('admin.add_services', compact('services', 'subcats'));
    }

    
    public function store(Request $request){
        $user = auth()->user();

        $rules = [
            'sub_category_id' => 'required',
            'service_name' => 'required',
        ];
        $this->validate($request, $rules);

        $data = [
            'user_id' => $user->id,
            'sub_category_id' => $request->sub_category_id,
            'service_name' => $request->service_name,
        ];

        Service::create($data);
        return redirect(route('pro_services'))->with('success');
    }


    public function singleService($service_id)
    {

        // $id = DB::table('categories')->where('slug', $slug)->value('id');

        $id = base64_decode(str_pad(strtr($project_id, '-_', '+/'), strlen($project_id) % 4, '=', STR_PAD_RIGHT))/786;

        $service = Service::find($id);
        
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

    public function destroy(Request $request)
    {
        $id = $request->service_id;

        $delete = Service::where('id', $id)->delete();
        if ($delete){
            return back()->with('success', trans('app.category_updated'));
        }
        return ['success' => 0, 'msg' => trans('app.error_msg')];
    }
   
}
