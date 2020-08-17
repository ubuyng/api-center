<?php

namespace App\Http\Controllers;

use App\User;
use App\Option;
use App\Pricing;
use App\Category;
use App\Dev\Mail\ContactUs;
use Illuminate\Http\Request;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Auth;

class MinPageController extends Controller
{
    public function AboutUs(){
        $title = 'About us - Ubuy.ng';

        return view('pages.about_us', compact('title'));
    }
    public function ContactUs(){
        $title = 'Contact us - Ubuy.ng';

        return view('pages.contact_us', compact('title'));
    }
    public function Careers(){
        $title = 'Join The Team - Ubuy.ng';

        return view('pages.career', compact('title'));
    }
    public function HowItWorks(){
        $title = 'How UbuyNG works -  Ubuy.ng';

        return view('pages.how_it_work', compact('title'));
    }
    public function Terms(){
        $title = 'UBUY.NG | Terms of use';

        return view('pages.terms', compact('title'));
    }
    public function Privacy(){
        $title = 'UBUY.NG | Privacy Policy';

        return view('pages.privacy', compact('title'));
    }
    public function ProGuide(){
        $title = 'Pros Guidelines | UBUY.NG';

        return view('pages.pros_guide', compact('title'));
    }
    public function CusGuide(){
        $title = 'Customer Guidelines | UBUY.NG';

        return view('pages.customer_guide', compact('title'));
    }
    public function safety(){
        $title = 'Safety & Precautions | UBUY.NG';

        return view('pages.safety', compact('title'));
    }

    public function SpinHowItWorks(){
        $title = 'How UbuyNG works -  Ubuy.ng';

        return view('pages.spin_how_it_work', compact('title'));
    }

    // sending of confirmation and welcome emails
    public function contactUsPost(Request $request){
       
        \Mail::send(new ContactUs($request));
        
            return back();

    }
   
    /**
     * @param Request $request
     * @return array|\Illuminate\Http\RedirectResponse
     */
 


}
