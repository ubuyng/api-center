<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @yield('page-meta')
    <meta name="robots" content="index, follow">
    <meta http-equiv="Content-Type" content="text/html" charset="utf-8">
    <meta name="language" content="English">
    <meta name="google-site-verification" content="8GccXQf3Q1PBUD884-_MGteMiu3uZ-TAwKZdwJe4ov8" />
    <meta name="msvalidate.01" content="FE7D2EF3CBDF084A87050B2D9CE4C697" />

    @include('layouts.favicon')
    <title>{{ !empty($title) ? $title : 'Welcome to Ubuy.ng' }}</title>
<!-- BEGIN PRIVY WIDGET CODE -->
<script type='text/javascript'> var _d_site = _d_site || 'F3B708C85B5B7FEB6CC90214'; </script>
<script src='https://widget.privy.com/assets/widget.js'></script>
<!-- END PRIVY WIDGET CODE -->
    @yield('page-before-css')
    <link rel="stylesheet" href="{{ asset('/mvp_ui/css/style.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('/mvp_ui/css/colors/blue.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('/mvp_ui/css/dash_ui.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('/mvp_ui/css/bootstrap-modal.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('/mvp_ui/vendor/ladda/dist/ladda-themeless.min.css') }}" rel="stylesheet">

<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-138321757-1"></script>
<script>
 window.dataLayer = window.dataLayer || [];
 function gtag(){dataLayer.push(arguments);}
 gtag('js', new Date());

 gtag('config', 'UA-138321757-1');
</script>

<!-- Facebook Pixel Code -->
<script>
    !function(f,b,e,v,n,t,s)
    {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
    n.callMethod.apply(n,arguments):n.queue.push(arguments)};
    if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
    n.queue=[];t=b.createElement(e);t.async=!0;
    t.src=v;s=b.getElementsByTagName(e)[0];
    s.parentNode.insertBefore(t,s)}(window,document,'script',
    'https://connect.facebook.net/en_US/fbevents.js');
     fbq('init', '471792220246624'); 
    fbq('track', 'PageView');
    </script>
    <noscript>
     <img height="1" width="1" 
    src="https://www.facebook.com/tr?id=471792220246624&ev=PageView
    &noscript=1"/>
    </noscript>
    <!-- End Facebook Pixel Code -->
    @yield('page-css')

</head>
<body>
@php
$user = Auth::user();
@endphp


<div id="wrapper">

    @include('layouts.header')

    <div class="clearfix"></div>

    @yield('content')

    @yield('modal_content')

@include('layouts.footer')
</div>


      <script src="{{ asset('/mvp_ui/js/jquery-3.3.1.min.js') }}"></script>
      
{{-- @auth
    
@if ( Auth::user()->user_role == 'customer')


    @elseif( Auth::user()->user_role == 'pro')
        <script>
        // swal("Hello world!");
        @if ($verify_status == 0)
                $('.v_checker').click(function(e) {
                     e.preventDefault(); 
                     swal({
                            title: "Please upload a verification picture",
                            text: "Ready to start earning? upload a verification picture to unlock your account",
                            icon: "error",
                            });
                     }); 
            $('#verify_account').click(function(){
                window.location  = '{{route('pro_verify')}}';
                });
                @elseif ($verify_status == 1)

                $('.v_checker').click(function(e) {
                     e.preventDefault(); 
                     swal({
                            title: "Waiting for approval",
                            text: "Please with while your account is being approved!!",
                            icon: "warning",
                            });
                     }); 
            @endif
            
        </script>
            @endif
            @endauth --}}
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
    <script src="{{ asset('mvp_ui/vendor/ladda/dist/spin.min.js') }}"></script>
    <script src="{{ asset('mvp_ui/vendor/ladda/dist/ladda.min.js') }}"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script src="https://wchat.freshchat.com/js/widget.js"></script>
    <script>
            window.fcWidget.init({
              token: "74d37aa5-94c1-4a90-8e01-a575094aa69a",
              host: "https://wchat.freshchat.com"
            });
           </script>
           <script type="application/ld+json">
{
  "@context": "https://schema.org/",
  "@type": "WebSite",
  "name": "UbuyNg",
  "url": "https://ubuy.ng/",
  "potentialAction": {
    "@type": "SearchAction",
    "target": "https://ubuy.ng/{search_term_string}",
    "query-input": "required name=search_term_string"
  }
}
</script>


    @yield('page-js')

</body>
</html>
