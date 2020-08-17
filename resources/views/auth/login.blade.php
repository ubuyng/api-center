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
  //  window.location.href ='/register/google-auth?first_name=' + first_name + '&last_name=' + last_name + '&email=' + email + '&google_token=' + id_token ;
       $('#google_email').val(email)
           $("#submit_google_log").submit();
       console.log('User signed out.');
     });
              }, function(error) {
                // alert(JSON.stringify(error, undefined, 2));
              });
        }

        </script>
@endsection

<div class="pro_bg">
	
        <div class="container-fluid">
            <div class="row">
                <div class="col-xl-4 offset-xl-4  margin-top-20 form_bg">
    
                    <div class="login-register-page">
                        <!-- Welcome Text -->
                        <div class="welcome-text">
                            <h3 style="font-size: 26px;">We're glad to see you again!</h3>
                            <span>Don't have an account? <a href="{{route('register_customer')}}">Sign up</a></span>
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
                                                @csrf
                            <div class="input-with-icon-left">
                                <i class="icon-material-baseline-mail-outline"></i>
                                <input type="text" class="input-text with-border" name="email" id="email" placeholder="Email Address" value="{{ old('email') }}" required/>
                            </div>
                            @if ($errors->has('email'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('email') }}</strong>
                            </span>
                        @endif
                            <div class="input-with-icon-left" title="Should be at least 6 characters long" data-tippy-placement="bottom">
                                <i class="icon-material-outline-lock"></i>
                                <input type="password" class="input-text with-border" name="password" id="password" placeholder="Password" required/>
                            </div>
                            @if ($errors->has('password'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('password') }}</strong>
                            </span>
                        @endif
                                <div class="checkbox">
                                    <input type="checkbox" id="remember" checked  name="remember">
                                    <label for="remember"><span class="checkbox-icon"></span> Remember me</label>
                                </div>     
                               
                                <!-- Button -->
                        <button class="button full-width button-sliding-icon ripple-effect margin-top-10" type="submit"
                            >Log In <i class="icon-material-outline-arrow-right-alt"></i></button>
                            
                        </form>
    
    
                        <!-- Social Login -->
                        <div class="social-login-separator"><span>or</span></div>
                        <div class="social-login-buttons">
                            <button class="ladda-button facebook-login ripple-effect" id="login"  data-style="expand-right"><i class="icon-brand-facebook-f"></i> Log in
                                via Facebook</button>
                                <button id="customBtn" class="google-login ripple-effect"><i class="icon-brand-google-plus-g"></i> Log in
                                    via Google</button>

                        </div>
                    </div>
    
                </div>
            </div>
        </div>
    
        <form id="submit_fb_log" style="display:none" action="{{route('fb_login_user')}}" method="post">
        @csrf
        <input type="hidden" id="fb_email" name="email" value="">
        <button type="submit">submit</button>
        </form>
        <form id="submit_google_log" style="display:none" action="{{route('google_login_user')}}" method="post">
        @csrf
        <input type="hidden" id="google_email" name="email" value="">
        <button type="submit">submit</button>
        </form>
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
            var l = Ladda.create( document.querySelector( '.facebook-login' ) );
                // Start loading
                l.start();
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
             var user_email = response.email;
             $('#fb_email').val(user_email)
             $("#submit_fb_log").submit();
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
            <script>startApp();</script>

          <script>
          
  
   </script>
@endsection
        
@endsection
