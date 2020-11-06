<?php

namespace App\Http\Controllers;

use App\Country;
use App\JobApplication;
use App\State;
use App\User;
use App\Profile;
use App\ProSkill;
use App\Favoritepro;
use App\ProCredential;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Illuminate\Contracts\Auth\Authenticatable;
use App\Mail\ProConfirmEmail;
use App\Mail\ProWelcomeEmail;
use App\Mail\CusWelcomeEmail;
use DB;
use App\Mail\CusConfirmEmail;
use App\Mail\PassChangeEmail;
use GuzzleHttp\Client;




class UserController extends Controller
{


    public function authLogin(){

        // $users_all = User::orderBy('id', 'asc');
        $users_all = User::select('id', 'first_name', 'last_name', 'password', 'email', 'number', 'number_verify_code', 'email_verify_code', 'user_role');

        if (isset($_GET['email'])) {
            $email = filter_input(INPUT_GET, 'email', FILTER_SANITIZE_STRING);
            $password = filter_input(INPUT_GET, 'password', FILTER_SANITIZE_STRING);
            $title = $email;
            
            $userauth = $users_all->where('email', '=', $email)->first();


            if (!$userauth) {
                $set['UBUYAPI_V1'][]=array('msg' =>'Account not found','success'=>'0');
            }else if($userauth){
                $hashedPassword = $userauth->password;


                if (Hash::check($password, $hashedPassword)) {
                    $user = $userauth->where('email', '=', $email)->select('id', 'first_name', 'last_name', 'email', 'image','number', 'number_verify_code', 'email_verify_code', 'user_role')->first();

                    if ($user->image) {
                        $user_image = 'https://ubuy.ng/uploads/images/profile_pics/'.$user->image;
                    } else {
                        $user_image = null;
                    }

                    $set['UBUYAPI_V1'][]=array(
                        'user_id' => $user->id,
                        'first_name' => $user->first_name,
                    'last_name' => $user->last_name,
                    'email' => $user->email,
                    'number' => $user->number,
                    'number_verify_code' => $user->number_verify_code,
                    'email_verify_code' => $user->email_verify_code,
                    'image' => $user_image,
                    'user_role' => $user->user_role,
                    'success' => '1');
                    
                }else{
                    $set['UBUYAPI_V1'][]=array('msg' =>'wrong password','success'=>'0');
                }
            }
            
            
            
            
        }
        header( 'Content-Type: application/json; charset=utf-8' );
        echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
        die();

      
    }
    public function profileDetails(){

        if (isset($_GET['user_id'])) {
            $user_id = filter_input(INPUT_GET, 'user_id', FILTER_SANITIZE_STRING);
            
            $userauth = User::where('id', '=', $user_id)->first();


            if (!$userauth) {
                $set['UBUYAPI_V1'][]=array('msg' =>'Account not found','success'=>'0');
            }else if($userauth){
                $user = User::where('id', '=', $user_id)->select('id', 'first_name', 'last_name', 'email', 'image','number', 'number_verify_code', 'email_verify_code', 'user_role')->first();

                if ($user->image) {
                    $user_image = 'https://ubuy.ng/uploads/images/profile_pics/'.$user->image;
                } else {
                    $user_image = null;
                }

                $set['UBUYAPI_V2'][]=array(
                    'user_id' => $user->id,
                    'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'image' => $user_image,
                'email' => $user->email,
                'number' => $user->number,
               );
                
            }
            
            
            
            
        }
        header( 'Content-Type: application/json; charset=utf-8' );
        echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
        die();

      
    }

    public function ProauthLogin(Request $request){


        $req_email = $request->email;
        $req_password = $request->password;
        if ($req_email) {
     
            
            $userauth = User::where('email', '=', $req_email)->select('email', 'password')->first();


            if (!$userauth) {
                $set['UBUYAPI_V1'][]=array('msg' =>'Account not found','success'=>'0');
            }else if($userauth){
                $hashedPassword = $userauth->password;


                if (Hash::check($password, $hashedPassword)) {
                    $user = $userauth->where('email', '=', $email)->select('id', 'first_name', 'last_name', 'email', 'number', 'number_verify_code', 'email_verify_code', 'user_role')->first();

                    $set['UBUYAPI_V1'][]=array(
                        'user_id' => $user->id,
                        'first_name' => $user->first_name,
                    'last_name' => $user->last_name,
                    'email' => $user->email,
                    'number' => $user->number,
                    'number_verify_code' => $user->number_verify_code,
                    'email_verify_code' => $user->email_verify_code,
                    'user_role' => $user->user_role,
                    'success' => '1');
                    
                }else{
                    $set['UBUYAPI_V1'][]=array('msg' =>'wrong password','success'=>'0');
                }
            }
            
            
            
            
        }
        header( 'Content-Type: application/json; charset=utf-8' );
        echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
        die();

      
    }
    // forget pass
    public function forgetpass(){

        // $users_all = User::orderBy('id', 'asc');

        if (isset($_GET['email'])) {
            $email = filter_input(INPUT_GET, 'email', FILTER_SANITIZE_STRING);
            
            $userauth = User::where('email', '=', $email)->first();


            if (!$userauth) {
                $set['UBUYAPI_V1'][]=array('msg' =>'Account not found, Please register','success'=>'0');
            }else if($userauth){

                \Mail::send(new PassChangeEmail($userauth));

                    $set['UBUYAPI_V1'][]=array(
                        'user_id' => $userauth->id,
                        'email' =>$userauth->email,
                        'msg' =>'Magic link sent to your email',
                         'success' => '1');
           }
          
        }
        header( 'Content-Type: application/json; charset=utf-8' );
        echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
        die();

      
    }
    // otp
    public function otpSms(){

        // $users_all = User::orderBy('id', 'asc');

        if (isset($_GET['number'])) {
            $number = filter_input(INPUT_GET, 'number', FILTER_SANITIZE_STRING);
            
            $userauth = User::where('number', '=', $number)->first();


            if (!$userauth) {
                $set['UBUYAPI_V1'][]=array('msg' =>'Account not found, Please register','success'=>'0');
            }else if($userauth){

                 // send user a verification 4 digit otp code
                        $client = new Client();
                        $res = $client->request('POST', 'https://www.bulksmsnigeria.com/api/v1/sms/create?api_token=ont6NgrDetRWY2Z2DTBZo8ieV78GKwvH0oakUmZbXsbC0DEMIQgf92ShmDdS&from=Ubuy.ng&to='.$userauth->number.'&body='.$userauth->user_token.' is your Ubuy Nigeria token login to https://ubuy.ng/verify-my-number?token='.$userauth->user_token.' to verify your number &dnd=2', [
                            'form_params' => [
                                'client_id' => 'test_id',
                                'secret' => 'test_secret',
                            ]
                                ]);

                    $set['UBUYAPI_V1'][]=array(
                        'user_id' => $userauth->id,
                        'number' =>$userauth->number,
                        'token' =>$userauth->user_token,
                        'msg' =>'OTP Sent',
                         'success' => '1');
           }
          
        }
        header( 'Content-Type: application/json; charset=utf-8' );
        echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
        die();

      
    }
    // otp checker
    public function otpChecker(){

        // $users_all = User::orderBy('id', 'asc');

        if (isset($_GET['number'])) {
            $number = filter_input(INPUT_GET, 'number', FILTER_SANITIZE_STRING);
            
            $userauth = User::where('number', '=', $number)->first();


            if (!$userauth) {
                $set['UBUYAPI_V1'][]=array('msg' =>'Account not found, Please register','success'=>'0');
            }else if($userauth){

                 if ($userauth->user_token) {
                    $set['UBUYAPI_V1'][]=array('msg' =>'Number Verified','success'=>'1');
                }
                 else {
                    $set['UBUYAPI_V1'][]=array('msg' =>'Number Not Verified','success'=>'0');
                }
           }
          
        }
        header( 'Content-Type: application/json; charset=utf-8' );
        echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
        die();

      
    }

// getting users profile

public function apiProfile()
    {

        if (isset($_GET['id'])) {
            $user_id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_STRING);

            $user = Auth::loginUsingId($user_id);

            if (!$user) {
                $set['UBUYAPI_V1'][]=array('msg' =>'Account not found','success'=>'0');
            }else if($user){
            // $projects = $user->projectsSubCat->get();

            $projects = DB::table("projects")
            ->join('sub_categories', 'projects.sub_category_id', '=', 'sub_categories.id')
            ->where('projects.user_id', '=', $user_id)
            ->select('projects.id', 'projects.user_id', 'projects.created_at', 'sub_categories.image', 'projects.sub_category_name', 'projects.sub_category_id','projects.address',  'projects.project_message')
            ->orderBy('projects.id', 'desc')->get();
            
            

            $allProjects =  $projects->count();
            $set['UBUYAPI_V1'][]=array(
                'user_id' => $user->id,
                'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'email' => $user->email,
            'number' => $user->number,
            'number_verify_code' => $user->number_verify_code,
            'email_verify_code' => $user->email_verify_code,
            'user_role' => $user->user_role,
            'user_project' => $allProjects,
            'success' => '1');
            // $set['UBUYAPI_V1'] = $projects;
             }

            header( 'Content-Type: application/json; charset=utf-8' );
            echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
            die();
        }
    }



    public function authRegister(){

        // $users_all = User::orderBy('id', 'asc');
        $users_all = User::select('id', 'first_name', 'last_name', 'password', 'email', 'number', 'number_verify_code', 'email_verify_code', 'user_role');
      

        if (isset($_GET['email'])) {
            $first_name = filter_input(INPUT_GET, 'first_name', FILTER_SANITIZE_STRING);
            $last_name = filter_input(INPUT_GET, 'last_name', FILTER_SANITIZE_STRING);
            $phone = filter_input(INPUT_GET, 'phone', FILTER_SANITIZE_STRING);
            $email = filter_input(INPUT_GET, 'email', FILTER_SANITIZE_STRING);
            $password = filter_input(INPUT_GET, 'password', FILTER_SANITIZE_STRING);
            
            $userauth = $users_all->where('email', '=', $email)->orWhere('number', '=', $phone)->first();

            if($userauth){     
                // echo "used";
           
                $set['UBUYAPI_V1'][]=array('msg' =>'This email or number is registered already','success'=>'0');
            }
            else if (!$userauth) {


                $length = 5;
                $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                $charactersLength = strlen($characters);
                $randomString = '';
                for ($i = 0; $i < $length; $i++) {
                    $randomString .= $characters[rand(0, $charactersLength - 1)];
                }
        
        
                $firstnameSlug = $first_name;
                $lastnameSlug = $last_name;
                $userslug = $firstnameSlug.$lastnameSlug;
                $user_slug = str_slug($userslug, 'User', 'user_slug');
        
                $cou_code = $phone;
        
                if (strpos($cou_code, '+2340') !== false) {
                    $phone_no = preg_replace('/^\+?2340|\|2340|\D/', '', ($cou_code)); 
                     $phone_no = .0.$phone_no;
                }elseif (strpos($cou_code, '2340') !== false) {
                    $phone_no = preg_replace('/^\+?2340|\|2340|\D/', '', ($cou_code)); 
                     $phone_no = .0.$phone_no;
                }elseif (strpos($cou_code, '+234') !== false) {
                    $phone_no = preg_replace('/^\+?234|\|234|\D/', '', ($cou_code)); 
                     $phone_no = .0.$phone_no;
                }elseif (strpos($cou_code, '234') !== false) {
                    $phone_no = preg_replace('/^\+?234|\|234|\D/', '', ($cou_code)); 
                     $phone_no = .0.$phone_no;
                }else {
                    $phone_no = $cou_code;
                }
        
                $fourdigitrandom = rand(1000,9999); 

               $user  = User::create([
                    'uuid'          => $randomString.$lastnameSlug,
                    'first_name'    => $first_name,
                    'last_name'     => $last_name,
                    'user_slug'     => $user_slug,
                    'email'         => $email,
                    'accept_terms'  => 1,
                    'user_role'     => 'customer',
                    'number'        => $phone_no,
                    'password'      => Hash::make($password),
                    'profile_approved' => 0,
                    'licence_approved' => 0,
                    'enable_text_message' => 0,
                    'user_token' => $fourdigitrandom,

                ]);
        
                 Auth::login($user);
                $user = auth()->user();
        
                // \Mail::send(new CusConfirmEmail);
                // \Mail::send(new CusWelcomeEmail);
        
                $set['UBUYAPI_V1'][]=array(
                    'user_id' => $user->id,
                    'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'email' => $user->email,
                'number' => $user->number,
                'number_verify_code' => $user->number_verify_code,
                'email_verify_code' => $user->email_verify_code,
                'user_token' => $user->user_token,
                'user_role' => $user->user_role,
                'msg' => 'Welcome to ubuy, Please verifiy your contacts',
                'success' => '1'
            );

                // $set['UBUYAPI_V1'][]=array('msg' =>'Lets do business','success'=>'0');

            } 
            
        } else{
            $set['UBUYAPI_V1'][]=array('msg' =>'An Error as occoured, please check your details and try again','success'=>'0');

        }
        header( 'Content-Type: application/json; charset=utf-8' );
        echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
        die();

      
    }

    public function proAuthRegister(){

        // $users_all = User::orderBy('id', 'asc');
        $users_all = User::select('id', 'first_name', 'last_name', 'password', 'email', 'number', 'number_verify_code', 'email_verify_code', 'user_role');
      

        if (isset($_GET['email'])) {
            $first_name = filter_input(INPUT_GET, 'first_name', FILTER_SANITIZE_STRING);
            $last_name = filter_input(INPUT_GET, 'last_name', FILTER_SANITIZE_STRING);
            $phone = filter_input(INPUT_GET, 'phone', FILTER_SANITIZE_STRING);
            $email = filter_input(INPUT_GET, 'email', FILTER_SANITIZE_STRING);
            $password = filter_input(INPUT_GET, 'password', FILTER_SANITIZE_STRING);
            
            $userauth = $users_all->where('email', '=', $email)->orWhere('number', '=', $phone)->first();

            if($userauth){     
                // echo "used";
           
                $set['UBUYAPI_V1'][]=array('msg' =>'This email or number is registered already','success'=>'0');
            }
            else if (!$userauth) {


                $length = 5;
                $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                $charactersLength = strlen($characters);
                $randomString = '';
                for ($i = 0; $i < $length; $i++) {
                    $randomString .= $characters[rand(0, $charactersLength - 1)];
                }
        
        
                $firstnameSlug = $first_name;
                $lastnameSlug = $last_name;
                $userslug = $firstnameSlug.$lastnameSlug;
                $user_slug = str_slug($userslug, 'User', 'user_slug');
        
                $cou_code = $phone;
        
                if (strpos($cou_code, '+2340') !== false) {
                    $phone_no = preg_replace('/^\+?2340|\|2340|\D/', '', ($cou_code)); 
                     $phone_no = .0.$phone_no;
                }elseif (strpos($cou_code, '2340') !== false) {
                    $phone_no = preg_replace('/^\+?2340|\|2340|\D/', '', ($cou_code)); 
                     $phone_no = .0.$phone_no;
                }elseif (strpos($cou_code, '+234') !== false) {
                    $phone_no = preg_replace('/^\+?234|\|234|\D/', '', ($cou_code)); 
                     $phone_no = .0.$phone_no;
                }elseif (strpos($cou_code, '234') !== false) {
                    $phone_no = preg_replace('/^\+?234|\|234|\D/', '', ($cou_code)); 
                     $phone_no = .0.$phone_no;
                }else {
                    $phone_no = $cou_code;
                }
                
                $fourdigitrandom = rand(1000,9999); 
               $user  = User::create([
                    'uuid'          => $randomString.$lastnameSlug,
                    'first_name'    => $first_name,
                    'last_name'     => $last_name,
                    'user_slug'     => $user_slug,
                    'email'         => $email,
                    'accept_terms'  => 1,
                    'user_role'     => 'pro',
                    'number'        => $phone_no,
                    'password'      => Hash::make($password),
                    'profile_approved' => 0,
                    'licence_approved' => 0,
                    'enable_text_message' => 0,
                    'user_token' => $fourdigitrandom,
                ]);
        

                


                 Auth::login($user);
                $user = auth()->user();
        

                  // send user a verification 4 digit otp code
                  $client = new Client();
                  $res = $client->request('POST', 'https://www.bulksmsnigeria.com/api/v1/sms/create?api_token=ont6NgrDetRWY2Z2DTBZo8ieV78GKwvH0oakUmZbXsbC0DEMIQgf92ShmDdS&from=Ubuy.ng&to='.$user->number.'&body='.$fourdigitrandom.' is your Ubuy Nigeria token login to https://ubuy.ng/verify-my-number?token='.$fourdigitrandom.' to verify your number &dnd=2', [
                      'form_params' => [
                          'client_id' => 'test_id',
                          'secret' => 'test_secret',
                      ]
                          ]);

                // \Mail::send(new CusConfirmEmail);
                // \Mail::send(new CusWelcomeEmail);
        
                $set['UBUYAPI_V1'][]=array(
                    'user_id' => $user->id,
                    'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'email' => $user->email,
                'number' => $user->number,
                'number_verify_code' => $user->number_verify_code,
                'email_verify_code' => $user->email_verify_code,
                'user_token' => $user->user_token,
                'user_role' => $user->user_role,
                'msg' => 'Welcome to ubuy, Please verifiy your contacts',
                'success' => '1'
            );

                // $set['UBUYAPI_V1'][]=array('msg' =>'Lets do business','success'=>'0');

            } 
            
        } else{
            $set['UBUYAPI_V1'][]=array('msg' =>'An Error as occoured, please check your details and try again','success'=>'0');

        }
        header( 'Content-Type: application/json; charset=utf-8' );
        echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
        die();

      
    }
    public function authRegister1(){  

        if (isset($_GET['user_id'])) {
            $user_id = filter_input(INPUT_GET, 'user_id', FILTER_SANITIZE_STRING);
            $business_name = filter_input(INPUT_GET, 'business_name', FILTER_SANITIZE_STRING);
            $business_des = filter_input(INPUT_GET, 'business_des', FILTER_SANITIZE_STRING);
            
            $userauth = User::where('id', '=', $user_id)->first();

            $user_profile = Profile::where('user_id', $userauth->id)->select('id', 'business_name', 'user_id')->first();


            if($user_profile){     
               $set['UBUYAPI_V1'][]=array('msg' =>'Sorry you already have a business account called:'.$user_profile->business_name,'success'=>'0');
            }
            else if (!$user_profile) {

        
                $business_name_data = $business_name;
                $business_des_data = $business_des;
            
               $profile_data  = Profile::create([
                    'user_id'          => $userauth->id,
                    'business_name'    => $business_name_data,
                    'about_profile'     => $business_des_data,
                ]);
        
                 Auth::login($userauth);
                $user = auth()->user();
        
                $set['UBUYAPI_V1'][]=array(
                'user_id' => $user->id,
                'msg' => 'Business profile created',
                'success' => '1'
            );

            } 
            
        } else{
            $set['UBUYAPI_V1'][]=array('msg' =>'An Error as occoured, please check your details and try again','success'=>'0');
            
        }
        header( 'Content-Type: application/json; charset=utf-8' );
        echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
        die();

      
    }
    public function authRegister2(){    

        if (isset($_GET['user_id'])) {
            $user_id = filter_input(INPUT_GET, 'user_id', FILTER_SANITIZE_STRING);
            $profile_state = filter_input(INPUT_GET, 'state', FILTER_SANITIZE_STRING);
            $profile_address = filter_input(INPUT_GET, 'address', FILTER_SANITIZE_STRING);
            $profile_lat = filter_input(INPUT_GET, 'lat', FILTER_SANITIZE_STRING);
            $profile_lng = filter_input(INPUT_GET, 'lng', FILTER_SANITIZE_STRING);
            
            $userauth = User::where('id', '=', $user_id)->first();

            $user_profile = Profile::where('user_id', $userauth->id)->select('id', 'business_name', 'user_id')->first();


            if($user_profile){   

                 $profile_address_data = $profile_address;
                $profile_state_data = $profile_state;
                $profile_lat_data = $profile_lat;
                $profile_lng_data = $profile_lng;
            

                $data = [
                    'pro_address' => $profile_address_data,
                    'pro_state' => $profile_state_data,
                    'lat' => $profile_lat_data,
                    'lng' => $profile_lng_data,
                ];   
        
                Profile::where('user_id', $user_id)->update($data);

                 Auth::login($userauth);
                $user = auth()->user();
        
                $set['UBUYAPI_V1'][]=array(
                'user_id' => $user->id,
                'msg' => 'Address Saved',
                'success' => '1'
            );
            }
            else if (!$user_profile) {
                $set['UBUYAPI_V1'][]=array('msg' =>"Sorry we can't find your business account",'success'=>'0');

            } 
            
        } else{
            $set['UBUYAPI_V1'][]=array('msg' =>'An Error as occoured, please check your details and try again','success'=>'0');

        }
        header( 'Content-Type: application/json; charset=utf-8' );
        echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
        die();

      
    }


         /* 
   ** ======== API VERSION 2 SECTION STARTS HERE =======
   **
   */

  public function authLogin2(){

    // $users_all = User::orderBy('id', 'asc');
    $users_all = User::select('id', 'first_name', 'last_name', 'password', 'email', 'number', 'number_verify_code', 'email_verify_code', 'user_role');

    if (isset($_GET['email'])) {
        $email = filter_input(INPUT_GET, 'email', FILTER_SANITIZE_STRING);
        $password = filter_input(INPUT_GET, 'password', FILTER_SANITIZE_STRING);
        $title = $email;
        
        $userauth = $users_all->where('email', '=', $email)->first();


        if (!$userauth) {
            $set['UBUYAPI_V2'][]=array('msg' =>'Account not found','success'=>'0');
        }else if($userauth){
            $hashedPassword = $userauth->password;


            if (Hash::check($password, $hashedPassword)) {
                $user = $userauth->where('email', '=', $email)->select('id', 'first_name', 'last_name', 'image', 'email', 'number', 'number_verify_code', 'email_verify_code', 'user_role')->first();

                if ($user->image) {
                    $user_image = 'https://ubuy.ng/uploads/images/profile_pics/'.$user->image;
                } else {
                    $user_image = null;
                }
                
                $set['UBUYAPI_V2'][]=array(
                    'user_id' => $user->id,
                    'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'email' => $user->email,
                'number' => $user->number,
                'number' => $user->number,
                'user_image' => $user_image,
                'number_verify_code' => $user->number_verify_code,
                'email_verify_code' => $user->email_verify_code,
                'user_role' => $user->user_role,
                'success' => '1');
                
            }else{
                $set['UBUYAPI_V2'][]=array('msg' =>'wrong password','success'=>'0');
            }
        }
        
        
        
        
    }
    header( 'Content-Type: application/json; charset=utf-8' );
    echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
    die();

  
}
// forget pass
public function forgetpass2(){

    // $users_all = User::orderBy('id', 'asc');
    $users_all = User::select('email');

    if (isset($_GET['email'])) {
        $email = filter_input(INPUT_GET, 'email', FILTER_SANITIZE_STRING);
        
        $userauth = $users_all->where('email', '=', $email)->first();


        if (!$userauth) {
            $set['UBUYAPI_V1'][]=array('msg' =>'Account not found, Please register','success'=>'0');
        }else if($userauth){

            \Mail::send(new PassChangeEmail($userauth));

                $set['UBUYAPI_V1'][]=array(
                    'user_id' => $userauth->id,
                     'success' => '1');
       }
      
    }
    header( 'Content-Type: application/json; charset=utf-8' );
    echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
    die();

  
}

// getting users profile

public function apiProfile2()
{

    if (isset($_GET['id'])) {
        $user_id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_STRING);

        $user = Auth::loginUsingId($user_id);

        if (!$user) {
            $set['UBUYAPI_V1'][]=array('msg' =>'Account not found','success'=>'0');
        }else if($user){
        // $projects = $user->projectsSubCat->get();

        $projects = DB::table("projects")
        ->join('sub_categories', 'projects.sub_category_id', '=', 'sub_categories.id')
        ->where('projects.user_id', '=', $user_id)
        ->select('projects.id', 'projects.user_id', 'projects.created_at', 'sub_categories.image', 'projects.sub_category_name', 'projects.sub_category_id','projects.address',  'projects.project_message')
        ->orderBy('projects.id', 'desc')->get();
        
        

        $allProjects =  $projects->count();
        $set['UBUYAPI_V1'][]=array(
            'user_id' => $user->id,
            'first_name' => $user->first_name,
        'last_name' => $user->last_name,
        'email' => $user->email,
        'number' => $user->number,
        'number_verify_code' => $user->number_verify_code,
        'email_verify_code' => $user->email_verify_code,
        'user_role' => $user->user_role,
        'user_project' => $allProjects,
        'success' => '1');
        // $set['UBUYAPI_V1'] = $projects;
         }

        header( 'Content-Type: application/json; charset=utf-8' );
        echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
        die();
    }
}

         /* 
   ** ======== API VERSION 3 SECTION STARTS HERE =======
   **
   */

  public function authLogin3(Request $request){

    $email = $request->email;
    $password = $request->password;
    $title = $email;
    
    $userauth = User::where('email', '=', $email)->first();


    if (!$userauth) {
        $set['UBUYAPI_V2'][]=array('msg' =>'Account not found','success'=>'0');
    }else if($userauth){
        $hashedPassword = $userauth->password;


        if (Hash::check($password, $hashedPassword)) {
            $user = $userauth->where('email', '=', $email)->select('id', 'first_name', 'last_name', 'image', 'email', 'number', 'number_verify_code', 'email_verify_code', 'user_role')->first();

            if ($user->image) {
                $user_image = 'https://ubuy.ng/uploads/images/profile_pics/'.$user->image;
            } else {
                $user_image = null;
            }
            
            $set['UBUYAPI_V2'][]=array(
                'user_id' => $user->id,
                'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'email' => $user->email,
            'number' => $user->number,
            'number' => $user->number,
            'user_image' => $user_image,
            'number_verify_code' => $user->number_verify_code,
            'email_verify_code' => $user->email_verify_code,
            'user_role' => $user->user_role,
            'success' => '1');
            
        }else{
            $set['UBUYAPI_V2'][]=array('msg' =>'wrong password','success'=>'0');
        }
    }
    
    header( 'Content-Type: application/json; charset=utf-8' );
    echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
    die();

  
}
// forget pass
public function forgetpass3(Request $request){

    $email = $request->email;
        
    $userauth = User::where('email', '=', $email)->first();


    if (!$userauth) {
        $set['UBUYAPI_V2'][]=array('msg' =>'Account not found, Please register','success'=>'0');
    }else if($userauth){

        \Mail::send(new PassChangeEmail($userauth));

            $set['UBUYAPI_V2'][]=array(
                'user_id' => $userauth->id,
                'msg' =>'We sent a magic link to '.$email.'. Click on the link to continue',
                 'success' => '1');
   }
    header( 'Content-Type: application/json; charset=utf-8' );
    echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
    die();
  
}

// getting users profile

public function apiProfile3()
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
        
        
        if ($user->image) {
            $profile_image = "https://ubuy.ng/uploads/images/profile_pics/".$user->image;
        }else{

            $profile_image = 'https://ubuy.ng/mvp_ui/images/icons/chat_user_icon.png';
        }


        $allProjects =  $projects->count();
        $set['UBUYAPI_V2'][]=array(
            'user_id' => $user->id,
            'first_name' => $user->first_name,
        'last_name' => $user->last_name,
        'email' => $user->email,
        'number' => $user->number,
        'number_verify_code' => $user->number_verify_code,
        'email_verify_code' => $user->email_verify_code,
        'user_role' => $user->user_role,
        'profile_image' => $profile_image,
        'user_project' => $allProjects,
        'success' => '1');
        // $set['UBUYAPI_V1'] = $projects;
         }

        header( 'Content-Type: application/json; charset=utf-8' );
        echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
        die();
    }
}



public function authRegister3(Request $request){

   
    if ($request->email) {
        $first_name = $request->first_name;
        $last_name = $request->last_name;
        $phone = $request->phone;
        $email = $request->email;
        $password = $request->password;
        
        $userauth = User::where('email', '=', $email)->orWhere('number', '=', $phone)->first();

        if($userauth){     
            // echo "used";
       
            $set['UBUYAPI_V2'][]=array('msg' =>'This email or number is registered already','success'=>'0');
        }
        else if (!$userauth) {


            $length = 5;
            $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $charactersLength = strlen($characters);
            $randomString = '';
            for ($i = 0; $i < $length; $i++) {
                $randomString .= $characters[rand(0, $charactersLength - 1)];
            }
    
    
            $firstnameSlug = $first_name;
            $lastnameSlug = $last_name;
            $userslug = $firstnameSlug.$lastnameSlug;
            $user_slug = str_slug($userslug, 'User', 'user_slug');
    
            $cou_code = $phone;
    
            if (strpos($cou_code, '+2340') !== false) {
                $phone_no = preg_replace('/^\+?2340|\|2340|\D/', '', ($cou_code)); 
                 $phone_no = .0.$phone_no;
            }elseif (strpos($cou_code, '2340') !== false) {
                $phone_no = preg_replace('/^\+?2340|\|2340|\D/', '', ($cou_code)); 
                 $phone_no = .0.$phone_no;
            }elseif (strpos($cou_code, '+234') !== false) {
                $phone_no = preg_replace('/^\+?234|\|234|\D/', '', ($cou_code)); 
                 $phone_no = .0.$phone_no;
            }elseif (strpos($cou_code, '234') !== false) {
                $phone_no = preg_replace('/^\+?234|\|234|\D/', '', ($cou_code)); 
                 $phone_no = .0.$phone_no;
            }else {
                $phone_no = $cou_code;
            }
    
            $fourdigitrandom = rand(1000,9999); 

           $user  = User::create([
                'uuid'          => $randomString.$lastnameSlug,
                'first_name'    => $first_name,
                'last_name'     => $last_name,
                'user_slug'     => $user_slug,
                'email'         => $email,
                'accept_terms'  => 1,
                'user_role'     => 'customer',
                'number'        => $phone_no,
                'password'      => Hash::make($password),
                'profile_approved' => 0,
                'licence_approved' => 0,
                'enable_text_message' => 0,
                'user_token' => $fourdigitrandom,

            ]);
    
             Auth::login($user);
            $user = auth()->user();
    
            // \Mail::send(new CusConfirmEmail);
            // \Mail::send(new CusWelcomeEmail);
    
            $set['UBUYAPI_V2'][]=array(
                'user_id' => $user->id,
                'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'email' => $user->email,
            'number' => $user->number,
            'number_verify_code' => $user->number_verify_code,
            'email_verify_code' => $user->email_verify_code,
            'user_token' => $user->user_token,
            'user_role' => $user->user_role,
            'msg' => 'Welcome to ubuy, Please verifiy your contacts',
            'success' => '1'
        );

            // $set['UBUYAPI_V2'][]=array('msg' =>'Lets do business','success'=>'0');

        } 
        
    } else{
        $set['UBUYAPI_V2'][]=array('msg' =>'An Error as occoured, please check your details and try again','success'=>'0');

    }
    header( 'Content-Type: application/json; charset=utf-8' );
    echo $val= str_replace('\\/', '/', json_encode($set,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
    die();

  
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



    /*
    *PROS REGISTRATION STARTS HERE
    *
    */
    
    public function registerPro(){
        $title = 'Render Your Services';
        
        return view('auth.pro.register', compact('title'));
    }

    public function registerProPost(Request $request){
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
        $pass_for_auth = $request->password;
        $email = $request->email;
        $user_slug = str_slug($userslug, 'User', 'user_slug');

        $cou_code = $request->number;

        if (strpos($cou_code, '+2340') !== false) {
            $phone_no = preg_replace('/^\+?2340|\|2340|\D/', '', ($cou_code)); 
             $phone_no = .0.$phone_no;
        }elseif (strpos($cou_code, '2340') !== false) {
            $phone_no = preg_replace('/^\+?2340|\|2340|\D/', '', ($cou_code)); 
             $phone_no = .0.$phone_no;
        }
        elseif (strpos($cou_code, '+234') !== false) {
            $phone_no = preg_replace('/^\+?234|\|234|\D/', '', ($cou_code)); 
             $phone_no = .0.$phone_no;
        }elseif (strpos($cou_code, '234') !== false) {
            $phone_no = preg_replace('/^\+?234|\|234|\D/', '', ($cou_code)); 
             $phone_no = .0.$phone_no;
        }else {
            $phone_no = $cou_code;
        }



     $user = User::create([
            'uuid'          => $randomString.$lastnameSlug,
            'first_name'    => $request->first_name,
            'last_name'     => $request->last_name,
            'user_slug'     => $user_slug,
            'email'         => $request->email,
            'accept_terms'         => 1,
            'user_role'     => 'pro',
            'number'         => $phone_no,
            'password'      => Hash::make($request->password),
            'profile_approved' => 0,
            'licence_approved' => 0,
            'enable_text_message' => 0,
        ]);

        Auth::login($user);
        
        \Mail::send(new ProConfirmEmail);
        \Mail::send(new ProWelcomeEmail);
        
        return redirect(route('boarding_starts'))->with('pro_w_c', 'send confirmation and welcome emails');
    }

        /*
    *PROS ONBOARDING TO CREATE PROFILE STARTS HERE 
    *V1 TESTING WITH AJAX
    */

    public function proBoradingMain(){
        $title = 'Setup a Ubuy Profesional Profile';
        $states = State::orderBy('id')->get();
        $user = auth()->user();
        
        $data = [
            'skills' => ProSkill::where('user_id',$user->id)->get(),
        ];
        
        $user_e_verified = $user->email_verify_code;
        if ($user_e_verified == null) {
            \Mail::send(new ProConfirmEmail);
        }

        return view('auth.pro.boarding_main', $data, compact('title', 'states', 'user'));
    }
    

    
    public function onBoardingstep1(Request $request){
        $rules = [
            'business_name' => 'required',
            'number_of_empolyees' => 'required',
            'about_profile' => 'required',
        ];
        $this->validate($request, $rules);
      
        
        $data = [
            'user_id' => $request->user_id,
            'business_name' => $request->business_name,
            'number_of_empolyees' => $request->number_of_empolyees,
            'founded_year' => $request->founded_year,
            'website' => $request->website,
            'website_verify' => '0',
            'about_profile' => $request->about_profile,
        ];     

        Profile::create($data);
        return redirect('/dashboard/onboarding/pro/welcome?index4');
    }
    public function onBoardingstepLocate(Request $request){
        $rules = [
            'address' => 'required',
            'state' => 'required',
            'city' => 'required',
            'latitude' => 'required',
            // 'longitude ' => 'required',
        ];
        $this->validate($request, $rules);
      
        
        $data = [
            'user_id' => $request->user_id,
            'pro_address' => $request->address,
            'pro_state' => $request->state,
            'pro_city' => $request->city,
            'lat' => $request->latitude,
            'lng' => $request->longitude,
        ];     

        $user_id = $request->user_id;
        Profile::where('user_id', $user_id)->update($data);
        return redirect('/dashboard/onboarding/pro/welcome?index6');
    }
    public function ProVerifyNumber(Request $request){
        $rules = [
            'number_verify_code' => 'required',
        ];
        $this->validate($request, $rules);
      
        
        $data = [
            'number_verify_code' => $request->number_verify_code,
        ];     

        $user_id = $request->user_id;
        User::where('id', $user_id)->update($data);
        return back()->with('Number Verified', 'Number Verified');
    }
    public function saveSkill(Request $request){
        $rules = [
            'skill_title' => 'required',
            'skill_type' => 'required',
        ];
        $this->validate($request, $rules);
      
        
        $data = [
            'user_id' => $request->user_id,
            'skill_title' => $request->skill_title,
            'skill_type' => $request->skill_type,
        ];     

        ProSkill::create($data);
        return back()->with('Skill Saved', 'Skill saved');
    }

    public function skill_destroy(Request $request)
    {
        $user_id = $request->user_id;
        $skill_title = $request->skill_title;

        $delete = ProSkill::where('user_id', $user_id)->where('skill_title', $skill_title)->delete();
        if ($delete){
            return back()->with('Skill Removed', 'Skill deleted');
        }
        return ['success' => 0, 'msg' => trans('app.error_msg')];
    }
    public function saveLang(Request $request){
        $rules = [
            'skill_title' => 'required',
            'skill_type' => 'required',
        ];
        $this->validate($request, $rules);
      
        
        $data = [
            'user_id' => $request->user_id,
            'skill_title' => $request->skill_title,
            'skill_type' => $request->skill_type,
        ];     

        ProSkill::create($data);
        return back()->with('language Saved', 'language saved');
    }

    public function lang_destroy(Request $request)
    {
        $user_id = $request->user_id;
        $skill_title = $request->skill_title;

        $delete = ProSkill::where('user_id', $user_id)->where('skill_title', $skill_title)->delete();
        if ($delete){
            return back()->with('Language Removed', 'Skill deleted');
        }
        return ['success' => 0, 'msg' => trans('app.error_msg')];
    }

    // bookmark pro
    public function bookmarkPro(Request $request)
    {
        
        $data = [
            'user_id' => $request->user_id,
            'pro_id' => $request->pro_id,
        ];     

        Favoritepro::create($data);
        return back()->with('Pro added to bookmark', 'Pro added to bookmark');
    }

    // Save user profile picture

    public function saveProfilePic (Request $request)
    {
       
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

            $thumbnailPath = '/images/profile_pics/'.$thumbnail;

            try{
                Storage::disk('public')->put($thumbnailPath, $resized_thumb->__toString());
            } catch (\Exception $e){
                echo $e->getMessage();

                // return redirect()->back()->withInput($request->input())->with('error', $e->getMessage()) ;
            }
        }

        if ($thumbnail){
            $data['profile_photo'] = $thumbnail;
            $userdata['image'] = $thumbnail;
        }
        $user_id = $request->user_id;

        Profile::where('user_id', $user_id)->update($data);
        User::where('id', $user_id)->update($userdata);

        echo 'successful upload';
    }
    // Save user verification picture

    // sending of confirmation and welcome emails
    public function ProConfirmMail(Request $request){
       
        \Mail::send(new ProConfirmEmail($request));
        
            return back();

    }
    public function ProWelcomeMail(Request $request){
       
        \Mail::send(new ProWelcomeEmail($request));
        
            return back();

    }
    // pro confirmation and welcome email ends here
    

    public function registerCustomer(){
        $title = 'Join UBuy Today';
       
        return view('auth.customer-register', compact('title'));
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

        $cou_code = $request->number;

        if (strpos($cou_code, '+2340') !== false) {
            $phone_no = preg_replace('/^\+?2340|\|2340|\D/', '', ($cou_code)); 
             $phone_no = .0.$phone_no;
        }elseif (strpos($cou_code, '2340') !== false) {
            $phone_no = preg_replace('/^\+?2340|\|2340|\D/', '', ($cou_code)); 
             $phone_no = .0.$phone_no;
        }elseif (strpos($cou_code, '+234') !== false) {
            $phone_no = preg_replace('/^\+?234|\|234|\D/', '', ($cou_code)); 
             $phone_no = .0.$phone_no;
        }elseif (strpos($cou_code, '234') !== false) {
            $phone_no = preg_replace('/^\+?234|\|234|\D/', '', ($cou_code)); 
             $phone_no = .0.$phone_no;
        }else {
            $phone_no = $cou_code;
        }

       $user  = User::create([
            'uuid'          => $randomString.$lastnameSlug,
            'first_name'    => $request->first_name,
            'last_name'     => $request->last_name,
            'user_slug'     => $user_slug,
            'email'         => $request->email,
            'accept_terms'         => $request->accept_terms,
            'user_role'     => 'customer',
            'number'         => $phone_no,
            'password'      => Hash::make($request->password),
            'profile_approved' => 0,
            'licence_approved' => 0,
            'enable_text_message' => 0,
        ]);

        Auth::login($user);
        // $user = new User();

        \Mail::send(new CusConfirmEmail);
        \Mail::send(new CusWelcomeEmail);

        return redirect(route('cus_verify'))->with('pro_w_c', 'send confirmation and welcome emails');
    }
    public function FacebookRegisterCustomerPost(Request $request){
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

        $cou_code = $request->number;

        if (strpos($cou_code, '+2340') !== false) {
            $phone_no = preg_replace('/^\+?2340|\|2340|\D/', '', ($cou_code)); 
             $phone_no = .0.$phone_no;
        }elseif (strpos($cou_code, '2340') !== false) {
            $phone_no = preg_replace('/^\+?2340|\|2340|\D/', '', ($cou_code)); 
             $phone_no = .0.$phone_no;
        }elseif (strpos($cou_code, '+234') !== false) {
            $phone_no = preg_replace('/^\+?234|\|234|\D/', '', ($cou_code)); 
             $phone_no = .0.$phone_no;
        }elseif (strpos($cou_code, '234') !== false) {
            $phone_no = preg_replace('/^\+?234|\|234|\D/', '', ($cou_code)); 
             $phone_no = .0.$phone_no;
        }else {
            $phone_no = $cou_code;
        }

        $user = User::create([
            'uuid'          => $randomString.$lastnameSlug,
            'first_name'    => $request->first_name,
            'last_name'     => $request->last_name,
            'user_slug'     => $user_slug,
            'email'         => $request->email,
            'accept_terms'         => $request->accept_terms,
            'user_role'     => 'customer',
            'number'         => $phone_no,
            'password'      => Hash::make($request->password),
            'profile_approved' => 0,
            'licence_approved' => 0,
            'enable_text_message' => 0,
            'facebook_auth' => 1,
        ]);

        Auth::login($user);
        \Mail::send(new CusConfirmEmail);
        \Mail::send(new CusWelcomeEmail);


        return redirect(route('cus_verify'))->with('pro_w_c', 'send confirmation and welcome emails');
    }


    public function FacebookAuth(){
        $title = 'Complete your profile';
        
        return view('auth.facebook_auth', compact('title'));
    }
    public function GoogleAuth(){
        $title = 'Complete your profile';
        
        return view('auth.google_auth', compact('title'));
    }
    public function FacebookLogin(Request $request){

        $users_all = User::orderBy('id', 'asc');
        $email = $request->email;
        if ($email != null) {
            
            $user = $users_all->where('email', '=', $email)->first();
            // echo $email;

            if ($user == null) {
                return redirect(route('register_customer'))->with('fb_log_error','We could not find your email, please register');
            }else{
                Auth::loginUsingId($user->id, true);
                return redirect(route('dashboard'));
            }

        }    
    }

    public function GoogleRegisterCustomerPost(Request $request){
        
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

        $cou_code = $request->number;

        if (strpos($cou_code, '+2340') !== false) {
            $phone_no = preg_replace('/^\+?2340|\|2340|\D/', '', ($cou_code)); 
             $phone_no = .0.$phone_no;
        }elseif (strpos($cou_code, '2340') !== false) {
            $phone_no = preg_replace('/^\+?2340|\|2340|\D/', '', ($cou_code)); 
             $phone_no = .0.$phone_no;
        }elseif (strpos($cou_code, '+234') !== false) {
            $phone_no = preg_replace('/^\+?234|\|234|\D/', '', ($cou_code)); 
             $phone_no = .0.$phone_no;
        }elseif (strpos($cou_code, '234') !== false) {
            $phone_no = preg_replace('/^\+?234|\|234|\D/', '', ($cou_code)); 
             $phone_no = .0.$phone_no;
        }else {
            $phone_no = $cou_code;
        }

        $user = User::create([
            'uuid'          => $randomString.$lastnameSlug,
            'first_name'    => $request->first_name,
            'last_name'     => $request->last_name,
            'user_slug'     => $user_slug,
            'email'         => $request->email,
            'accept_terms'         => $request->accept_terms,
            'user_role'     => 'customer',
            'number'         => $phone_no,
            'google_token'         => $request->google_token,
            'password'      => Hash::make($request->password),
            'profile_approved' => 0,
            'licence_approved' => 0,
            'enable_text_message' => 0,
            'facebook_auth' => 0,
        ]);

        Auth::login($user);

        \Mail::send(new CusConfirmEmail);
        \Mail::send(new CusWelcomeEmail);

        return redirect(route('cus_verify'))->with('pro_w_c', 'send confirmation and welcome emails');
    }


    public function GoogleLogin(Request $request){

        $users_all = User::orderBy('id', 'asc');
        $email = $request->email;
        if ($email != null) {
            
            $user = $users_all->where('email', '=', $email)->first();
            // echo $email;

            if ($user == null) {
                return redirect(route('register_customer'))->with('fb_log_error','We could not find your email, please register');
            }else{
                Auth::loginUsingId($user->id, true);
                return redirect(route('dashboard'));
            }

        }    
    }
    public function cusConfirm(){
        $title = 'Please verify your details';
        $user = auth()->user();
        return view('auth.boarding_main', compact('title', 'user'));
    }
// sending of customer and confirm emails
public function CusConfirmMail(Request $request){
       
    \Mail::send(new CusConfirmEmail($request));
    
        return back();

}
  


    public function proAccount(){
        $user = auth()->user();
        $title = $user->first_name .' '. $user->last_name . ' - Ubuy.ng';
        $u_cred = ProCredential::where('user_id', $user->id)->first();
        $states = State::orderBy('id')->get();

        return view('admin.pro.account', compact('title', 'user', 'u_cred', 'states'));
    }
    public function editCredentials(){
        $title = 'Update your credentials';
        $user = auth()->user();
        $u_cred = ProCredential::where('user_id', $user->id)->first();
        $states = State::orderBy('id')->get();
        return view('admin.account.credentials', compact('title', 'user', 'u_cred', 'states'));
    }
    // update pro credentials
public function updateCredentials(Request $request){
    $user = auth()->user();

    $rules = [
        'licence_type' => 'required',
        'licence_number' => 'required',
        'licence_state' => 'required',
        'licence_username' => 'required',
    ];
    $this->validate($request, $rules);

    $thumbnail = null;
    if ($request->hasFile('licence_photo')){
        $image = $request->file('licence_photo');

        $valid_extensions = ['jpg','jpeg','png'];
        if ( ! in_array(strtolower($image->getClientOriginalExtension()), $valid_extensions) ){
            return redirect()->back()->withInput($request->input())->with('error', 'Only .jpg, .jpeg and .png is allowed extension') ;
        }
        $file_base_name = str_replace('.'.$image->getClientOriginalExtension(), '', $image->getClientOriginalName());
        $resized_thumb = Image::make($image)->resize(600, 600)->stream();

        $thumbnail = strtolower(time().str_random(5).'-'.str_slug($file_base_name)).'.' . $image->getClientOriginalExtension();

        $thumbnailPath = '/images/pro_licence/'.$thumbnail;

        try{
            Storage::disk('public')->put($thumbnailPath, $resized_thumb->__toString());
        } catch (\Exception $e){
            return redirect()->back()->withInput($request->input())->with('error', $e->getMessage()) ;
        }
    }

   

    $data = [
        'licence_type' => $request->licence_type,
        'licence_number' => $request->licence_number,
        'licence_state' => $request->licence_state,
        'licence_username' => $request->licence_username,
    ];

    if ($thumbnail){
        $data['licence_photo'] = $thumbnail;
    }
    ProCredential::where('user_id', $user->id)->update($data);

    return redirect()->back();
}
    // save user detials
public function updateUser(Request $request){
    $user = auth()->user();

    $rules = [
        'first_name'      => ['required', 'string', 'max:190'],
        'last_name'      => ['required', 'string', 'max:190'],
    ];
    $this->validate($request, $rules);

    $thumbnail = null; 
    if ($request->hasFile('image')){
        $image = $request->file('image');

        $valid_extensions = ['jpg','jpeg','png'];
        if ( ! in_array(strtolower($image->getClientOriginalExtension()), $valid_extensions) ){
            return redirect()->back()->withInput($request->input())->with('error', 'Only .jpg, .jpeg and .png is allowed extension') ;
        }
        $file_base_name = str_replace('.'.$image->getClientOriginalExtension(), '', $image->getClientOriginalName());
        $resized_thumb = Image::make($image)->resize(600, 600)->stream();

        $thumbnail = strtolower(time().str_random(5).'-'.str_slug($file_base_name)).'.' . $image->getClientOriginalExtension();

        $thumbnailPath = '/images/profile_pics/'.$thumbnail;

        try{
            Storage::disk('public')->put($thumbnailPath, $resized_thumb->__toString());
        } catch (\Exception $e){
            return redirect()->back()->withInput($request->input())->with('error', $e->getMessage()) ;
        }
    }

   

    $data = [
        'first_name' => $request->first_name,
        'last_name' => $request->last_name,
    ];

    if ($thumbnail){
        $data['image'] = $thumbnail;
    }
    User::where('id', $user->id)->update($data);

    return redirect()->back();
}

public function editEmail(){
    $title = 'Edit Email UBUYNG';
    $user = Auth::user();
    return view('admin.account.edit_email', compact('title', 'user'));
}

    // save user detials
    public function updateEmail(Request $request){
        $user = auth()->user();
    
        $rules = [
            'email'    => 'required|email|unique:users,email,'.$user->id,
        ];
        $this->validate($request, $rules);   
       
    
        $data = [
            'email' => $request->email,
            'email_verify_code' => null
                ];
    
        
        User::where('id', $user->id)->update($data);
    
        return redirect(route('change_confirm_user'));
    }
    
    public function editNumber(){
        $title = 'Edit Number UBUYNG';
        $user = Auth::user();
        return view('admin.account.edit_number', compact('title', 'user'));
    }
    
    public function updateNumber(Request $request){
        $user = auth()->user();
    
        $rules = [
            'number'    => 'required|numeric|unique:users,number,'.$user->id,
        ];
        $this->validate($request, $rules);   
       
    
        $data = [
            'number' => $request->number,
            'number_verify_code' => null
                ];
    
        
        User::where('id', $user->id)->update($data);
    
        return redirect(route('change_confirm_user'));
    }

    public function changeConfirm(){
        $title = 'Please verify your details';
        $user = auth()->user();
        return view('admin.account.confirm_changes', compact('title', 'user'));
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
