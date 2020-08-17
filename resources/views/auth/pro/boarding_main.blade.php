@extends('layouts.mvp_dash3')

@section('content')


@section('page-css')


@if(isset($_GET['index5']))
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBhol0N_wyb0oZqcKjaU7afqPRFMfz7X80&v=3.exp&libraries=places"></script>

@endif

  <style>
  .boarding1_header img {
    width: 60%;
    margin: 20px;
}
.boarding1_header {
    text-align: center;
}
.dashboard-box{
    text-align: center;
}
.boarding1_header.stepper_des {
    padding-top: 32px;
}
.help_text{
    text-align: left;
}
input.input-text {
    margin-bottom: 1px;
}
.notice{
    text-align: center;
}
.stepper_des h4 {
    padding: 2px;
}
ul.list-3.color {
    text-align: left;
}
.section-headline h5 {
    text-align: left;
}
.modal-content {
    padding: 30px;
}
input[type="file"] {
    display: none;
}
.custom-file-upload {
    border: 1px solid #ccc;
    padding: 6px 12px;
    cursor: pointer;
}
img#show_profile_image {
    width: 100%;
    margin: 0px;
}
.cre_image_holder{
    margin-left: 25%;
    margin-right: 25%;
}
/* map css */
 /* Always set the map height explicitly to define the size of the div
       * element that contains the map. */
       #map {
        height: 100%;
      }
      .gm-style img { max-width: none; }
       .gm-style label { width: auto; display: inline; }
     
      #description {
        font-family: Roboto;
        font-size: 15px;
        font-weight: 300;
      }

      #infowindow-content .title {
        font-weight: bold;
      }

      #infowindow-content {
        display: none;
      }

      #map #infowindow-content {
        display: inline;
      }
      div#dvMap {
    margin-top: 0px !important;
    border-radius: 10px;
}
      .pac-card {
        margin: 10px 10px 0 0;
        border-radius: 2px 0 0 2px;
        box-sizing: border-box;
        -moz-box-sizing: border-box;
        outline: none;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.3);
        background-color: #fff;
        font-family: Roboto;
      }

      #pac-container {
        padding-bottom: 12px;
        margin-right: 12px;
      }

      .pac-controls {
        display: inline-block;
        padding: 5px 11px;
      }

      .pac-controls label {
        font-family: Roboto;
        font-size: 13px;
        font-weight: 300;
      }

      #pac-input {
       
        width: 400px;
        z-index: 10;
        margin-bottom: 0px;
      }
      .map_search {
    z-index: 10;
    margin-bottom: -40px;
}

      #pac-input:focus {
        border-color: #4d90fe;
      }

      #title {
        color: #fff;
        background-color: #4d90fe;
        font-size: 25px;
        font-weight: 500;
        padding: 6px 12px;
      }
      .map_holder{
          margin-top: 10px;
      }
  </style>
@endsection

<div class="notification notice closeable">
    <p>Welcome to Ubuy.ng, Let's setup your Pro Profile</p>
    {{-- <a class="close" href="#"></a> --}}
</div>
<div class="container">
	<!-- Row -->
    <div class="row">

            <!-- Dashboard Box --> 
            @if(isset($_GET['index1']))
            <div class="col-xl-8 offset-xl-2">
                    <div class="dashboard-box margin-top-30 margin-bottom-60">
                        <div class="index_holder">
                            <div class="boarding1_header">
                                <img src="/mvp_ui/images/onboarding/welcome_1.png" alt="" srcset="">
                                <h2>Welcome to Ubuy </h2>
                                <h4>Time to set up your professional Profile and attract thousands of customers</h4>
                            </div>
                            <a href="?index2" class="button dark ripple-effect full-width button-sliding-icon big margin-top-30"> Continue  <i class="icon-material-outline-arrow-right-alt"></i> </a>
                        </div>
                    </div>
            </div>
                        {{-- index 2 for email and number confirmation --}}
                     @elseif(isset($_GET['index2']))
                     <script>
                            @if ($user->number_verify_code != null & $user->email_verify_code != null) 
                                       window.location.href= '?index3';
                           @endif
                           </script>
                     <div class="col-xl-6 offset-xl-3">
                            <div class="dashboard-box margin-top-30 margin-bottom-60">
                        <div class="index_holder">
                            <div class="boarding1_header stepper_des">
                                <h2><Strong>Step 1</Strong> Confirm your contact details</h2>
                                <h4>Make sure you have the right contact details on ubuy.ng so 
                                    thousands of customers can reach you
                                </h4>
                                
						<div class="content with-padding padding-bottom-10">
                             

                                <div class="row">
                                        @if ($user->email_verify_code == null)
                                        <div class="col-xl-12">
                                                <div class="input-with-icon-left no-border">
                                                    <i class="icon-material-outline-drafts"></i>
                                                    <input type="text" value="{{ Auth::user()->email}}" class="input-text" disabled>
                                                   <h5 class="help_text">
                                                       <span>please confirm your email. 
                                                        <strong >Didn't get the Email <a id="resend_email" href="#">Resend Email</a></strong></span>
                                                        <br>
                                                       {{-- <strong>       
                                                           <a href="#">Not my email</a>
                                                    </strong> --}}
                                                    </h5>
                                                </div>
                                        </div>
                                                
                                            @else
                                            <div class="col-xl-12 padding-top-20" id="email_verify">
                                                    <h4>Email Confirmed</h4>
                                                </div> 

                                                
                                       
                                            @endif

                                            @if ($user->number_verify_code == null)
                                            
                                            <div class="col-xl-8 padding-top-20" id="phone_verify">
                                                    <div class="input-with-icon-left no-border ">
                                                            <i class="icon-feather-phone-outgoing"></i>
                                                            <input type="text" value="+234{{ Auth::user()->number }}" disabled class="input-text">
                                                            @if ($errors->has('number'))
                                                            <span class="invalid-feedback" role="alert">
                                                                <strong>{{ $errors->first('number') }}</strong>
                                                            </span>
                                                             @endif
                                                           <h5 class="help_text">
                                                               <span>please verify your Phone Number.</span>
                                                                <br> 
                                                            </h5>
                                                        </div>
                                                </div> 
                                                <div class="col-xl-12 padding-top-20" style="display:none" id="phone_verified">
                                                        <h4>Phone Number verified</h4>
                                                    </div> 
    
                                                    <div class="col-xl-4 padding-top-20" id="verify_btn">
                                                            <button  onclick="smsLogin();" class="facebook-number button dark ripple-effect full-width button-sliding-icon ladda-button" data-style="expand-left">Verify <i class="icon-feather-phone-outgoing"></i></button>    
                                                        </div> 
                                                @else
                                            <div class="col-xl-12 padding-top-20" id="phone_verify">
                                                    <h4>Phone Number verified</h4>
                                                </div> 

                                                    
                                              

                                                    
                                           
                                                @endif
                                                
                                                
                                                 
                                </div>
						</div>
                                </div>
                            </div>
                            <a href="?index3" class="button  dark ripple-effect full-width button-sliding-icon big margin-top-30"> Proceed to step 2  <i class="icon-material-outline-arrow-right-alt"></i> </a>
                        </div>
                     </div>
                     @elseif(isset($_GET['index3']))
                      <div class="col-xl-6 offset-xl-3">
                            <div class="dashboard-box margin-top-30 margin-bottom-60">
                                <form action="{{route('pro_onboarding_1')}}" method="post">
                        <div class="index_holder">
                            <div class="boarding1_header stepper_des">
                                    <h2><Strong>Step 2:</Strong> Tell us about your Business</h2>
                                <h4>Having a top-notch Public profile can put you ahead of pros in your area </h4>
                                
						<div class="content with-padding padding-bottom-10">
                            @csrf
                                <div class="row">
                                        @if ($errors->any())
                            <div class="notification error closeable">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                                <a class="close" href="#"></a>
                            </div>
                            @endif                            
                                        <div class="col-xl-12">
                                                    <div class="input-with-icon-left no-border">
                                                        <i class="icon-feather-user-check"></i>
                                                        <input type="text"  class="input-text" placeholder="Business name" required name="business_name" value="{{ old('business_name') }}"  >
                                                       <h5 class="help_text"> 
                                                       <span>Don't have a registered business name? use {{Auth::user()->first_name}} {{Auth::user()->last_name}}.</span>                                                          
                                                        </h5>
                                                    </div>
                                            </div>
                                            <br>
                                        <div class="col-xl-6">
                                                    <div class="input-with-icon-left no-border">
                                                        <i class="icon-feather-users"></i>
                                                        <input type="number"  class="input-text" required placeholder="Number of staffs" name="number_of_empolyees" value="{{ old('number_of_empolyees') }}"  >
                                                       <h5 class="help_text"> 
                                                        </h5>
                                                    </div>
                                            </div>
                                        <div class="col-xl-6">
                                                    <div class="input-with-icon-left no-border">
                                                        <i class="icon-material-outline-date-range"></i>
                                                        <input type="text"  class="input-text" placeholder="Founded at? (optional)" name="founded_year" value="{{ old('founded_year') }}"  >
                                                       <h5 class="help_text"> 
                                                        </h5>
                                                    </div>
                                                    <br>
                                            </div>
                                        <div class="col-xl-12">
                                                    <div class="input-with-icon-left no-border margin_top_10">
                                                        <i class="icon-material-outline-computer"></i>
                                                        <input type="text"  class="input-text" placeholder="Website (optional)" value="{{ old('website') }}"  >
                                                       <h5 class="help_text"> 
                                                           leave blank if non exist
                                                        </h5>
                                                    </div>
                                            </div>
                                        <div class="col-xl-12">
                                                    <div class="input-with-icon-left no-border margin_top_10">
                                                        <textarea name="about_profile" id="" required cols="20" rows="4" placeholder="Tell us about yourself and your business."></textarea>
                                                    </div>
                                                            <ul class="list-3 color">
                                                                <li>Focus on the key features of your brand</li>
                                                                <li>Tell customers what makes you different </li>
                                                            </ul>
                                            </div>
                                            
                                </div>
                            </div>
                        </div>
                        </div>
                        <input type="hidden" name="user_id" value="{{Auth::user()->id}}">
                        <button type="submit" class="button  dark ripple-effect full-width button-sliding-icon big margin-top-30"> Save and Continue <i class="icon-material-outline-arrow-right-alt"></i> </button>
                        </form>
                        </div>
                     </div>
                     @elseif(isset($_GET['index4']))
                        <div class="col-xl-6 offset-xl-3">
                                <div class="dashboard-box margin-top-30 margin-bottom-60">
                            <div class="index_holder">
                                <div class="boarding1_header stepper_des">
                                        <h2><Strong>Step 3:</Strong> What are your key strengths</h2>
                                    <h4>Show us some fascinating skills you've acquired over the years.</h4>
                                    
                            <div class="content with-padding padding-bottom-10">
                                @csrf
                                    <div class="row">
                                            @if ($errors->any())
                                <div class="notification error closeable">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                    <a class="close" href="#"></a>
                                </div>
                                @endif    
                                        
                                                <div class="col-xl-12">
                                                        <div class="section-headline margin-top-25 margin-bottom-12">
                                                            <h5>Tell us your skills</h5>
                                                        </div>
                                                        <div class="keywords-container skills_con">
                                                            <div class="keyword-input-container">
                                                                <input type="text" class="keyword-input skills_input" placeholder="Add skills Ex. Java - Plumbing - Dancing"/>
                                                                <h5 class="help_text"> 
                                                                        Press enter to save each skills
                                                                     </h5>
                                                                <button class="keyword-input-button skills_input_btn ripple-effect"><i class="icon-material-outline-add"></i></button>
                                                            </div>
                                                            <div class="keywords-list">
                                                            </div>
                                                            <div class="clearfix"></div>
                                                        </div>
                                                    </div>
                                                <div class="col-xl-12">
                                                        <div class="section-headline margin-top-25 margin-bottom-12">
                                                            <h5>what languages do you speak?</h5>
                                                        </div>
                                                        <div class="keywords-container lang_con">
                                                            <div class="keyword-input-container">
                                                                <input type="text" class="keyword-input lang_input" placeholder="Add Languages Ex. English - Igbo - Hausa- Yoruba"/>
                                                                <h5 class="help_text"> 
                                                                        Press enter to save each language
                                                                     </h5>
                                                                <button class="keyword-input-button lang_input_btn ripple-effect"><i class="icon-material-outline-add"></i></button>
                                                            </div>
                                                            <div class="keywords-list">
                                                                

                                                            </div>
                                                            <div class="clearfix"></div>
                                                        </div>
                                                </div>
                                                <div class="col-xl-12">
                                                        <div class="section-headline margin-top-25 margin-bottom-12">
                                                            <h5>Tell us about your educational background?</h5>
                                                        </div>
                                                        <div class="keywords-container edu_con">
                                                            <div class="keyword-input-container">
                                                                <input type="text" class="keyword-input edu_input" placeholder="Ex. - B.A French Language - Msc.Computer Science"/>
                                                                <h5 class="help_text"> 
                                                                        Press enter to save 
                                                                     </h5>
                                                                <button class="keyword-input-button edu_input_btn ripple-effect"><i class="icon-material-outline-add"></i></button>
                                                            </div>
                                                            <div class="keywords-list">
                                                          </div>

                                                                 
                                                            <div class="clearfix"></div>
                                                        </div>
                                                </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <a href="?index5"  class="button  dark ripple-effect full-width button-sliding-icon big margin-top-30"> Save and Continue <i class="icon-material-outline-arrow-right-alt"></i> </a>
                            </div>
                        </div>
                        @elseif(isset($_GET['index5']))
                                <div class="col-xl-8 offset-xl-2">
                                    <div class="dashboard-box margin-top-30 margin-bottom-60">
                                            <form action="{{route('pro_onboarding_locate')}}" method="post">
                                    <div class="index_holder">
                                        <div class="boarding1_header stepper_des">
                                                <h2><Strong>Step 4:</Strong> Your Location</h2>
                                            <h4>Your location would be used to find customers near you and increase your earnings.</h4>
                                            
                                    <div class="content with-padding padding-bottom-10">
                                        @csrf
                                            <div class="row">
                                                    @if ($errors->any())
                                        <div class="notification error closeable">
                                            <ul>
                                                @foreach ($errors->all() as $error)
                                                    <li>{{ $error }}</li>
                                                @endforeach
                                            </ul>
                                            <a class="close" href="#"></a>
                                        </div>
                                        @endif    
                                                                        
                                                <div class="col-xl-12">
                                                    <h5 class="help_text"> <mark class="color">
                                                        <span>where can customers find you?</span>                                                          
                                                    </mark>
                                                    </h5>
                                                            <div class="input-with-icon-left no-border">
                                                                <i class="icon-material-outline-search"></i>
                                                                <input type="text"  class="input-text" onFocus="initializeAutocomplete()" id="locality"  placeholder="What's your nearest location" required name="address" value="{{ old('address') }}"  >
                                                            </div>
                                                            <h5 class="help_text locate_help" style="display:none"> <mark >
                                                                    <span>If you can't find your location, use the closest landmark</span>                                                          
                                                                </mark>
                                                                </h5>
                                                    </div>
                                                    <br>
                                                    <div class="location_form row" style="display:none">                      
                                                <div class="col-xl-6">
                                                        <h5 class="help_text"> <mark class="color">
                                                                <span>Tell us your state</span>                                                          
                                                            </mark>
                                                            </h5>
                                                        <div class="input-with-icon-left no-border">
                                                            <i class="icon-material-outline-location-on"></i>
                                                            <select name="state" class="selectpicker">
                                                                @forelse ($states as $state)
                                                                <option value="{{$state->name}}">{{$state->name}}</option>
                                                                        
                                                                    @empty
                                                                        
                                                                    @endforelse
                                                            </select>
                                                        </div>
                                                </div>
                                                <div class="col-xl-6">
                                                        <h5 class="help_text"> <mark class="color">
                                                                <span>Your city</span>                                                          
                                                            </mark>
                                                            </h5>
                                                        <div class="input-with-icon-left no-border">
                                                            <i class="icon-material-outline-location-on"></i>
                                                            <input type="text"  class="input-text" placeholder="Your city" id="user_city" required name="city" value="{{ old('city') }}"  >
                                                        </div>
                                                </div>
                                                <div class="col-xl-12">
                                                    <br>
                                                    <div class="row">

                                                        <div class="col-xl-6">
                                                                <div class="input-with-icon-left no-border">
                                                                        <i class="icon-material-outline-location-on"></i>
                                                                        <input type="text" name="latitude"  required  class="input-text" id="latitude" value="{{ old('latitude') }}"  placeholder="latitude" >
                                                                    </div>
                                                        </div>
                                            
                                
                                                        <div class="col-xl-6">
                                                                <div class="input-with-icon-left no-border">
                                                                        <i class="icon-material-outline-location-on"></i>
                                                                        <input type="text" required id="longitude" value="{{ old('longitude') }}" name="longitude">
                                                                    </div>
                                                        </div>
                                                        
                                                      </div>

                                                </div>
                                            </div>

                                                      
                                </div>
                                </div>
                            </div>
                        </div>
                            <input name="user_id" type="hidden" value="{{Auth::user()->id}}">
                                <button type="submit" class="button  dark ripple-effect full-width button-sliding-icon big margin-top-30"> Save and Continue <i class="icon-material-outline-arrow-right-alt"></i> </button>
                            </form>
                                    </div>
                        </div>
                   @elseif(isset($_GET['index6']))
                                <div class="col-xl-7 offset-xl-3">
                                        <div class="dashboard-box margin-top-30 margin-bottom-60">
                                    <div class="index_holder">
                                        <div class="boarding1_header stepper_des">
                                                <h2><Strong>Step 6:</Strong> Upload your images & credential</h2>
                                            <h4>Having a trustworthy profile ranks you higher. <br>
                                                    <a href="#"> Please read through Ubuy Pros Guidelines.</a></h4>
                                            
                                    <div class="content with-padding padding-bottom-10">
                                         
                                            <form id="profile_image" action="{{route('pro_save_profile_pic')}}" method="post" enctype="multipart/form-data">
                                                <div class="row">
                                                        @if ($errors->any())
                                                        <div class="notification error closeable">
                                                            <ul>
                                                                @foreach ($errors->all() as $error)
                                                                <li>{{ $error }}</li>
                                                                @endforeach
                                                            </ul>
                                                            <a class="close" href="#"></a>
                                                        </div>
                                                        @endif 
                                                @csrf 
                                               
                                                <div class="col-xl-6 cre_image_holder">
                                                        <img src="#" id="show_profile_image" alt="">
                                                    </div>
                                                       
                                                                    <div class="col-xl-12">
                                                                            <div class="section-headline margin-top-25 margin-bottom-12">
                                                                            <h5>Upload your Profile Picture</h5>
                                                                        </div>
                                                                        <label for="profile-upload" class="custom-file-upload">
                                                                            <i class="icon-material-outline-account-circle"></i> Select Profile Photo
                                                                        </label>
                                                                        <input type="file"  class="input-text" id="profile-upload"  required name="thumbnail" >
                                                                        
                                                                    </div>
                                                                  
                                                           <div class="col-xl-12">

                                                               <input type="hidden" value="{{Auth::user()->id}}" name="user_id">
                                                               <button id="upload_pro_pic_btn" class="button  ripple-effect button-sliding-icon dark ladda-button" data-style="expand-left"> Upload Profile Picture <i class="icon-feather-upload-cloud"></i> </button>
                                                            </div>

                                                    
                                                </div>
                                            </form>

                                            <form  action="{{route('pro_save_cre')}}" method="post" enctype="multipart/form-data">
                                                <div class="row">
                                                    @csrf 
                                                    @if ($errors->any())
                                                    <div class="notification error closeable">
                                                        <ul>
                                                            @foreach ($errors->all() as $error)
                                                            <li>{{ $error }}</li>
                                                            @endforeach
                                                        </ul>
                                                        <a class="close" href="#"></a>
                                                    </div>
                                                    @endif 
                                                    <div class="col-xl-12">
                                                        	<div class="section-headline margin-top-25 margin-bottom-12">
                                                                    <h5>Select Licence Type</h5>
                                                                </div>
                                                            <div class="input-with-icon-left no-border">
                                                                <select name="licence_type" class="selectpicker">
                                                                    <option value="national_id">National ID</option>
                                                                <option value="drivers_licence">Drivers Licence</option>
                                                                <option value="votters_card">Votters Card</option>
                                                                <option value="international_passport">International Passport</option>
                                                                   
                                                                    </select>
                                                                    <br>

                                                            </div>
                                                    </div>
                                                   
                                                    <div class="col-xl-6">
                                                            <div class="input-with-icon-left no-border">
                                                                <i class="icon-material-outline-location-on"></i>
                                                                <input type="text"  class="input-text" placeholder="Name on licence" required name="licence_username" value="{{ old('licence_username') }}"  >
                                                            </div>
                                                    </div>
                                                    <div class="col-xl-6">
                                                            <div class="input-with-icon-left no-border">
                                                                <i class="icon-material-outline-location-on"></i>
                                                                <input type="text"  class="input-text" placeholder="Licence id number" required name="licence_number" value="{{ old('licence_number') }}"  >
                                                            </div>
                                                    </div>

                                                    <div class="col-xl-12">
                                                        	<div class="section-headline margin-top-25 margin-bottom-12">
                                                                    <h5>Select State on Licence</h5>
                                                                </div>
                                                            <div class="input-with-icon-left no-border">
                                                                <select name="licence_state" class="selectpicker">
                                                                        @forelse ($states as $state)
                                                                        <option value="{{$state->name}}">{{$state->name}}</option>
                                                                                
                                                                            @empty
                                                                                
                                                                            @endforelse
                                                                    </select>
                                                                
                                                            </div>
                                                    </div>

                                                    <div class="col-xl-6 cre_image_holder">
                                                            <img src="#" id="show_licence_image" alt="">
                                                        </div>
                                                           
                                                                        <div class="col-xl-12">
                                                                                <div class="section-headline margin-top-25 margin-bottom-12">
                                                                                <h5>Upload your your licence </h5>
                                                                            </div>
                                                                            <label for="licence-upload" class="custom-file-upload">
                                                                                <i class="icon-material-outline-account-circle"></i> Upload a captured or scanned copy of your licence
                                                                            </label>
                                                                            <input type="file"  class="input-text" id="licence-upload"   name="licence_photo" >
                                                                            <h5 class="help_text"> 
                                                                                <span>Only png and jpg format are accepted </span>                                                          
                                                                            </h5>
                                                                        </div>
                                                                      

                                                </div>
                                                <input type="hidden" name="user_id" value="{{Auth::user()->id}}">
                                <button type="submit" class="button  dark ripple-effect full-width button-sliding-icon big margin-top-30"> Save and Continue <i class="icon-material-outline-arrow-right-alt"></i> </button>
                              
                                            </form>

                                        </div>
                                    </div>
                                </div>
                                  </div>
                                </div>
                            
                                {{-- page indexs ends here --}}
                        @else
                        <div class="col-xl-8 offset-xl-2">
                                <div class="dashboard-box margin-top-30 margin-bottom-60">
                            <div class="index_holder">
                                <div class="boarding1_header">
                                    <img src="/mvp_ui/images/onboarding/welcome_1.png" alt="" srcset="">
                                    <h2>Welcome to Ubuy </h2>
                                    <h4>Time to set up your professional Profile and attract thousands of customers</h4>
                                </div>
                                <a href="?index2" class="button dark ripple-effect full-width button-sliding-icon big margin-top-30"> Continue  <i class="icon-material-outline-arrow-right-alt"></i> </a>
                            </div>
                                </div>
                        </div>
                        @endisset
            </div>
            </div>

        </div>
        <!-- Row / End -->
    </div>

    {{-- <div class="page_help">
        <button disabled="disabled">Help</button>
    </div> --}}
    @section('page-js')
    @if(isset($_GET['index2']))
<!-- HTTPS required. HTTP will give a 403 forbidden response -->
<script src="https://sdk.accountkit.com/en_US/sdk.js"></script>
<script>
        // initialize Account Kit with CSRF protection
        AccountKit_OnInteractive = function(){
          AccountKit.init(
            {
              appId:836158506742418, 
              state:"{{ csrf_token() }}", 
              version:"v1.1",
              fbAppEventsEnabled:true,
              redirect:"https://beta.ubuy.ng/dashboard/onboarding/pro/welcome?index3"
            }
          );
        };
        AccountKit.init();
      
        // login callback
        function loginCallback(response) {
          if (response.status === "PARTIALLY_AUTHENTICATED") {
            var code = response.code;
            var csrf = response.state;
            Snackbar.show({
                        text: 'Checking verification...',
                        pos: 'top-center',
                        showAction: false,
                        actionText: "Dismiss",
                        duration:3000,
                        textColor: '#fff',
                        dismiss:false,
                        backgroundColor: '#383838'
                    }); 
            console.log('code is '+code);
            console.log('csfr is'+csrf);
            send_phone_verify(csrf);
           
            // Send code to server to exchange for access token
          }
          else if (response.status === "NOT_AUTHENTICATED") {
            // handle authentication failure
          }
          else if (response.status === "BAD_PARAMS") {
            // handle bad parameters
          }
        }
      
        // phone form submission handler
        function smsLogin() {
            var l = Ladda.create( document.querySelector( '.facebook-number' ) );
                // Start loading
                l.start();
            var countryCode = '+234';
          var phoneNumber = {{ Auth::user()->number}};
          AccountKit.login(
            'PHONE', 
            {countryCode: countryCode, phoneNumber: phoneNumber}, // will use default values if not specified
            loginCallback
          );
        }


        function send_phone_verify(csrf) {
                $.post("{{route('pro_verify_number')}}",
                {
                    "_token": "{{ csrf_token() }}",
                    user_id: "{{Auth::user()->id}}",
                    number_verify_code:csrf,
                },
                function(data, status){
                    Snackbar.show({
                        text: 'Number Verified',
                        pos: 'top-center',
                        showAction: false,
                        actionText: "Dismiss",
                        duration:2000,
                        textColor: '#fff',
                        dismiss:false,
                        backgroundColor: '#383838'
                    }); 
                     // Stop loading
            var l = Ladda.create( document.querySelector( '.facebook-number' ) );
            l.stop();
            $('#phone_verify').fadeOut();
            $('#verify_btn').fadeOut();
            $('#phone_verified').fadeIn();
            
                });
                }
      
      
 </script>
@endif
    <script>
            $('#upload_pro_pic_btn').fadeOut();

    function readURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
            $('#show_profile_image').attr('src', e.target.result);
        }

        reader.readAsDataURL(input.files[0]);
    }
}

$("#profile-upload").change(function(){
    readURL(this);
    $('#upload_pro_pic_btn').fadeIn();
});

$(document).ready(function (e) {
 $("#profile_image").on('submit',(function(e) {
    var l = Ladda.create( document.querySelector( '#upload_pro_pic_btn' ) );
                // Start loading
                l.start();
  e.preventDefault();
  Snackbar.show({
		text: 'Uploading profile pic',
		pos: 'top-center',
		showAction: false,
		actionText: "Dismiss",
		duration: 3000,
		textColor: '#fff',
        dismiss:false,
		backgroundColor: '#383838'
	}); 
  $.ajax({
         url: "{{route('pro_save_profile_pic')}}",
   type: "POST",
   data:  new FormData(this),
   contentType: false,
         cache: false,
   processData:false,
   success: function(data, response)
      {
    if(data=='invalid')
    {
        console.log('error');
        Snackbar.show({
		text: 'Image not saved!!',
		pos: 'top-center',
		showAction: false,
		actionText: "Dismiss",
		duration: 1000,
		textColor: '#fff',
        dismiss:false,
		backgroundColor: '#383838'
	}); 
    }
    else{
        console.log(response);

        Snackbar.show({
		text: 'Profile Picture saved',
		pos: 'top-center',
		showAction: false,
		actionText: "Dismiss",
		duration: 3000,
		textColor: '#fff',
        dismiss:false,
		backgroundColor: '#383838'
	}); 
    l.stop();
    }
      },
     error: function(e) 
      {
    $("#err").html(e).fadeIn();
      }          
    });
 }));
});

    </script>

    {{-- <script>
    // licence file display
    function readURL(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
            $('#show_licence_image').attr('src', e.target.result);
        }

        reader.readAsDataURL(input.files[0]);
    }
}

$("#licence-upload").change(function(){
    readURL(this);
});

    </script> --}}
          @if(isset($_GET['index5']))
    
          <script>
                  function initializeAutocomplete(){
      var input = document.getElementById('locality');
      var options = {
        types: ['(regions)'],
        componentRestrictions: {country: "NG"}
      };
      

      var options = {}
  
      var autocomplete = new google.maps.places.Autocomplete(input, options);
      google.maps.event.addListener(autocomplete, 'place_changed', function() {
        var place = autocomplete.getPlace();
        console.log(place);
        var lat = place.geometry.location.lat();
        var lng = place.geometry.location.lng();
        // var placeId = place.place_id;
        var city = place.address_components[1].long_name + ' ' + place.address_components[2].long_name;

$('.location_form').fadeIn();
$('.locate_help').fadeIn();
        var placeId = place.formatted_address;
        console.log(placeId);
        // to set city name, using the locality param
        var componentForm = {
          locality: 'short_name',
        };
        for (var i = 0; i < place.address_components.length; i++) {
          var addressType = place.address_components[i].types[0];
          if (componentForm[addressType]) {
            var val = place.address_components[i][componentForm[addressType]];
            // document.getElementById("city").value = val;
          }
        }
        // $('.location_form').fadeIn();

        document.getElementById("latitude").value = lat;
        document.getElementById("longitude").value = lng;
        document.getElementById("user_city").value = city;
      });
    }
            
            </script>

        @endif
      
          
    <script>
    $('#resend_email').click(function() { 
	Snackbar.show({
		text: 'Sending Email',
		pos: 'top-center',
		showAction: false,
		actionText: "Dismiss",
		duration: 3000,
		textColor: '#fff',
        dismiss:false,
		backgroundColor: '#383838'
	}); 

send_c();

function send_c() {
    $.post("{{route('pro_confirm_email')}}",
  {
    "_token": "{{ csrf_token() }}",
    user_id: "{{Auth::user()->id}}",
    user_email: "{{Auth::user()->email}}",
  },
  function(data, status){
    Snackbar.show({
		text: 'Verification Email as been sent!!',
		pos: 'top-center',
		showAction: false,
		actionText: "Dismiss",
		duration: 3000,
		textColor: '#fff',
        dismiss:false,
		backgroundColor: '#383838'
	}); 
  });
}
}); 



// skills ajax 
$(".skills_con").each(function() {
            var keywordInput = $(this).find(".skills_input");

            function addKeyword() {
                $.post("{{route('pro_save_skill')}}",
                {
                    "_token": "{{ csrf_token() }}",
                    user_id: "{{Auth::user()->id}}",
                    skill_title: keywordInput.val(),
                    skill_type:'skills',
                },
                function(data, status){
                    Snackbar.show({
                        text: 'Skill saved',
                        pos: 'top-center',
                        showAction: false,
                        actionText: "Dismiss",
                        duration:2000,
                        textColor: '#fff',
                        dismiss:false,
                        backgroundColor: '#383838'
                    }); 
                });
                }
            keywordInput.on('keyup', function(e) {
                if ((e.keyCode == 13) && (keywordInput.val() !== "")) {
                    addKeyword();
                }
            });
            $('.skills_input_container').on('click', function() {
                if ((keywordInput.val() !== "")) {
                    addKeyword();
                }
            });
            $(document).on("click", ".keyword-remove", function() {
                $(this).parent().addClass('keyword-removed');
                var keyworddelete = $(this).parent().find(".keyword-text");

                $.post("{{route('skill_destroy')}}",
                {
                    "_token": "{{ csrf_token() }}",
                    user_id: "{{Auth::user()->id}}",
                    skill_title: keyworddelete.text(),
                },
                function(data, status){
                   console.log('item removed');
                });
                function removeFromMarkup() {
                    $(".keyword-removed").remove();

                }
                setTimeout(removeFromMarkup, 500);
                keywordsList.css({
                    'height': 'auto'
                }).height();
            });
          
        });

        {{-- language --}}
$(".lang_con").each(function() {
            var keywordInput = $(this).find(".lang_input");

            function addKeyword() {
                $.post("{{route('pro_save_skill')}}",
                {
                    "_token": "{{ csrf_token() }}",
                    user_id: "{{Auth::user()->id}}",
                    skill_title: keywordInput.val(),
                    skill_type:'language',
                },
                function(data, status){
                    Snackbar.show({
                        text: 'Language saved',
                        pos: 'top-center',
                        showAction: false,
                        actionText: "Dismiss",
                        duration: 1000,
                        textColor: '#fff',
                        dismiss:false,
                        backgroundColor: '#383838'
                    }); 
                });
                }
            keywordInput.on('keyup', function(e) {
                if ((e.keyCode == 13) && (keywordInput.val() !== "")) {
                    addKeyword();
                }
            });
            $('.lang_input_container').on('click', function() {
                if ((keywordInput.val() !== "")) {
                    addKeyword();
                }
            });
            $(document).on("click", ".keyword-remove", function() {
                $(this).parent().addClass('keyword-removed');
                var keyworddelete = $(this).parent().find(".keyword-text");

                $.post("{{route('skill_destroy')}}",
                {
                    "_token": "{{ csrf_token() }}",
                    user_id: "{{Auth::user()->id}}",
                    skill_title: keyworddelete.text(),
                },
                function(data, status){
                    console.log('item removed');
                });
                function removeFromMarkup() {
                    $(".keyword-removed").remove();

                }
                setTimeout(removeFromMarkup, 500);
                keywordsList.css({
                    'height': 'auto'
                }).height();
            });
          
        });
        {{-- education --}}
$(".edu_con").each(function() {
            var keywordInput = $(this).find(".edu_input");

            function addKeyword() {
                $.post("{{route('pro_save_skill')}}",
                {
                    "_token": "{{ csrf_token() }}",
                    user_id: "{{Auth::user()->id}}",
                    skill_title: keywordInput.val(),
                    skill_type:'education',
                },
                function(data, status){
                    Snackbar.show({
                        text: 'Record saved',
                        pos: 'top-center',
                        showAction: false,
                        actionText: "Dismiss",
                        duration: 1000,
                        textColor: '#fff',
                        dismiss:false,
                        backgroundColor: '#383838'
                    }); 
                });
                }
            keywordInput.on('keyup', function(e) {
                if ((e.keyCode == 13) && (keywordInput.val() !== "")) {
                    addKeyword();
                }
            });
            $('.edu_input_container').on('click', function() {
                if ((keywordInput.val() !== "")) {
                    addKeyword();
                }
            });
            $(document).on("click", ".keyword-remove", function() {
                $(this).parent().addClass('keyword-removed');
                var keyworddelete = $(this).parent().find(".keyword-text");

                $.post("{{route('skill_destroy')}}",
                {
                    "_token": "{{ csrf_token() }}",
                    user_id: "{{Auth::user()->id}}",
                    skill_title: keyworddelete.text(),
                },
                function(data, status){
                    console.log('item removed');
                });
                function removeFromMarkup() {
                    $(".keyword-removed").remove();

                }
                setTimeout(removeFromMarkup, 500);
                keywordsList.css({
                    'height': 'auto'
                }).height();
            });
          
        });

</script>
    @endsection

        @endsection
