<?php

namespace App\Http\Controllers;

use App\Topic;
use App\Challenge;
use App\Slider;
use App\Fact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class SlidersController extends Controller
{
    public function index()
    {
        $title = "Sliders";
        $sliders = Slider::orderBy('id', 'desc')->get();
        $topics = Topic::orderBy('id', 'desc')->get();
        $challenges = Challenge::orderBy('id', 'desc')->get();
        $facts = Fact::orderBy('id', 'desc')->get();

        return view('admin.sliders', compact('title', 'sliders', 'topics', 'challenges','facts'));
    }

    public function store(Request $request){
       
        $thumbnail = null;
        if ($request->hasFile('thumbnail')){
            $image = $request->file('thumbnail');

            $valid_extensions = ['jpg','jpeg','png'];
            if ( ! in_array(strtolower($image->getClientOriginalExtension()), $valid_extensions) ){
                return redirect()->back()->withInput($request->input())->with('error', 'Only .jpg, .jpeg and .png is allowed extension') ;
            }
            $file_base_name = str_replace('.'.$image->getClientOriginalExtension(), '', $image->getClientOriginalName());
            $resized_thumb = Image::make($image)->resize(false)->stream();

            $thumbnail = strtolower(time().str_random(5).'-'.str_slug($file_base_name)).'.' . $image->getClientOriginalExtension();

            $thumbnailPath = 'uploads/images/sliders/'.$thumbnail;

            try{
                Storage::disk('public')->put($thumbnailPath, $resized_thumb->__toString());
            } catch (\Exception $e){
                return redirect()->back()->withInput($request->input())->with('error', $e->getMessage()) ;
            }
        }
        $data = [
            'challenge_id' => $request->challenge_id,
            'slider_url' => $request->slider_url,
            'topic_id' => $request->topic_id,
        ];

        if ($thumbnail){
            $data['slider_image'] = $thumbnail;
        }

        Slider::create($data);
        return back()->with('success', trans('app.category_created'));
    }


    public function edit($id)
    {
        $slider_id = $id;
        $title = 'Edit Slider';
        $slider = Slider::find($id);
        $topics = Topic::orderBy('id', 'desc')->get();
        $challenges = Challenge::orderBy('id', 'desc')->get();
        $facts = Fact::orderBy('id', 'desc')->get();


        return view('admin.edit_slider', compact('title', 'slider','topics', 'challenges','facts'));
    }

    public function update(Request $request, $id)
    {
        $rules = [
            'slider_des' => 'required',
            'topic_id' => 'required',
        ];
        $this->validate($request, $rules);

        $thumbnail = null;
        if ($request->hasFile('thumbnail')){
            $image = $request->file('thumbnail');

            $valid_extensions = ['jpg','jpeg','png'];
            if ( ! in_array(strtolower($image->getClientOriginalExtension()), $valid_extensions) ){
                return redirect()->back()->withInput($request->input())->with('error', 'Only .jpg, .jpeg and .png is allowed extension') ;
            }
            $file_base_name = str_replace('.'.$image->getClientOriginalExtension(), '', $image->getClientOriginalName());
            $resized_thumb = Image::make($image)->resize(512, 512)->stream();

            $thumbnail = strtolower(time().str_random(5).'-'.str_slug($file_base_name)).'.' . $image->getClientOriginalExtension();

            $thumbnailPath = 'uploads/images/sliders/'.$thumbnail;

            try{
                Storage::disk('public')->put($thumbnailPath, $resized_thumb->__toString());
            } catch (\Exception $e){
                return redirect()->back()->withInput($request->input())->with('error', $e->getMessage()) ;
            }
        }
  
        $data = [
            'slider_des' => $request->slider_des,
            'topic_id' => $request->topic_id,
            
        ];

        if ($thumbnail){
            $data['slider_image'] = $thumbnail;
        }

        Slider::where('id', $id)->update($data);
        return back()->with('success', trans('app.category_updated'));
    }
    public function updateStatus(Request $request, $id)
    {
        $rules = [
            'slider_status' => 'required'
        ];
        $this->validate($request, $rules);

        $data = [
            'slider_status' => $request->slider_status,
        ];
        Slider::where('id', $id)->update($data);
        return back()->with('success', trans('app.category_updated'));
    }


    public function destroy(Request $request, $id)
    {
        $id = $request->data_id;

        $delete = Slider::where('id', $id)->delete();
        if ($delete){
            return back()->with('success', trans('app.category_updated'));
        }
        return ['success' => 0, 'msg' => trans('app.error_msg')];
    }



}
