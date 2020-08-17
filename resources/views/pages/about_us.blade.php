<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ !empty($title) ? $title : __('app.dashboard') }}</title>

    @yield('page-before-css')
    <link rel="stylesheet" href="{{ asset('/mvp_ui/css/style.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('/mvp_ui/css/colors/blue.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('/mvp_ui/css/dash_ui.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('/mvp_ui/css/bootstrap-modal.css') }}" rel="stylesheet">
    <style>
    .banner-headline {
    max-width: 100%;
    text-align: center;
}
.about1{
  padding: 5%;
}
.img-banner {
    /* position: absolute; */
    height: 100%;
    width: 100%;
  }
  .blog-compact-item:before {
    content: "";
    top: 0;
    position: absolute;
    height: 100%;
    width: 100%;
    z-index: 9;
    border-radius: 4px;
    background:none;
    transition: .4s;
}
.blog-compact-item {
    background: #fff;
    box-shadow: 0 3px 10px rgba(0, 0, 0, .2);
    border-radius: 4px;
    height: 100%;
    display: block;
    position: relative;
    background-size: cover;
    background-repeat: no-repeat;
    background-position: 50%;
    height: 100%;
    z-index: 100;
    cursor: default;
    transition: .4s;
}
.blog-compact-item-content h3 {
    color: #333;
    font-size: 20px;
    padding: 5px 0;
    font-weight: 500;
    margin: 2px 0 0;
    line-height: 30px;
    text-align: center;
}
.blog-compact-item-content p {
    font-size: 16px;
    font-weight: 300;
    display: inline-block;
    color: #333;
    margin: 7px 0 0;
    text-align: center;

}
.blog-compact-item-content {
    position: unset;
    bottom: 32px;
    left: 0;
    padding: 0 34px;
    width: 100%;
    z-index: 50;
    box-sizing: border-box;
    text-align: center;
}
.blog-compact-item img {
    object-fit: cover;
    height: 100%;
    width: 33%;
    border-radius: 4px;
    margin-top: 21px;
}
    </style>
    @yield('page-css')

</head>
<body>
@php
$user = Auth::user();
@endphp


<div id="wrapper" class="wrapper-with-transparent-header">

    @include('layouts.header')

    <div class="clearfix"></div>

    <div class="intro-banner dark-overlay" data-background-image="mvp_ui/images/about_bg.jpg">

      <!-- Transparent Header Spacer -->
      <div class="transparent-header-spacer" style="height: 82px;"></div>
    
      <div class="container">
        
        <!-- Intro Headline -->
        <div class="row">
          <div class="col-md-12">
            <div class="banner-headline">
              <h3>
                <strong>Nigeria's largest professionals platform...</strong>
                <br>
                <span>Connecting Pros and Customers nationwide</span>
              </h3>
            </div>
          </div>
        </div>
        
   
        <!-- Stats -->
        <div class="row">
          <div class="col-md-6 offset-md-4">
            <ul class="intro-stats margin-top-45 hide-under-992px">
              <li>
                <strong class="counter">3,543</strong>
                <span>Projects Posted</span>
              </li>
              <li>
                <strong class="counter">2032</strong>
                <span>Professionals</span>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>


<div class="container-fluid">
  <div class="about1">
  <div class="row">

    <div class="col-md-6">
      <div class="banner-headline">

        <h3><strong>About Us</strong></h3>
      </div>
      <p> Ubuy Nigeria Global Trading Services International Ltd (UbuyNG).
         is an e-commerce company headquartered in the 
         Federal Capital Territory, Abuja, Nigeria.
          It provides platforms that connect clients with professionals 
          and artisans within their vicinity offering the services they 
          require to execute their projects at zero costs and no commission
           via its website and mobile applications. Our mission is to maintain
             platforms for professionals and skilled   artisans   within  
              Nigeria   that  gives   them   utmost  accessibility  
               andvisibility to their potential clients with just a few 
               clicks.
      </p>
    </div>
    <div class="col-md-6">
      <div class="img-banner" >
        <img src="mvp_ui/images/about_cap.png" alt="" srcset="">
      </div>
    </div>

    </div>
  </div>
</div>
<div class="container-fluid">
  <div class="about1">
  <div class="row">

    <div class="col-md-6">
        <div class="img-banner" >
            <img src="mvp_ui/images/about_2.png" alt="" srcset="">
          </div>
     
    </div>
    <div class="col-md-6">
    
          <p>Taking a cursory look into the slow pace with which 
            the diversity of Nigeria’s workforce is appreciated,
             we thought of various means to which we can limit the struggle 
             associated with unemployment in the country and how best we can 
             help persons especially our youths out of the cold embrace of
              poverty, even while they are not gainfully employed. 
                  </p>
                  <p>
                      It is to this
                      end that we thought it would be a nice innovation to create a platform
                       for professionals who have limited or no visibility for their services,
                       those   that   are   not   gainfully   employed   but   have   services 
                         to   offer,   and “drowning” professionals. With the hope of giving 
                         hope to them, and to the nation’s economy.
                  </p>
    </div>
    </div>
  </div>
</div>
<div class="container-fluid">
  <div class="about1">
  <div class="row">

    <div class="col-md-6">
        <p>In 2018, these thoughts became a reality. This platform became a reality that eases   the   stress   encountered   by   Nigerians  
            while   looking   for   reliable professionals to handle a wide range of jobs for them. 
           We created a platform that   offered   an   opportunity   for   professional   artists,   cosmeticians,
            beauticians,   writers,   event   planners,   office   and   house   cleaners,   interior designers, ICT experts, web developers,
             gardeners, home tutors, etc., to be easily seen by persons that seek the services they provide.
                 </p>
                 <p>
                 <a href="{{route('home')}}"> UbuyNG’s</a> satisfaction lies in the satisfaction our customers derive from the
                      services which they may have offered or received on or through our website.
                      Through the comfort created by our platforms, we’ve created an avenue for individuals in need of professional 
                      services but don’t have the time to searchfor people offering the services 
                      they need or endlessly browse the internet insearch for professional help. 
                      We are devoted to giving you peace of mind aswell as creating opportunities for everyone.

                 </p>
        
    </div>
    <div class="col-md-6">
        <div class="img-banner" >
            <img src="mvp_ui/images/about_3.png" alt="" srcset="">
          </div>
        
    </div>
    </div>
  </div>
</div>
<div class="container-fluid">
  <div class="about1">
  <div class="row">

   
    <div class="col-md-6">
        <div class="img-banner" >
            <img src="mvp_ui/images/about_4.png" alt="" srcset="">
          </div>
        
    </div>
    <div class="col-md-6">
        <p>Furthermore, <a href="{{route('home')}}"> UbuyNG</a> is a stepping stone for adding an extra source of income
             for   those   who   have   skills   but   are   on   a   “9-5”  
              appointment   to meaningfully engage in the skills they have 
              acquired over the years. Also,the platform offers a 
              much-appreciated break from the anxiety suffered by youths 
              while trying to break through from the strangulation of 
              the harsh economy, putting a stop from pains takingly 
              trying to reach out to potential clients, to no avail. 
                 <p>
                    It only gets easier: 
                    Register – Login – Build your profile – work to get yourself 
                    known – Hope for potential convertible clients – get one or some – 
                    do anexcellent job – get paid – rating increase – attract more
                     customer members.</p>
                     <p><a href="{{route('home')}}"> UbuyNG</a> also serves as a sweet relief from 
                        the burden placed on the economy and the government, gainfully 
                        converting the untapped human capital into a more resourceful unit 
                        that profits the country’s per capital index. </p>

                 </p>
        
    </div>
    </div>
  </div>
</div>
<div class="section padding-top-65 padding-bottom-50">
    <div class="container">
      <div class="row">
        <div class="col-xl-12">
       
          <div class="row">
            <!-- Blog Post Item -->
            <div class="col-xl-4">
                <div class="blog-compact-item-container">
                    <div class="blog-compact-item">
                  <div class="blog-compact-item-content">
                      <img src="/mvp_ui/images/icons/about_values.svg" alt="">

                      <h3>Our responsibility</h3>
                      <p>We are devoted to giving you peace of mind as well as creating opportunities for all skilled-workers</p>
                    </div>
                </div>
              </div>
            </div>
            <!-- Blog post Item / End -->
  
            <!-- Blog Post Item -->
            <div class="col-xl-4">
                <div class="blog-compact-item-container">
                    <div class="blog-compact-item">
                  <div class="blog-compact-item-content">
                      <img src="/mvp_ui/images/icons/about_mission.svg" alt="">
                      <h3>Our mission</h3>
                      <p>To maintain platforms for professionals andskilled artisans within Nigeria that gives them utmost 
                        accessibility and visibility to their potential clients with just a few clicks</p>
                      </div>
                    </div>
                  </div>
                </div>
                <!-- Blog post Item / End -->
                
                <!-- Blog Post Item -->
                <div class="col-xl-4">
                  <div class="blog-compact-item-container">
                    <div class="blog-compact-item">
                      <div class="blog-compact-item-content">
                          <img src="/mvp_ui/images/icons/about_responsibility.svg" alt="">

                        <h3>Our core values</h3>
                    <p>Our core values are attached to every aspect of our business and this provides a solid foundation for everything UbuyNG represents.</p>
                   <p>Client-focused - Committed to Excellence - Rich Diversity - Innovation - Collaboration - Purpose-driven - Community support</p>
                  </div>
                </div>
              </div>
            </div>
            <!-- Blog post Item / End -->
          </div>
        </div>
      </div>
    </div>
  </div>
  
@include('layouts.footer')
</div>




      <script src="{{ asset('/mvp_ui/js/jquery-3.3.1.min.js') }}"></script>
    <script src="{{ asset('mvp_ui/js/jquery-migrate-3.0.0.min.js') }}"></script>
    <script src="{{ asset('mvp_ui/js/mmenu.min.js') }}"></script>
    <script src="{{ asset('mvp_ui/js/tippy.all.min.js') }}"></script>
    <script src="{{ asset('mvp_ui/js/simplebar.min.js') }}"></script>
    <script src="{{ asset('mvp_ui/js/bootstrap-slider.min.js') }}"></script>
    <script src="{{ asset('mvp_ui/js/bootstrap-select.min.js') }}"></script>
    <script src="{{ asset('mvp_ui/js/snackbar.js') }}"></script>
    <script src="{{ asset('mvp_ui/js/clipboard.min.js') }}"></script>
    <script src="{{ asset('mvp_ui/js/counterup.min.js') }}"></script>
    <script src="{{ asset('mvp_ui/js/magnific-popup.min.js') }}"></script>
    <script src="{{ asset('mvp_ui/js/slick.min.js') }}"></script>
    <script src="{{ asset('mvp_ui/js/custom.js') }}"></script>
    <script src="{{ asset('mvp_ui/js/bootstrap-modal.js') }}"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script src="https://wchat.freshchat.com/js/widget.js"></script>
    <script>
            window.fcWidget.init({
              token: "74d37aa5-94c1-4a90-8e01-a575094aa69a",
              host: "https://wchat.freshchat.com"
            });
           </script>
    @yield('page-js')

</body>
</html>
