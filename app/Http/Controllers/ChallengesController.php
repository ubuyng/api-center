<?php

namespace App\Http\Controllers;

use App\Challenge;
use App\Quiz;
use App\Topic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class ChallengesController extends Controller
{
    public function index()
    {
        $title = "Challenges";
        $challenges = Challenge::orderBy('id', 'desc')->get();
        $topics = Topic::orderBy('id', 'desc')->get();

        return view('admin.challenges', compact('title', 'challenges','topics'));
    }

    public function store(Request $request){
        $rules = [
            'challenge_name' => 'required',
            'challenge_points' => 'required',
            'challenge_des' => 'required',
            'topic_id' => 'required',
            'challenge_level' => 'required',
            'challenge_timer' => 'required',
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

            $thumbnailPath = 'uploads/images/challenges/'.$thumbnail;

            try{
                Storage::disk('public')->put($thumbnailPath, $resized_thumb->__toString());
            } catch (\Exception $e){
                return redirect()->back()->withInput($request->input())->with('error', $e->getMessage()) ;
            }
        }

        $slug = str_slug($request->challenge_name);
        $duplicate = Challenge::where('challenge_slug', $slug)->count();
        if ($duplicate > 0){
            return back()->with('error', trans('app.category_exists_in_db'));
        }

        $data = [
            'challenge_name' => $request->challenge_name,
            'challenge_points' => $request->challenge_points,
            'challenge_des' => $request->challenge_des,
            'challenge_timer' => $request->challenge_timer,
            'challenge_level' => $request->challenge_level,
            'topic_id' => $request->topic_id,
            'challenge_slug' => $slug,
        ];

        if ($thumbnail){
            $data['challenge_image'] = $thumbnail;
        }

        Challenge::create($data);
        return back()->with('success', trans('app.category_created'));
    }


    public function edit($id)
    {
        $title = 'Edit Challenge';
        $challenge = Challenge::find($id);

        return view('admin.edit_challenge', compact('title', 'challenge'));
    }
    public function quiz($id)
    {
        $title = 'Challenge Quiz';
        $challenge = Challenge::find($id);
        $quizzes = Quiz::orderBy('id', 'desc')->get();


        return view('admin.challenges_quiz', compact('title', 'challenge', 'quizzes'));
    }

    public function update(Request $request, $id)
    {
        $rules = [
            'challenge_name' => 'required',
            'challenge_points' => 'required',
            'challenge_des' => 'required',
            'challenge_timer' => 'required',
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

            $thumbnailPath = 'uploads/images/challenges/'.$thumbnail;

            try{
                Storage::disk('public')->put($thumbnailPath, $resized_thumb->__toString());
            } catch (\Exception $e){
                return redirect()->back()->withInput($request->input())->with('error', $e->getMessage()) ;
            }
        }

        $slug = str_slug($request->challenge_name);

        $duplicate = Challenge::where('challenge_slug', $slug)->where('id', '!=', $id)->count();
        if ($duplicate > 0){
            return back()->with('error', trans('app.category_exists_in_db'));
        }

        $data = [
            'challenge_name' => $request->challenge_name,
            'challenge_points' => $request->challenge_points,
            'challenge_des' => $request->challenge_des,
            'challenge_timer' => $request->challenge_timer,
            'challenge_slug' => $slug,
        ];

        if ($thumbnail){
            $data['challenge_image'] = $thumbnail;
        }

        Challenge::where('id', $id)->update($data);
        return back()->with('success', trans('app.category_updated'));
    }
    public function updateStatus(Request $request, $id)
    {
        $rules = [
            'challenge_status' => 'required'
        ];
        $this->validate($request, $rules);

        $data = [
            'challenge_status' => $request->challenge_status,
        ];
        Challenge::where('id', $id)->update($data);
        return back()->with('success', trans('app.category_updated'));
    }


    public function destroy(Request $request, $id)
    {
        $id = $request->data_id;

        $delete = Challenge::where('id', $id)->delete();
        if ($delete){
            return back()->with('success', trans('app.category_updated'));
        }
        return ['success' => 0, 'msg' => trans('app.error_msg')];
    }



}
