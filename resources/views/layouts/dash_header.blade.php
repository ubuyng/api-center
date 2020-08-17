

   <header id="header-container" class="fullwidth dashboard-header not-sticky">



        <!-- Header -->

        <div id="header">

            <div class="container">

                

                <!-- Left Side Content -->

                <div class="left-side">

                        

                    <!-- Logo -->

                    <div id="logo">

                        <a href="{{route('home')}}"><img src="/mvp_ui/images/logo.png" alt=""></a>

                    </div>

    

                    <!-- Main Navigation -->

                    <nav id="navigation">

                        <ul id="responsive">

                        @if ( Auth::user()->user_role == 'customer')

                            <li><a href="{{route('home')}}" >Home</a></li>

                            <li><a href="{{route('explore_ubuy')}}">Explore Ubuy</a></li>

                            <li><a href="{{route('htw')}}">How It Works</a></li>

                            <li><a href="{{route('dash_projects')}}">Projects</a></li>

                            <li><a href="{{route('customer_inbox')}}">Inbox</a></li>

                        @elseif ( Auth::user()->user_role == 'pro')

                            <li><a class="v_checker" href="{{route('pro_bids')}}" >My Bids</a></li>

                            <li><a class="v_checker" href="{{route('pro_requests')}}">Requests</a></li>

                            <li><a class="v_checker" href="{{route('pro_services')}}">Services</a></li>

                            {{-- <li><a class="v_checker" href="{{route('pro_reviews')}}">Reviews</a></li> --}}



                        @endif

    

                        </ul>

                    </nav>

                    <div class="clearfix"></div>

                    <!-- Main Navigation / End -->

                    

                </div>

                <!-- Left Side Content / End -->

    

    

                <!-- Right Side Content / End -->

                <div class="right-side">

    

                    <!--  User Notifications -->

                    {{-- <div class="header-widget hide-on-mobile">

                        

                       <!-- Notifications -->

                        <div class="header-notifications">

    

                            <!-- Trigger -->

                            <div class="header-notifications-trigger">

                                <a href="#"><i class="icon-feather-bell"></i>

                                    <!-- <span>4</span> -->

                                </a>

                            </div>

    

                            <!-- Dropdown -->

                            <div class="header-notifications-dropdown">

    

                                <div class="header-notifications-headline">

                                    <h4>Notifications</h4>

                                </div>

    

                                <div class="header-notifications-content">

                                    <div class="header-notifications-scroll" data-simplebar>

                                        <img class="no_chat_icon" src="/mvp_ui/images/icons/no_notification_icon.svg" alt="" srcset="">

                                        <h3 class="no_notify_text">No Notification available</h3>

                                                                     

                                        <ul>

                                            <li class="notifications-not-read">

                                                <a href="dashboard-manage-candidates.html">

                                                    <span class="notification-icon"><i class="icon-material-outline-group"></i></span>

                                                    <span class="notification-text">

                                                        <strong>Michael Shannah</strong> applied for a job <span class="color">Full Stack Software Engineer</span>

                                                    </span>

                                                </a>

                                            </li>

                                            </ul> 

                                    </div>

                                </div>

    

                            </div>

    

                        </div>

                        

                        <!-- Messages -->

                        <div class="header-notifications">

                            <div class="header-notifications-trigger">

                                <a href="#"><i class="icon-feather-mail"></i>

                                    <!-- <span>3</span> -->

                                </a>

                            </div>

    

                            <!-- Dropdown -->

                            <div class="header-notifications-dropdown">

    

                                <div class="header-notifications-headline">

                                    <h4>Messages</h4>

                                

                                </div>

    

                                <div class="header-notifications-content">

                                    <div class="header-notifications-scroll" data-simplebar>

                                            <img class="no_chat_icon" src="/mvp_ui/images/icons/no_message_icon.svg" alt="" srcset="">

                                        <h3 class="no_notify_text">No Messages available</h3>

                                        <!-- <ul>

                                            <li class="notifications-not-read">

                                                <a href="dashboard-messages.html">

                                                    <span class="notification-avatar status-online"><img src="/mvp_ui/images/user-avatar-small-03.jpg" alt=""></span>

                                                    <div class="notification-text">

                                                        <strong>David Peterson</strong>

                                                        <p class="notification-msg-text">Thanks for reaching out. I'm quite busy right now on many...</p>

                                                        <span class="color">4 hours ago</span>

                                                    </div>

                                                </a>

                                            </li>

                                        </ul> -->

                                    </div>

                                </div>

    

                                <a href="dashboard-messages.html" class=" v_checker header-notifications-button ripple-effect button-sliding-icon">View All Messages<i class="icon-material-outline-arrow-right-alt"></i></a>

                            </div>

                        </div>

                        

                    </div> --}}

                    <!--  User Notifications / End -->

    

                    <!-- User Menu -->

                    <div class="header-widget">

    

                        <!-- Messages -->

                        <div class="header-notifications user-menu">

                            <div class="header-notifications-trigger">

                                    @if (Auth::user()->image == null)

                                    <a href="#"><div class="user-avatar status-online"><img src="/mvp_ui/images/icons/user_icon.svg" alt=""></div></a>

                                    @else

                                    <a href="#"><div class="user-avatar status-online"><img src="{{ url('/'). env('PROFILE_IMAGES_PATH').Auth::user()->image}}" alt=""></div></a>                                        

                                    @endif



                            </div>

    

                            <!-- Dropdown -->

                            <div class="header-notifications-dropdown">

    

                                <!-- User Status -->

                                <div class="user-status">

    

                                    <!-- User Name / Avatar -->

                                    <div class="user-details">

                                        @if (Auth::user()->image == null)

                                        <div class="user-avatar status-online"><img src="/mvp_ui/images/icons/user_icon.svg" alt=""></div>

                                        

                                        @else

                                    <div class="user-avatar status-online"><img src="{{ url('/'). env('PROFILE_IMAGES_PATH').Auth::user()->image}}" alt=""></div>

                                            

                                        @endif

                                        <div class="user-name">

                                                {{ Auth::user()->first_name }} {{ Auth::user()->last_name }}

                                                @if ( Auth::user()->user_role == 'customer')

                                                        <span>Customer</span> 

                                                @endif

                                                @if ( Auth::user()->user_role == 'pro')

                                                        <span>Professional</span> 

                                                @endif

                                        </div>

                                    </div>

                                    

                                    @if ( Auth::user()->user_role == 'pro')

                                <a href="{{route('switch_to_cus')}}">
                                               
                                    <div class="status-switch" id="snackbar-user-status">

                                        <label class="user-online current-status">Pro</label>

                                        <label class="user-invisible">Customer</label>

                                        <span class="status-indicator" aria-hidden="true"></span>

                                    </div>	 
                                    </a> 
                                    @elseif ( Auth::user()->user_role == 'customer')

                                            
                                    <a href="{{route('switch_to_pro')}}">

                                    <div class="status-switch" id="snackbar-user-status">

                                        <label class="user-online current-status">Customer</label>

                                        <label class="user-invisible">Pro</label>

                                        <span class="status-indicator" aria-hidden="true"></span>

                                    </div>	 
                                    </a>



                                    @endif 

                            </div>

                            

                            <ul class="user-menu-small-nav">

                                <li><a href="{{route('dashboard')}}"><i class="icon-material-outline-dashboard"></i> Dashboard</a></li>

                                {{-- <li><a href="{{route('dash_my_settings')}}"><i class="icon-material-outline-settings"></i> Settings</a></li> --}}

                                <li><a href="{{ route('logout') }}"><i class="icon-material-outline-power-settings-new"></i> Logout</a></li>

                            </ul>

    

                            </div>

                        </div>

    

                    </div>

                    <!-- User Menu / End -->

    

                    <!-- Mobile Navigation Button -->

                    <span class="mmenu-trigger">

                        <button class="hamburger hamburger--collapse" type="button">

                            <span class="hamburger-box">

                                <span class="hamburger-inner"></span>

                            </span>

                        </button>

                    </span>

    

                </div>

                <!-- Right Side Content / End -->

    

            </div>

        </div>

        <!-- Header / End -->

    

    </header>



   

    