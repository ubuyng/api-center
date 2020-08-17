<?php

namespace App\Http\Controllers;

use App\Notification;
use App\Challenge;
use App\Product;
use App\Topic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class NotifyController extends Controller
{
    public function index()
    {
        $title = "Notifications";
        $notifications = Notification::orderBy('notify_id', 'desc')->get();
        $products = Product::orderBy('id', 'desc')->get();
        $challenges = Challenge::orderBy('id', 'desc')->get();
        $topics = Topic::orderBy('id', 'desc')->get();

        return view('admin.notifications', compact('title', 'notifications', 'products', 'challenges', 'topics'));
    }

    public function store(Request $request){
        $rules = [
            'notify_des' => 'required',
        ];
        $this->validate($request, $rules);

        $data = [
            'user_id' => $request->user_id,
            'notify_des' => $request->notify_des,
            'notify_url' => $request->notify_url,
            'notify_type' => $request->notify_type,
            'notify_general' => $request->notify_general,
            'notify_points' => $request->notify_points,
            'product_id' => $request->product_id,
            'challenge_id' => $request->challenge_id,
            'order_id' => $request->order_id,
        ];


        Notification::create($data);
        return back()->with('success');
    }


    public function edit($id)
    {
        $title = 'Edit Notification';
        $product = Notification::find($id);

        return view('admin.edit_product', compact('title', 'product'));
    }

    public function updateStatus(Request $request, $id)
    {
        $rules = [
            'notify_status' => 'required'
        ];
        $this->validate($request, $rules);

        $data = [
            'notify_status' => $request->notify_status,
        ];
        Notification::where('notify_id', $id)->update($data);
        return back()->with('success', trans('app.category_updated'));
    }


    public function destroy(Request $request, $id)
    {
        $id = $request->data_id;

        $delete = Notification::where('id', $id)->delete();
        if ($delete){
            return back()->with('success', trans('app.category_updated'));
        }
        return ['success' => 0, 'msg' => trans('app.error_msg')];
    }



}
