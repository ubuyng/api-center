@extends('layouts.mvp_dash3')

@section('content')

@section('page-css')

  <style>
.boarding1_header img {
    width: 16%;
    margin: 20px;
}
.task-tags span {
    font-size: 56.7px;
    padding: 26px 22px;
}
.numbered.color {
    text-align: left;
    margin-left: 40px;
}
.numbered ol li {
    padding: 4px 0;
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
p.verify_guide {
    margin: 10px;
}
.boarding_header{
    text-align: center;
}
.boarding_header img{
    width: 60%;
}
  </style>
@endsection
<div class="container-fluid">
	<!-- Row -->
    <div class="row">

            <!-- Dashboard Box --> 
            <div class="col-xl-7">
                    <div class=" margin-top-30 margin-bottom-60">
                        <form id="profile_image" action="{{route('pro_save_verify_pic')}}" method="post" enctype="multipart/form-data">
                            @csrf
                        <div class="index_holder">
                            <div class="boarding1_header">
                                <img id="show_profile_image" src="/mvp_ui/images/onboarding/verify_pro.png" alt="" srcset="">
                                <h1 class="task-tags">
                                        <span>{{$verify_code}}</span>
                                </h1>
                                <h2>{{$title}} </h2>
                                <br>
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
                                <label for="profile-upload" class="custom-file-upload">
                                        <i class="icon-material-outline-account-circle"></i> Select Verification Photo
                                    </label>
                                    <input type="file"  class="input-text" id="profile-upload"  required name="thumbnail" >
                            </div>
                                        <input type="hidden" value="{{Auth::user()->id}}" name="user_id">
                                        <input type="hidden" value="{{$verify_code}}" name="verify_code">
                                        
                                        <button type="submit" id="upload_pro_pic_btn" class="button ladda-button dark full-width ripple-effect button-sliding-icon big margin-top-30" data-style="expand-left"> Continue  <i class="icon-material-outline-arrow-right-alt"></i> </button>
                                    </div>
                                </form>
                    </div>
            </div>
            <div class="col-xl-5">

                <div class="  boarding_tips_holder">
                    <div class="boarding_header padding-top-10 padding-bottom-5">
                        <img src="/mvp_ui/images/icons/onboarding_img.svg" alt="" srcset="">
                        <h3> <strong>  How to upload a verification image </strong></h3>
                    </div>
                    <div class="boarding_content">
                        <p style="text-align:center">Help us make ubuy.ng a safe place.</p>
                            <div class="numbered color">
                                    <ol>
                                        <li>Write the number above on a plain paper</li>
                                        <li>Take a selfie with the code</li>
                                        <li>Make sure your face is visible and the code can be seen</li>
                                        <li>Upload the selfie</li>
                                        <li>Get verified by admin and start earning</li>
                                    </ol>
                                </div>
                                <p>Having issues uploading your verification image? <strong><a href="mailto:idverify@ubuy.ng">Send a mail to idverify@ubuy.ng</a></strong></p>
                                <p> <Strong>Please read through our <a href="{{route('pro_guide')}}">Pro's Guidelines</a> and <a href="{{route('privacy_policy')}}">Privacy Policy</a> regarding this verification process.</Strong></p>
                          
                    </div>

                </div>


            </div>
            </div>

        </div>
        <!-- Row / End -->
  

    {{-- <div class="page_help">
        <button disabled="disabled">Help</button>
    </div> --}}
    @section('page-js')
   <script>
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
  e.preventDefault();
//   $("#upload_pro_pic_btn").text('Uploading Verify picture');
  var l = Ladda.create( document.querySelector( '#upload_pro_pic_btn' ) );
                // Start loading
                l.start();
  Snackbar.show({
		text: 'Uploading Verification pic',
		pos: 'top-center',
		showAction: false,
		actionText: "Dismiss",
		duration: 0,
		textColor: '#fff',
        dismiss:false,
		backgroundColor: '#383838'
	}); 
  $.ajax({
         url: "{{route('pro_save_verify_pic')}}",
   type: "POST",
   data:  new FormData(this),
   contentType: false,
         cache: false,
   processData:false,
   success: function(data)
      {
    if(data=='invalid')
    {
        console.log(data);
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
    l.stop();
    }
    else{
        console.log(data);

        Snackbar.show({
		text: 'Verification Picture saved',
		pos: 'top-center',
		showAction: false,
		actionText: "Dismiss",
		duration: 3000,
		textColor: '#fff',
        dismiss:false,
		backgroundColor: '#383838'
    }); 
    l.stop();
    window.location.href ='{{route('dashboard')}}';
    }
      },
     error: function(e) 
      {
        Snackbar.show({
		text: 'An error occured',
		pos: 'top-center',
		showAction: false,
		actionText: "Dismiss",
		duration: 1000,
		textColor: '#fff',
        dismiss:false,
		backgroundColor: '#383838'
	}); 
    l.stop();
      
}          
    });
 }));
});

   </script>

    @endsection

        @endsection
