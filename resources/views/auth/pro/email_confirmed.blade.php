@extends('layouts.mvp_ui')

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
  </style>
@endsection
<div class="container">
	<!-- Row -->
    <div class="row">

            <!-- Dashboard Box --> 
            <div class="col-xl-8 offset-xl-2">
                    <div class="dashboard-box margin-top-30 margin-bottom-60">
           
                        <div class="index_holder">
                            <div class="boarding1_header">
                                <img id="show_profile_image" src="/mvp_ui/images/icons/mail_confirmed.svg" alt="" srcset="">
                                <h1 class="task-tags">
                                        <span>Email verified</span>
                                </h1>
                                <h2>Thanks for confirming your email. </h2>
                                <br>
                                  
                               
                                    
                            <a href="{{route('login')}}" id="upload_pro_pic_btn" class="button dark ripple-effect full-width button-sliding-icon big margin-top-30"> Continue  <i class="icon-material-outline-arrow-right-alt"></i> </a>
                                    </div>
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

    @endsection

        @endsection
