<?php

namespace App\Http\Controllers;

use App\Country;
use App\JobApplication;
use App\State;
use App\User;
use App\Profile;
use App\ProSkill;
use App\FavoritePro;
use App\ProjectBid;
use DB;
use Flash;
use App\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Illuminate\Contracts\Auth\Authenticatable;


class ProfileController extends Controller
{

    public function singlePro($pro_id){
        $id = base64_decode(str_pad(strtr($pro_id, '-_', '+/'), strlen($pro_id) % 4, '=', STR_PAD_RIGHT))/786;
        
        $profile = Profile::find($id);
        $user = auth()->user();

        if ($user == true) {
            $bookmarked = FavoritePro::where('user_id', $user->id)
        ->where('pro_id', $profile->id)->first();
        } else{
            $auth = false;
            $bookmarked = null;
        };

       $services = Service::where('user_id', $profile->user_id)->get();
       $skills = ProSkill::where('user_id', $profile->user_id)->get();
       $p_user = User::where('id', $profile->user_id)->first();
       if ($p_user->verify_confirm == 2) {
          $verify = true;
       } else {
           $verify = null;
       };
       $data = [
        'jobCount' => ProjectBid::where('user_id', $profile->user_id)
        ->where('bid_status', '=', 2)
        ->count()
        ];
        
        $title = $profile->business_name.' Profile - Ubuy.ng';

        
        return view('profiles.pro_public_profiles', $data, compact('profile','bookmarked',  'title', 'services', 'verify', 'skills'));


    }

    public function proProfile(){
        $user = auth()->user();
        $profile = Profile::where('user_id', $user->id)->first();
        $title = 'My Profile - Ubuy.ng';

        $skills = ProSkill::where('user_id', $profile->user_id)->get();

        return view('admin.pro.profile_edit', compact('title', 'profile', 'skills'));
    }

    public function UpdateProfilePost(Request $request){
        $user = Auth::user();

        $rules = [
            'business_name'   => 'required',
            'about_profile'     => 'required',
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

            $thumbnailPath = 'uploads/images/cover_pics/'.$thumbnail;

            try{
                Storage::disk('public')->put($thumbnailPath, $resized_thumb->__toString());
            } catch (\Exception $e){
                return redirect()->back()->withInput($request->input())->with('error', $e->getMessage()) ;
            }
        }

       

        $data = [
            'business_name'  => $request->business_name,
            'number_of_empolyees'         => $request->number_of_empolyees,
            'website'       => $request->website,
            'founded_year'     => $request->founded_year,
            'about_profile'    => $request->about_profile,
            'facebook_url'  => $request->facebook_url,
            'twitter_url'      => $request->twitter_url,
            'linkedin_url'          => $request->linkedin_url,
            'instagram_url' => $request->instagram_url,
            'website'       => $request->website,
           
        ];

        if ($thumbnail){
            $userdata['cover_photo'] = $thumbnail;
        }
        Profile::where('user_id', $user->id)->update($userdata);

        Profile::where('user_id', $user->id)->update($data);

        return back()->with('success', __('app.updated'));
    }
    public function UpdateProfileDistance(Request $request){
        $user = Auth::user();      

        $data = [
            'distance'  => $request->distance,
        ];
        Profile::where('user_id', $user->id)->update($data);

        return redirect('/dashboard/pro/requests/jobs');
    }

    public function updateStatus(Request $request, $id)
    {
        $rules = [
            'active_status' => 'required'
        ];
        $this->validate($request, $rules);

        $data = [
            'active_status' => $request->active_status,
        ];
        User::where('id', $id)->update($data);
        return back()->with('success', trans('app.category_updated'));
    }


    public function show($id = 0){
        if ($id){
            $title = trans('app.profile');
            $user = User::find($id);

            $is_user_id_view = true;
            return view('admin.profile', compact('title', 'user', 'is_user_id_view'));
        }
    }

    /**
     * @param $id
     * @param null $status
     * @return \Illuminate\Http\RedirectResponse
     */
    public function statusChange($id, $status = null){
        if(config('app.is_demo')){
            return redirect()->back()->with('error', 'This feature has been disable for demo');
        }

        $user = User::find($id);
        if ($user && $status){
            if ($status == 'approve'){
                $user->active_status = 1;
                $user->save();

            }elseif($status == 'block'){
                $user->active_status = 2;
                $user->save();
            }
        }
        return back()->with('success', trans('app.status_updated'));
    }

    public function appliedJobs(){
        $title = __('app.applicant');
        $user_id = Auth::user()->id;
        $applications = JobApplication::whereUserId($user_id)->orderBy('id', 'desc')->paginate(20);

        return view('admin.applied_jobs', compact('title', 'applications'));
    }

    public function registerJobSeeker(){
        $title = __('app.register_job_seeker');
        return view('register-job-seeker', compact('title'));
    }

    public function registerJobSeekerPost(Request $request){
        $rules = [
            'name' => ['required', 'string', 'max:190'],
            'email' => ['required', 'string', 'email', 'max:190', 'unique:users'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ];

        $this->validate($request, $rules);

        $data = $request->input();
        User::create([
            'name'          => $data['name'],
            'email'         => $data['email'],
            'user_type'     => 'user',
            'password'      => Hash::make($data['password']),
            'active_status' => 1,
        ]);

        return redirect(route('login'))->with('success', __('app.registration_successful'));
    }

    public function registerCustomer(){
        $title = 'Join UBuy Today';
       
        return view('customer-register', compact('title'));
    }

    public function registerCustomerPost(Request $request){
        $rules = [
            'first_name'      => ['required', 'string', 'max:190'],
            'last_name'      => ['required', 'string', 'max:190'],
            'number'      => ['required', 'numeric', 'unique:users'],
            'email'     => ['required', 'string', 'email', 'max:190', 'unique:users'],
            'password'  => ['required', 'string', 'min:6', 'confirmed'],
            'accept_terms'     => 'required',
           
        ];
        $this->validate($request, $rules);
        
        $length = 5;
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }


        $firstnameSlug = $request->first_name;
        $lastnameSlug = $request->last_name;
        $userslug = $firstnameSlug.$lastnameSlug;
        $user_slug = str_slug($userslug, 'User', 'user_slug');

        User::create([
            'uuid'          => $randomString.$lastnameSlug,
            'first_name'    => $request->first_name,
            'last_name'     => $request->last_name,
            'user_slug'     => $user_slug,
            'email'         => $request->email,
            'accept_terms'         => $request->accept_terms,
            'user_type'     => 'customer',
            'number'         => $request->number,
            'password'      => Hash::make($request->password),
            'profile_approved' => 0,
            'licence_approved' => 0,
            'enable_text_message' => 0,
        ]);

        return redirect(route('login'))->with('success', __('app.registration_successful'));
    }


    public function registerAgent(){
        $title = __('app.agent_register');
        $countries = Country::all();
        $old_country = false;
        if (old('country')){
            $old_country = Country::find(old('country'));
        }
        return view('agent-register', compact('title', 'countries', 'old_country'));
    }

    public function registerAgentPost(Request $request){
        $rules = [
            'name'      => ['required', 'string', 'max:190'],
            'company'   => 'required',
            'email'     => ['required', 'string', 'email', 'max:190', 'unique:users'],
            'password'  => ['required', 'string', 'min:6', 'confirmed'],
            'phone'     => 'required',
            'address'   => 'required',
            'country'   => 'required',
            'state'     => 'required',
        ];
        $this->validate($request, $rules);

        $company = $request->company;
        $company_slug = unique_slug($company, 'User', 'company_slug');

        $country = Country::find($request->country);
        $state_name = null;
        if ($request->state){
            $state = State::find($request->state);
            $state_name = $state->state_name;
        }

        User::create([
            'name'          => $request->name,
            'user_slug'     => $user_slug,
            'email'         => $request->email,
            'user_type'     => 'customer',
            'password'      => Hash::make($request->password),
            'phone'         => $request->phone,
            'address'       => $request->address,
            'address_2'     => $request->address_2,
            'country_id'    => $request->country,
            'country_name'  => $country->country_name,
            'state_id'      => $request->state,
            'state_name'    => $state_name,
            'city'          => $request->city,
            'active_status' => 1,
        ]);

        return redirect(route('login'))->with('success', __('app.registration_successful'));
    }


    public function employerProfile(){
        $title = __('app.employer_profile');
        $user = Auth::user();


        $countries = Country::all();
        $old_country = false;
        if ($user->country_id){
            $old_country = Country::find($user->country_id);
        }

        return view('admin.employer-profile', compact('title', 'user', 'countries', 'old_country'));
    }

    public function employerProfilePost(Request $request){
        $user = Auth::user();

        $rules = [
            'company_size'   => 'required',
            'phone'     => 'required',
            'address'   => 'required',
            'country'   => 'required',
            'state'     => 'required',
        ];

        $this->validate($request, $rules);


        $logo = null;
        if ($request->hasFile('logo')){
            $image = $request->file('logo');

            $valid_extensions = ['jpg','jpeg','png'];
            if ( ! in_array(strtolower($image->getClientOriginalExtension()), $valid_extensions) ){
                return redirect()->back()->withInput($request->input())->with('error', 'Only .jpg, .jpeg and .png is allowed extension') ;
            }
            $file_base_name = str_replace('.'.$image->getClientOriginalExtension(), '', $image->getClientOriginalName());
            $resized_thumb = Image::make($image)->resize(256, 256)->stream();

            $logo = strtolower(time().str_random(5).'-'.str_slug($file_base_name)).'.' . $image->getClientOriginalExtension();

            $logoPath = 'uploads/images/logos/'.$logo;

            try{
                Storage::disk('public')->put($logoPath, $resized_thumb->__toString());
            } catch (\Exception $e){
                return redirect()->back()->withInput($request->input())->with('error', $e->getMessage()) ;
            }
        }

        $country = Country::find($request->country);
        $state_name = null;
        if ($request->state){
            $state = State::find($request->state);
            $state_name = $state->state_name;
        }

        $data = [
            'company_size'  => $request->company_size,
            'phone'         => $request->phone,
            'address'       => $request->address,
            'address_2'     => $request->address_2,
            'country_id'    => $request->country,
            'country_name'  => $country->country_name,
            'state_id'      => $request->state,
            'state_name'    => $state_name,
            'city'          => $request->city,
            'about_company' => $request->about_company,
            'website'       => $request->website,
        ];

        if ($logo){
            $data['logo'] = $logo;
        }

        $user->update($data);

        return back()->with('success', __('app.updated'));
    }


    public function employerApplicant(){
        $title = __('app.applicant');
        $employer_id = Auth::user()->id;
        $applications = JobApplication::whereEmployerId($employer_id)->orderBy('id', 'desc')->paginate(20);

        return view('admin.applicants', compact('title', 'applications'));
    }

    public function makeShortList($application_id){
        $applicant = JobApplication::find($application_id);
        $applicant->is_shortlisted = 1;
        $applicant->save();
        return back()->with('success', __('app.success'));
    }

    public function shortlistedApplicant(){
        $title = __('app.shortlisted');
        $employer_id = Auth::user()->id;
        $applications = JobApplication::whereEmployerId($employer_id)->whereIsShortlisted(1)->orderBy('id', 'desc')->paginate(20);

        return view('admin.applicants', compact('title', 'applications'));
    }


    public function profile(){
        $title = trans('app.profile');
        $user = Auth::user();
        return view('admin.profile', compact('title', 'user'));
    }

    public function profileEdit($id = null){
        $title = trans('app.profile_edit');
        $user = Auth::user();

        if ($id){
            $user = User::find($id);
        }

        $countries = Country::all();

        return view('admin.profile_edit', compact('title', 'user', 'countries'));
    }

    public function profileEditPost($id = null, Request $request){
        if(config('app.is_demo')){
            return redirect()->back()->with('error', 'This feature has been disable for demo');
        }

        $user = Auth::user();
        if ($id){
            $user = User::find($id);
        }
        //Validating
        $rules = [
            'email'    => 'required|email|unique:users,email,'.$user->id,
        ];
        $this->validate($request, $rules);

        $inputs = array_except($request->input(), ['_token', 'photo']);
        $user->update($inputs);

        return back()->with('success', trans('app.profile_edit_success_msg'));
    }


    public function changePassword()
    {
        $title = trans('app.change_password');
        return view('admin.change_password', compact('title'));
    }

    public function changePasswordPost(Request $request)
    {
        if(config('app.is_demo')){
            return redirect()->back()->with('error', 'This feature has been disable for demo');
        }
        $rules = [
            'old_password'  => 'required',
            'new_password'  => 'required|confirmed',
            'new_password_confirmation'  => 'required',
        ];
        $this->validate($request, $rules);

        $old_password = $request->old_password;
        $new_password = $request->new_password;
        //$new_password_confirmation = $request->new_password_confirmation;

        if(Auth::check())
        {
            $logged_user = Auth::user();

            if(Hash::check($old_password, $logged_user->password))
            {
                $logged_user->password = Hash::make($new_password);
                $logged_user->save();
                return redirect()->back()->with('success', trans('app.password_changed_msg'));
            }
            return redirect()->back()->with('error', trans('app.wrong_old_password'));
        }
    }

}
