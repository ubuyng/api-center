@extends('layouts.mvp_ui')

@section('content')


@section('page-css')


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
  </style>
@endsection


<div class="container">
	<!-- Row -->
    <div class="row">

            <script>
                    @if ($user->number_verify_code != null & $user->email_verify_code != null) 
                               window.location.href= '/dashboard';
                   @endif
                   </script>
             <div class="col-xl-6 offset-xl-3">
                    <div class="dashboard-box margin-top-30 margin-bottom-60">
                <div class="index_holder">
                    <div class="boarding1_header stepper_des">
                        <h2><Strong>  Confirm your contact details </Strong></h2>
                        
                        
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
                <a href="{{route('dashboard')}}" class="button  dark ripple-effect full-width button-sliding-icon big margin-top-30"> Continue <i class="icon-material-outline-arrow-right-alt"></i> </a>
                </div>
             </div>
           
            </div>

        </div>
     

    @section('page-js')
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
    $.post("{{route('cus_confirm_email')}}",
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
</script>
    @endsection

        @endsection
