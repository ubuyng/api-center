@extends('layouts.mvp_ui')

@section('page-css')
    <style>
      .htw_c_1{
        text-align: center;
      }
      .htw_c_1 img {
    width: 68%;
    margin-bottom: 18px;
}
.banner-headline {
    max-width: 100%;
    text-align: center;
}
    </style>
@endsection

@section('content')
   
      <div class="section padding-top-65 padding-bottom-50">
        <div class="container">
          
            <div class="notification notice closeable">
                <p>Congratulations! your {{$_GET['deal']}} is on it's way</p>
            </div>

          <div class="row">
            <div class="col-xl-5 offset-xl-4">
                <div class="status-switch" id="snackbar-user-status">
                    <label class="user-online current-status">Customer</label>
                    <label class="user-invisible ">Professional</label>
                    <!-- Status Indicator -->
                    <span class="status-indicator" aria-hidden="true"></span>
                  </div>
            </div>
          </div>
        </div>
      </div>
<div id="customer">
  <div class="container">
    <div class="row">
      	<div class="col-lg-6 col-md-8 col-sm-12">
            
            <h2><strong>Finding local professionals has never been easier.</strong></h2>
            <br>
            <p>Ubuy.ng is home to 1820+ fully verified professionals across Nigeria. 
                Ubuy.ng connects you to professionals for any task, project or assistance you need.
            </p>
            <a href="{{route('register_customer')}}?first_name={{$_GET['first_name']}}&last_name={{$_GET['last_name']}}&email={{$_GET['email']}}&number={{$_GET['number']}}" 
            class="button button-sliding-icon ripple-effect big margin-top-20">Get Started <i class="icon-material-outline-arrow-right-alt"></i></a>
        </div>
          <div class="col-lg-6 col-md-8 col-sm-12">
                  <a href="#sign-in-dialog" class="popup-with-zoom-anim">
        
                    <div class="vid_holder"><img src="/mvp_ui/images/play_video.png" alt=""></div>
                  </a>
          </div>
            <br>
          <div class="col-xl-4 htw_c_1">
            <img src="/mvp_ui/images/icons/htw/htw_customer1.svg" alt="">
            <h3> <Strong>Post a free project</Strong></h3>
            <p>Tell us what you want done, where you require the service and get connected to top skilled professionals.
              </p>
          </div>

          <div class="col-xl-4 htw_c_1">
            <img src="/mvp_ui/images/icons/htw/htw_customer2.png" alt="">
            <h3> <Strong>Review and choose a pro</Strong></h3>
            <ul style="text-align: initial;">
              <li>Browse Pro's profile</li>
              <li>Chat in real-time</li>
            </ul>
          </div>
          <div class="col-xl-4 htw_c_1">
            <img src="/mvp_ui/images/icons/htw/htw_customer3.svg" alt="">
            <h3> <Strong>Make a choice</Strong></h3>
           <p> Compare bids, select the best one and get your task completed </p>
          </div>
    </div>
  </div>

  <div class="intro-banner dark-overlay" data-background-image="mvp_ui/images/career_bg.jpg">

    <!-- Transparent Header Spacer -->
  
    <div class="container">
      
      <!-- Intro Headline -->
      <div class="row">
        <div class="col-md-6 offset-md-3">
          <div class="banner-headline">
            <h3>
              <strong>Start a new project?</strong>
            </h3>
          </div>
          <form action="{{route('search_subcat')}}" method="post">
            <div class="intro-banner-search-form margin-top-95">
    
            @csrf
    
              <!-- Search Field -->
              <div class="intro-search-field">
                <label for ="intro-keywords" class="field-title ripple-effect">What would you like done?</label>
                <select id="ajax-select" name="category_id" class="selectpicker with-ajax" data-live-search="true">
                  </select>
              </div>
            
    
              <!-- Button -->
              <div class="intro-search-button">
                <button class="button ripple-effect" type="submit">Search</button>
              </div>
                
            </div>
          </form>
        </div>
      </div>
      
    </div>
  </div>

  {{-- <div class="container padding-top-65">
    <div class="row">
        <div class="col-lg-6 col-md-8 col-sm-12">
            <a href="#sign-in-dialog" class="popup-with-zoom-anim">
  
              <div class="vid_holder"><img src="/mvp_ui/images/play_video.png" alt=""></div>
            </a>
    </div>
      <div class="col-lg-6 col-md-8 col-sm-12">
        <h2><strong>Finding local professionals has never been easier.</strong></h2>
        <br>
        <p>Ubuy.ng is home to 1820+ fully verified professionals across Nigeria. 
            Ubuy.ng connects you to professionals for any task, project or assistance you need.
        </p>
        <a href="{{route('register_customer')}}" class="button button-sliding-icon ripple-effect big margin-top-20">Get Started <i class="icon-material-outline-arrow-right-alt"></i></a>
    </div>
     
    </div>
  </div> --}}

  {{-- customer ends --}}
</div>
<div id="pro" style="display:none;">
 
  <div class="container padding-top-65">
    <div class="row">
        <div class="col-lg-6 col-md-8 col-sm-12">
  
              <div class="vid_holder"><img src="/mvp_ui/images/icons/htw/htw_pro1.svg" alt=""></div>
    </div>
      <div class="col-lg-6 col-md-8 col-sm-12">
        <h2><strong>Thousands of tasks at your finger tips.</strong></h2>
        <br>
        <p>
            There’s a huge range of tasks on ubuy.ng. From home-based tasks such as cleaning, gardening and handyman tasks; to office-based tasks, such as marketing, graphic design and web development tasks. There are also a bunch of interesting tasks as well, for example, wedding help, cake baking or makeup artists.  
        </p> <br>
        <a href="{{route('register_pro')}}?first_name={{$_GET['first_name']}}&last_name={{$_GET['last_name']}}&email={{$_GET['email']}}&number={{$_GET['number']}}" class="button  ripple-effect big  margin-top-20">Start Earning <i class="icon-material-outline-arrow-right-alt"></i></a>
    </div>
    <br>
    <div class="col-xl-4 htw_c_1">
      <img src="/mvp_ui/images/icons/htw/htw_pro2.svg" alt="">
      <h3> <Strong>Create a free Pro account</Strong></h3>
      <ul style="text-align: initial;">
          <li>Verify your contact details</li>
          <li> Select your skills and expertise</li>
          <li> Tell us your location</li>
          <li> Upload a professional profile photo</li>
          <li> Complete ubuy.ng verification process</li>
        </ul>
    </div>

    <div class="col-xl-4 htw_c_1">
      <img src="/mvp_ui/images/icons/htw/htw_pro3.svg" alt="">
      <h3> <Strong>Add services you offer</Strong></h3>
     <p>Get local task requests within your location and feel free to extend and set where you want to work. </p>
    </div>
    <div class="col-xl-4 htw_c_1">
      <img src="/mvp_ui/images/icons/htw/htw_pro4.svg" alt="">
      <h3> <Strong>Browse tasks</Strong></h3>
      <p>
Search for tasks nearby that match your skill set by using distance, When you find the right task, submit a bid!
       </p>
    </div>
    </div>
  </div>

  <div class="intro-banner dark-overlay" data-background-image="mvp_ui/images/how_to_use3.png">

    <!-- Transparent Header Spacer -->
  
    <div class="container">
      
      <!-- Intro Headline -->
      <div class="row">
        <div class="col-md-6 offset-md-3">
          <div class="banner-headline">
            <h2>
              <strong>What are you waiting for</strong>
            </h2>
            <h3>Create a free professional profile now and strat getting offers</h3>
          <a href="{{route('register_pro')}}?first_name={{$_GET['first_name']}}&last_name={{$_GET['last_name']}}&email={{$_GET['email']}}&number={{$_GET['number']}}" class="button  ripple-effect big  margin-top-20">Browse Jobs</a>
          </div>
        </div>
      </div>
      
    </div>
  </div>
{{-- pro ends --}}
</div>
<!-- video Popup
================================================== -->
<div id="sign-in-dialog" class="zoom-anim-dialog mfp-hide dialog-with-tabs">

		<!--Tabs -->
		<iframe width="560" height="315" src="https://www.youtube.com/embed/W3HoMVxtFrs?controls=0" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
	</div>

      @section('page-js')

      <script type="text/javascript" src="/mvp_ui/vendor/ajax_bootstrap_select/ajax-bootstrap-select.min.js"></script>
<script>
    var options = {
        ajax          : {
            url     : '{{route('fetch_subcat')}}',
            type    : 'POST',
            dataType: 'json',
            // Use "q" as a placeholder and Ajax Bootstrap Select will
            // automatically replace it with the value of the search query.
            data    : {
				"_token": "{{ csrf_token() }}",
                q: '<?php echo '{{{q}}}' ?>'
            }
        },
        locale        : {
            emptyTitle: 'Select and Begin Typing'
        },
        log           : 3,
        preprocessData: function (data) {
            var i, l = data.length, array = [];
            if (l) {
                for (i = 0; i < l; i++) {
                    array.push($.extend(true, data[i], {
                        text : data[i].name,
                        value: data[i].id,
                        data : {
                            subtext: data[i].description
                        }
                    }));
                }
            }
            // You must always return a valid array when processing data. The
            // data argument passed is a clone and cannot be modified directly.
            return array;
        }
    };

    $('.selectpicker').selectpicker().filter('.with-ajax').ajaxSelectPicker(options);
    $('select.after-init').append('<option value="neque.venenatis.lacus@neque.com" data-subtext="neque.venenatis.lacus@neque.com" selected="selected">Chancellor</option>').selectpicker('refresh');
    $('select').trigger('change');
</script>
    
<script>
 if ($('.status-switch label.user-invisible').hasClass('current-status')) {
            $('.status-indicator').addClass('right');
        }
        $('.status-switch label.user-invisible').on('click', function() {
          
          $("#pro").fadeIn();
          $("#customer").fadeOut();
        });
        $('.status-switch label.user-online').on('click', function() {
          $("#pro").fadeOut();
          $("#customer").fadeIn();
        });
</script>

    @endsection
@endsection