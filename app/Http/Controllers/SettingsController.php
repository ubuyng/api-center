<?php

namespace App\Http\Controllers;

use App\User;
use App\Option;
use App\Pricing;
use Illuminate\Http\Request;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Auth;

class SettingsController extends Controller
{
    public function GeneralSettings(){
        $title = trans('app.general_settings');
        return view('admin.settings-general', compact('title'));
    }

    public function GatewaySettings(){
        $title = trans('app.gateway_settings');
        return view('admin.settings-gateways', compact('title'));
    }
    public function EmailConfirm(){

        $users_all = User::orderBy('id', 'asc');

        if (isset($_GET['email'])) {
            $email= filter_input(INPUT_GET, 'email', FILTER_SANITIZE_STRING);
            $code= filter_input(INPUT_GET, 'token', FILTER_SANITIZE_STRING);
            $title = $email;
            
            $user = $users_all->where('email', '=', $email)->first();

            if ($user == null) {
                return redirect(route('home'));
            }else{

                // echo $code;
                $data = [
                    'email_verify_code' => $code,
                ];
                User::where('id', $user->id)->update($data);
                
                return view('auth.pro.email_confirmed', compact('title'));
            }
        }
        

      
    }

   
    /**
     * @param Request $request
     * @return array|\Illuminate\Http\RedirectResponse
     */
 


}
