<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ !empty($title) ? $title : __('app.dashboard') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">



    <link href="{{ asset('dashui/dist/css/style.css') }}" rel="stylesheet">
    <!-- This page CSS -->

    @yield('page-css')

    <!-- Scripts -->

    <script type='text/javascript'>
        /* <![CDATA[ */
        var page_data = {!! pageJsonData() !!};
        /* ]]> */
    </script>

</head>
<body>
<div class="main-wrapper">
        <!-- ============================================================== -->
        <!-- Preloader - style you can find in spinners.css -->
        <!-- ============================================================== -->
        <div class="preloader">
            <div class="loader">
                <div class="loader__figure"></div>
                <p class="loader__label">loading Spark Backend</p>
            </div>
        </div>
        <!-- ============================================================== -->
        <!-- Preloader - style you can find in spinners.css -->
        <!-- ============================================================== -->
@yield('content')

</div>
    <!-- Scripts -->
    @yield('page-js')
     <!-- ============================================================== -->
    <!-- All Required js -->
    <!-- ============================================================== -->
    <script src="{{ asset('dashui/assets/libs/jquery/dist/jquery.min.js') }}"></script>
    <script src="{{ asset('dashui/dist/js/materialize.min.js') }}" ></script>
    <!-- ============================================================== -->
    <!-- This page plugin js -->
    <!-- ============================================================== -->
    <script>
    $('.tooltipped').tooltip();
    // ============================================================== 
    // Login and Recover Password 
    // ============================================================== 
    $('#to-recover').on("click", function() {
        $("#loginform").slideUp();
        $("#recoverform").fadeIn();
    });
    $(function() {
        $(".preloader").fadeOut();
    });
    </script>


</body>
</html>
