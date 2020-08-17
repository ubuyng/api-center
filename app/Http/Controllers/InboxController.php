<?php

namespace App\Http\Controllers;

use DB;
use Flash;
use App\ProjectBid;
use App\Project;
use App\Response;
use App\JobRequest;
use App\Category;
use App\SubCategory;
use App\Quote;
use App\Message;
use App\Typing;
use App\ProjectFile;
use App\Rating;
use App\Models\Auth\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Auth\Authenticatable;
use Cache;

class InboxController extends Controller
{
    public function CustomerInbox(){

        $user = auth()->user();
        $projects = $user->projects;
        $home_cat = Category::orderBy('id')->get()->take(8);

        $bids = DB::table("project_bids")
        ->where('project_bids.cus_id','=', $user->id)
        ->join('projects', 'project_bids.project_id', '=', 'projects.id')
        ->join('profiles', 'project_bids.user_id', '=', 'profiles.user_id')
        ->select('projects.id', 'project_bids.id AS bid_id', 'projects.sub_category_name', 'project_bids.bid_message', 'project_bids.bid_amount', 'profiles.business_name', 'profiles.profile_photo')
       ->get();
        return view('admin.customer_inbox', compact('projects', 'home_cat', 'bids'));
    }


    public function ProInbox(){
        $auth = auth()->user();
        $auth_id = $auth->id;
        $bid_msg = ProjectBid::where('user_id',$auth_id)->get(); 

        $bids = DB::table("project_bids")
        ->where('project_bids.user_id','=', $auth_id)
        ->join('projects', 'project_bids.project_id', '=', 'projects.id')
        ->join('users', 'project_bids.cus_id', '=', 'users.id')
        ->select('projects.id', 'project_bids.id AS bid_id', 'projects.sub_category_name', 'project_bids.bid_message', 'project_bids.bid_amount', 'users.first_name', 'users.last_name')
       ->get();

       
        return view('admin.pro.pro_inbox', compact('bids', 'last_message'));
    }
    public function proProjectChat($project_id)
    {
        $user = auth()->user();
        $title = 'Project Chat - UbuyNg';
        // $id = DB::table('categories')->where('slug', $slug)->value('id');

        $id = base64_decode(str_pad(strtr($project_id, '-_', '+/'), strlen($project_id) % 4, '=', STR_PAD_RIGHT))/786;

        $project = Project::find($id);

        $details = DB::table("responses")
        ->where('responses.user_id','=', $project->user_id)
        ->where('responses.sub_category_id','=', $project->sub_category_id)
        ->join('questions', 'responses.question_id', '=', 'questions.id')
        ->join('choices', 'responses.choice_id', '=', 'choices.id')
        ->select('questions.text AS ques_text', 'choices.text AS choice_text')
       ->get();

        $customer  = DB::table("users")
        ->where('users.id','=', $project->user_id)
       ->get()->first();

        $bid  = DB::table("project_bids")
        ->where('project_bids.project_id','=', $project->id)
        ->where('project_bids.user_id','=', $user->id)
       ->get()->first();

        
        return view('admin.pro_project_chat', compact('project', 'title', 'details', 'customer', 'bid'));

        
    }
    public function cusProjectChat($bid_id)
    {

        // $id = DB::table('categories')->where('slug', $slug)->value('id');
        
        $id = base64_decode(str_pad(strtr($bid_id, '-_', '+/'), strlen($bid_id) % 4, '=', STR_PAD_RIGHT))/786;
        
        $bid = ProjectBid::find($id);
        $project = Project::find($bid->project_id);

        $details = DB::table("responses")
        ->where('responses.user_id','=', $project->user_id)
        ->where('responses.sub_category_id','=', $project->sub_category_id)
        ->join('questions', 'responses.question_id', '=', 'questions.id')
        ->join('choices', 'responses.choice_id', '=', 'choices.id')
        ->select('questions.text AS ques_text', 'choices.text AS choice_text')
       ->get();

        $pro  = DB::table("profiles")
        ->where('profiles.user_id','=', $bid->user_id)
        ->join('users', 'users.id', '=', 'profiles.user_id')
        ->select('profiles.id AS id', 'profiles.user_id', 'profiles.business_name', 'profiles.profile_photo', 'users.number', 'users.email', 'profiles.pro_city')
       ->get()->first();
        $pro_rating  = DB::table("ratings")
        ->where('ratings.pro_id','=', $bid->user_id)
        ->where('ratings.cus_id','=', $bid->cus_id)
        ->where('ratings.project_id','=', $bid->project_id)
       ->get()->first();



    //     $bid  = DB::table("project_bids")
    //     ->where('project_bids.project_id','=', $project->id)
    //    ->get()->first();

        
        return view('admin.cus_project_chat', compact('project', 'details', 'pro', 'bid', 'pro_rating'));

        
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

    // pro send bids

    public function sendBid(Request $request)
    {

        $rules = [
            'project_id' => 'required',
            'user_id' => 'required',
            'cus_id' => 'required',
            'bid_message' => 'required',
            'bid_amount' => 'required',
        ];
        $this->validate($request, $rules);

        $data = [
            'project_id' => $request->project_id,
            'user_id' => $request->user_id,
            'cus_id' => $request->cus_id,
            'bid_message' => $request->bid_message,
            'bid_amount' => $request->bid_amount,
        ];
        ProjectBid::create($data);
        return back()->with('success');
    }

    // customers manage bids
    public function ProjectBids($project_id)
    {
        $id = base64_decode(str_pad(strtr($project_id, '-_', '+/'), strlen($project_id) % 4, '=', STR_PAD_RIGHT))/786;

        $project = Project::find($id);

        $bidders = Quote::data(['project_id' => $id, 'bid_status' => 0])->get();

        
        return view('admin.project_bids', compact('project', 'bidders'));

        
    }

    /**
     *  CHAT MANAGER STARTS BELOW 
     * PLEASE BE VERY CAREFUL
     * 
     */
   

       public function cusFirstResponse(Request $request){
        $chat = new Message;
        $chat->message = $request->message;
        $chat->sender_id = $request->sender;
        $chat->receiver_id = $request->receiver;
        $chat->bid_id = $request->bid_id;
        $chat->project_id = $request->project_id;
        $chat->save();
        ProjectBid::where('id',$chat->bid_id)
              ->update(['bid_status' => 1]);
              $bid_url = base64_encode($chat->bid_id*786);
        return redirect('/dashboard/inbox/project/bid/'.$bid_url);
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
    function callmessage($id){
        $bid = DB::table('project_bids')->where('id',$id)->first();
        $user = DB::table('users')->where('id',$bid->cus_id)->first();
        $user->first_name;
        $auth_user = auth()->user();
        $profile = DB::table('profiles')->where('user_id',$auth_user->id)->first();
        $auth_id=$auth_user->id;
        $chats = Message::where('bid_id',$bid->id)
                     ->where('sender_id',$auth_id)
                     ->where('receiver_id',$user->id)
                     ->Orwhere('sender_id',$user->id)
                     ->where('receiver_id',$auth_id)
                     ->get();
                     
                   
                         
                     
                        foreach($chats as $chat){
                            if($chat->sender_id != $auth_id){
                echo '<div class="message-bubble me">
                <div class="message-bubble-inner">
                    <div class="message-avatar"><img src="https://placehold.it/50/55C1E7/fff&text='. mb_substr($user->first_name , 0, 1) .' " alt="User Avatar" class="img-circle"  /></div>
                    <div class="message-text"><p>'. $chat->message .'</p></div>
                    </div>
                    <div class="clearfix"></div>
                </div>
                
                <div class="message-time-sign">
                    <span>' . $chat->created_at->diffForHumans() .'</span>
                </div>';
                            }else{
                
                                echo '<div class="message-bubble">
                                <div class="message-bubble-inner">
                                    <div class="message-avatar"><img src="/uploads/images/profile_pics/'.$profile->profile_photo.' " alt="User Avatar" class="img-circle"  /></div>
                                    <div class="message-text"><p>'. $chat->message .'</p></div>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                                
                                <div class="message-time-sign">
                                    <span>' . $chat->created_at->diffForHumans() .'</span>
                                </div>';
                
                            }
                        };
                     
       
    }
    // 
    function callCusmessage($id){
        $bid = DB::table('project_bids')->where('id',$id)->first();
        $user = DB::table('users')->where('id',$bid->user_id)->first();
        $profile = DB::table('profiles')->where('user_id',$bid->user_id)->first();
       $user->first_name;
       $auth_user = auth()->user();
        $auth_id=$auth_user->id;
        $chats = Message::where('bid_id',$id)
                     ->where('sender_id',$user->id)
                     ->where('receiver_id',$auth_id)
                     ->Orwhere('sender_id',$auth_id)
                     ->where('receiver_id',$user->id)
                     ->get();
        foreach($chats as $chat){
            if($chat->sender_id != $auth_id){
echo '<div class="message-bubble ">
<div class="message-bubble-inner">
    <div class="message-avatar"><img  src="/uploads/images/profile_pics/'.$profile->profile_photo.' " alt="User Avatar" class="img-circle"  /></div>
    <div class="message-text"><p>'. $chat->message .'</p></div>
    </div>
    <div class="clearfix"></div>
</div>

<div class="message-time-sign">
    <span>' . $chat->created_at->diffForHumans() .'</span>
</div>';
            }else{

                echo '<div class="message-bubble me">
                <div class="message-bubble-inner">
                    <div class="message-avatar"><img src="https://placehold.it/50/55C1E7/fff&text='. mb_substr($auth_user->last_name , 0, 1) .' " alt="User Avatar" class="img-circle"  /></div>
                    <div class="message-text"><p>'. $chat->message .'</p></div>
                    </div>
                    <div class="clearfix"></div>
                </div>
                
                <div class="message-time-sign">
                    <span>' . $chat->created_at->diffForHumans() .'</span>
                </div>';

            }
        };
    }

    // not implemented yet

    public function deletemessage($id){
        DB::table('messages')->where('id',$id)
                          ->delete();
      
    }
   public function typinc_receve($id){

    $auth_user = auth()->user();

        $typing_receve= DB::table('typings')->where('receiver_id',$auth_user->id)
                            ->where('sender_id',$id)
                            ->first();
           if(isset( $typing_receve)){
               return  $typing_receve->check_status;
           }            

   }

   public function typing(Request $request){
    $auth_user = auth()->user();
    $auth_id=$auth_user->id;
    echo $id= $request->receiver;
   echo $text= $request->message;
    Message::where('receiver_id',$auth_id)
            ->where('is_pro_seen',1)
            ->where('sender_id',$id)
            ->update(['is_pro_seen' => 0]);
    Message::where('receiver_id',$auth_id)
            ->where('is_cus_seen',1)
            ->where('sender_id',$id)
            ->update(['is_cus_seen' => 0]);
    $typing_check = DB::table('typings')->where('receiver_id',$id)
                              ->where('sender_id',$auth_id)
                              ->first();
    if($typing_check){
        DB::table('typings')->where('receiver_id',$id)
            ->where('sender_id',$auth_id)
            ->update(['check_status' => $request->message]);
    }else{
        $typing = new typing;
        $typing->receiver_id = $id;
        $typing->sender_id = $auth_id;
       
        $typing->save();
    }
}

/**
 * 
 * FILE UPLOAD SECTION
 */

public function proChatFiles($project_id)
{
    $title ='Project Files';
    $id = base64_decode(str_pad(strtr($project_id, '-_', '+/'), strlen($project_id) % 4, '=', STR_PAD_RIGHT))/786;

    $project = Project::find($id);
   
    $bid =  ProjectBid::where('project_id', $id)->first();
    $files =  ProjectFile::where('project_id', $id)->get();

    $pro  = DB::table("profiles")
    ->where('profiles.user_id','=', $bid->user_id)->first();

    $customer  = DB::table("users")
    ->where('users.id','=', $project->user_id)
   ->get()->first();

   $user = auth()->user();

    
    return view('admin.pro.pro_project_files', compact('project','title', 'files', 'pro', 'customer', 'bid', 'user'));
}
public function userOnlineStatus()
{
    $users = DB::table('users')->get();

    foreach ($users as $user) {
        if (\Cache::has('user-is-online-' . $user->id))
            echo "User " . $user->first_name . " is online.";
        else
            echo "User " . $user->first_name . " is offline.";
    }
}
// ajax files
public function proAjaxFiles($project_id)
{
    $id = $project_id;

    $files =  ProjectFile::where('project_id', $id)->orderBy('id', 'DESC')->get();
    
    return view('admin.ajax.ajax_files', compact('files'));
}


  // Save pro project files

  public function saveProjectFile (Request $request)
  {
    $project_id = $request->project_id;

      $thumbnail = null;
      if ($request->hasFile('files')){
          $image = $request->file('files');

          $valid_extensions = ['jpg','jpeg','png', 'gif', 'docx', 'pdf', 'txt', 'doc', 'xls', 'xlsx', 'ppt', 'pptx', 'xml', 'zip'];
          $image_extensions = ['jpg','jpeg','png', 'gif'];
          $doc_extensions = ['docx','doc'];
          $pdf_extensions = ['pdf'];
          $excel_extensions = ['xls', 'xlsx'];
          $ppt_extensions = ['ppt', 'pptx',];
          $zip_extensions = ['zip',];
          $other_extensions = ['xml', 'txt'];
          if ( ! in_array(strtolower($image->getClientOriginalExtension()), $valid_extensions) ){
              return redirect()->back()->withInput($request->input())->with('error', 'Only .jpg, .jpeg and .png is allowed extension') ;
          }
          $file_base_name = str_replace('.'.$image->getClientOriginalExtension(), '', $image->getClientOriginalName());
          if (in_array(strtolower($image->getClientOriginalExtension()), $image_extensions)) {
               $resized_thumb = Image::make($image)->resize(512, 512)->stream();
          }else {
            $resized_thumb = '512';
          }

        //   echo   $resized_thumb;
          $thumbnail = strtolower(str_slug($file_base_name)).'.' . $image->getClientOriginalExtension();

          $thumbnailPath = '/project_files/'.$project_id.'/'.$thumbnail;

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
  // Save pro files


}
