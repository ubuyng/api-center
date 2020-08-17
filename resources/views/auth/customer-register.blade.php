@extends('layouts.mvp_ui')

@section('content')
 @section('page-css')

 <meta name="google-signin-scope" content="profile email">
 {{-- <meta name="google-signin-client_id" content="637779005926-ic6j3no78uc24ie2t8u43nhjmmk2f9ba.apps.googleusercontent.com"> --}}
 <script src="https://apis.google.com/js/platform.js" async defer></script>
 <script>
        var googleUser = {};
        var startApp = function() {
          gapi.load('auth2', function(){
            // Retrieve the singleton for the GoogleAuth library and set up the client.
            auth2 = gapi.auth2.init({
              client_id: '637779005926-ic6j3no78uc24ie2t8u43nhjmmk2f9ba.apps.googleusercontent.com',
              cookiepolicy: 'single_host_origin',
              // Request scopes in addition to 'profile' and 'email'
              //scope: 'additional_scope'
            });
            attachSignin(document.getElementById('customBtn'));
          });
        };
      
        function attachSignin(element) {
          console.log(element.id);
          auth2.attachClickHandler(element, {},
              function(googleUser) {
               
        // Useful data for your client-side scripts:
        var profile = googleUser.getBasicProfile();
   
   var first_name = profile.getName();
   var last_name = profile.getFamilyName();
   var email = profile.getEmail();
   // The ID token you need to pass to your backend:
   var id_token = googleUser.getAuthResponse().id_token;
   var auth2 = gapi.auth2.getAuthInstance();
 
     auth2.signOut().then(function () {
   window.location.href ='/register/google-auth?first_name=' + first_name + '&last_name=' + last_name + '&email=' + email + '&google_token=' + id_token ;
    //    $('#google_email').val(email)
    //        $("#submit_google_log").submit();
       console.log('User signed out.');
     });
              }, function(error) {
                alert(JSON.stringify(error, undefined, 2));
              });
        }

        </script>
 @endsection
<div class="pro_bg">
	
    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-4 offset-xl-4 form_bg margin-top-20">

                <div class="login-register-page">
                    <!-- Welcome Text -->
                    <div class="welcome-text">
                        <h3 style="font-size: 26px;">Let's create your account!</h3>
                        <span>Already have an account? <a href="{{route('login')}}">Log In!</a></span>
                    </div>

                    <!-- Account Type -->
                    <div class="account-type">

                            <div>
                                    <input type="radio" name="account-type-radio" id="customers-radio"
                                        class="account-type-radio" checked/>
                                        <label for="customers-radio" class="ripple-effect-dark">
                                            <i class="icon-material-outline-business-center"></i>
                                            Customers
                                        </label>
                                </div>

                        <div>
                            <input type="radio" name="account-type-radio" id="freelancer-radio"
                                class="account-type-radio"  />
                            <label for="freelancer-radio" class="ripple-effect-dark"><i
                                    class="icon-material-outline-account-circle"></i> Pros</label>
                        </div>
                    </div>

                     <form method="POST"action="" id="register-account-form">
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

                        @if(Session::has('cou_code'))
                        <div class="notification error closeable">
                                <ul>
                                        <li>{{ Session::get('cou_code') }} </li>
                                </ul>
                                <a class="close" href="#"></a>
                            </div>
                            @endif
                        @if(Session::has('fb_log_error'))
                        <div class="notification error closeable">
                                <ul>
                                        <li>{{ Session::get('fb_log_error') }} </li>
                                </ul>
                                <a class="close" href="#"></a>
                            </div>
                            @endif
        
                                            @csrf
                                            <div class="input-with-icon-left">
                            <i class="icon-material-outline-account-circle"></i>
                            <input type="text" class="input-text with-border" name="first_name" id="first_name" placeholder="First name"  
                            @if(isset($_GET['first_name']))
                            value="{{ $_GET['first_name']}}"
                            @else 
                            value="{{ old('first_name') }}"
                            @endif
                            required/>
                        </div>
                         @if ($errors->has('first_name'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('first_name') }}</strong>
                                </span>
                            @endif
                        <div class="input-with-icon-left">
                            <i class="icon-material-outline-account-circle"></i>
                            <input type="text" class="input-text with-border" name="last_name" id="last_name" placeholder="Last name" 
                            @if(isset($_GET['last_name']))
                            value="{{ $_GET['last_name']}}"
                            @else 
                            value="{{ old('last_name') }}"
                            @endif
                            required/>
                        </div>
                         @if ($errors->has('last_name'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('last_name') }}</strong>
                                </span>
                            @endif
                       
                        <div class="input-with-icon-left">
                            <i class="icon-feather-phone"></i>
                            <input type="text" class="input-text with-border" name="number" id="phone_number" placeholder="Phone number" 
                            @if(isset($_GET['phone_number']))
                            value="{{ $_GET['phone_number']}}"
                            @else 
                            value="{{ old('phone_number') }}"
                            @endif                 
                                       required/>
                        </div>
                        @if ($errors->has('phone_number'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('phone_number') }}</strong>
                        </span>
                    @endif
                    <div class="input-with-icon-left">
                            <i class="icon-material-baseline-mail-outline"></i>
                            <input type="email" class="input-text with-border" name="email" id="email" placeholder="Email Address"
                             @if(isset($_GET['email']))
                             value="{{ $_GET['email']}}"
                             @else 
                             value="{{ old('email') }}"
                             @endif  
                              required/>
                        </div>
                        @if ($errors->has('email'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('email') }}</strong>
                        </span>
                    @endif
                        <div class="input-with-icon-left" title="Should be at least 8 characters long" data-tippy-placement="bottom">
                            <i class="icon-material-outline-lock"></i>
                            <input type="password" class="input-text with-border" name="password" id="password" placeholder="Password" required/>
                        </div>
                        @if ($errors->has('password'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('password') }}</strong>
                        </span>
                    @endif
                        <div class="input-with-icon-left">
                            <i class="icon-material-outline-lock"></i>
                            <input type="password" class="input-text with-border" name="password_confirmation" id="password-repeat-register" placeholder="Repeat Password" required/>
                        </div>
                        @if ($errors->has('password_confirmation'))
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $errors->first('password_confirmation') }}</strong>
                                </span>
                            @endif

                            <div class="checkbox">
                                <input type="checkbox" id="terms" name="accept_terms" value="1">
                                <label for="terms"><span class="checkbox-icon"></span> Accept ubuy terms & conditions</label>
                            </div>     
                            @if ($errors->has('terms'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('accept_terms') }}</strong>
                            </span>
                        @endif
                            <!-- Button -->
                    <button class="button full-width button-sliding-icon ripple-effect margin-top-10 ladda-button" data-style="expand-left"  type="submit"
                        >Register <i class="icon-material-outline-arrow-right-alt"></i></button>
                        
                    </form>


                    <!-- Social Login -->
                    <div class="social-login-separator"><span>or</span></div>
                    <div class="social-login-buttons">
                        <button class="ladda-button facebook-login ripple-effect" data-style="expand-right" id="login"><i class="icon-brand-facebook-f"></i> Register
                            via Facebook</button>
                   
                            <button id="customBtn" class="google-login ripple-effect"><i class="icon-brand-google-plus-g"></i> Register
                                via Google</button>
                    </div>
                    <p>By signing up, I agress to Ubuy's <a href="{{route('terms_of_use')}}">Terms of Use</a>, <a href="{{route('privacy_policy')}}">Privacy Policy</a> 
                        and <a href="{{route('customer_guide')}}">Community Guidelines</a>.</p>
                </div>

            </div>
        </div>
    </div>

    <div class="miniDivider"></div>
</div>

@section('page-js')
<script>
(function(d, s, id) {
    var js, fjs = d.getElementsByTagName(s)[0];
    if (d.getElementById(id)) return;
    js = d.createElement(s); js.id = id;
    js.src = "https://connect.facebook.net/en_US/sdk.js";
    fjs.parentNode.insertBefore(js, fjs);
  }(document, 'script', 'facebook-jssdk'));
 
  // initialize the facebook sdk
 
  window.fbAsyncInit = function() {
    FB.init({
      appId      : '836158506742418',
      cookie     : false,  // enable cookies to allow the server to access 
                          // the session
      xfbml      : true,  // parse social plugins on this page
      version    : 'v3.1' // The Graph API version to use for the call
    });
 
}
</script>
<script>
    $(document).ready(function(){   
 
        

 // add event listener on the login button
 
 $("#login").click(function(){

    facebookLogin();

   
 });

 // add event listener on the logout button

 $("#logout").click(function(){

   $("#logout").hide();
   $("#login").show();
   $("#status").empty();
   facebookLogout();

 });


 function facebookLogin()
 {
   FB.getLoginStatus(function(response) {
       console.log(response);
       statusChangeCallback(response);
   });
 }

 function statusChangeCallback(response)
 {
     console.log(response);
     if(response.status === "connected")
     {
        $("#login").hide();
        $("#logout").show(); 
        fetchUserProfile();
     }
     else{
         // Logging the user to Facebook by a Dialog Window
         facebookLoginByDialog();
     }
 }

 function fetchUserProfile()
 {
   console.log('Welcome!  Fetching your information.... ');
   FB.api('/me?fields=id,first_name,last_name,email', function(response) {
     console.log(response);
     console.log('Successful login for: ' + response.first_name);
     var profile = `<h1>Welcome {response.first_name}<h1>
      <h2>Your email is ${response.email}</h2>
      <h3>Your Birthday is ${response.birthday}</h3>`;
     $("#status").append(profile);
       window.location.href ='/register/facebook-auth?first_name=' + response.first_name + '&last_name=' + response.last_name + '&email=' + response.email ;

   });
 }

 function facebookLoginByDialog()
 {
   FB.login(function(response) {
      
       statusChangeCallback(response);
      
   }, {scope: 'public_profile,email'});
 }

 // logging out the user from Facebook

 function facebookLogout()
 {
   FB.logout(function(response) {
       statusChangeCallback(response);
   });
 }


});
  </script>
    <script>
    
    $("#freelancer-radio").click(function(){
        window.location.href = "{{route('register_pro')}}";
});
    </script>

<script>startApp();</script>


@endsection
@endsection
