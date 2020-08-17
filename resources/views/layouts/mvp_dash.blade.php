<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @include('layouts.favicon')

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ !empty($title) ? $title : __('Ubuy Find nearby proffesionals easily') }}</title>
    <script src="{{ asset('mvp_ui/js/jquery-3.3.1.min.js') }}"></script>

    @yield('page-before-css')
    <link rel="stylesheet" href="{{ URL::asset('/mvp_ui/css/style.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('/mvp_ui/css/colors/blue.css') }}" rel="stylesheet">
    {{-- breaker --}}
    <link rel="stylesheet" href="{{ asset('/mvp_ui/css/bootstrap-modal.css') }}" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('/mvp_ui/css/dash_ui.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('/mvp_ui/vendor/ladda/dist/ladda-themeless.min.css') }}" rel="stylesheet">

    @yield('page-css')

</head>
<body class="gray">
@php
$user = Auth::user();
@endphp


<div id="wrapper">

    @include('layouts.dash_header')

    <div class="clearfix"></div>

    @yield('modal_content')

{{-- <!-- Dashboard Container --> --}}
<div class="dashboard-container">


    @include('layouts.dash_sidebar')


    {{-- <!-- Dashboard Content
	================================================== --> --}}
	<div class="dashboard-content-container" data-simplebar>
            <div class="dashboard-content-inner" >
   
                
    @yield('content')

    @include('layouts.dash_footer')

			

		</div>
	</div>
	{{-- <!-- Dashboard Content / End --> --}}
</div>
</div>

{{-- @if ( Auth::user()->user_role == 'customer')


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

@endif --}}
    <script src="{{ asset('mvp_ui/js/jquery-migrate-3.0.0.min.js') }}"></script>
    <script src="{{ asset('mvp_ui/js/mmenu.min.js') }}"></script>
    <script src="{{ asset('mvp_ui/js/tippy.all.min.js') }}"></script>
    <script src="{{ asset('mvp_ui/js/simplebar.min.js') }}"></script>
    <script src="{{ asset('mvp_ui/js/bootstrap-slider.min.js') }}"></script>
    <script src="{{ asset('mvp_ui/js/bootstrap-select.min.js') }}"></script>
    <script src="{{ asset('mvp_ui/js/snackbar.js') }}"></script>
    <script src="{{ asset('mvp_ui/js/clipboard.min.js') }}"></script>
    {{-- breaker --}}
    <script src="{{ asset('mvp_ui/js/bootstrap-modal.js') }}"></script>

    <script src="{{ asset('mvp_ui/js/counterup.min.js') }}"></script>
    <script src="{{ asset('mvp_ui/js/magnific-popup.min.js') }}"></script>
    <script src="{{ asset('mvp_ui/js/slick.min.js') }}"></script>
    <script src="{{ asset('mvp_ui/js/custom.js') }}"></script>
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
    @yield('page-js')

</body>
</html>



