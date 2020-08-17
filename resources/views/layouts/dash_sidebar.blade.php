<!-- Dashboard Sidebar

	================================================== -->

	<div class="dashboard-sidebar">

            <div class="dashboard-sidebar-inner" data-simplebar>

                <div class="dashboard-nav-container">

    

                    <!-- Responsive Navigation Trigger -->

                    <a href="#" class="dashboard-responsive-nav-trigger">

                        <span class="hamburger hamburger--collapse" >

                            <span class="hamburger-box">

                                <span class="hamburger-inner"></span>

                            </span>

                        </span>

                        <span class="trigger-title">Dashboard Navigation</span>

                    </a>

                    @if ( Auth::user()->user_role == 'customer')

            

                    {{-- <!-- customers  Navigation --> --}}

                    <div class="dashboard-nav">

                        <div class="dashboard-nav-inner">

    

                            <ul data-submenu-title="Main">

                                <li class="active"><a href="{{ route('dashboard') }}"><i class="icon-material-outline-dashboard"></i> Dashboard</a></li>

                                <li><a href="{{ route('dash_projects') }}"><i class="icon-material-outline-business-center"></i> Projects</a></li>

                                </ul>

                            

                            <ul data-submenu-title="Organize">

                                <li><a href="{{ route('customer_inbox') }}"><i class="icon-material-outline-question-answer"></i> Inbox</a></li>

                                <li><a href="{{ route('dash_saved_pros') }}"><i class="icon-material-outline-assignment"></i> My Favorite Pros</a></li>

                                {{-- <li><a href="{{ route('dash_notifications') }}"><i class="icon-material-outline-assignment"></i> Notifications</a></li> --}}

                            </ul>

    

                            <ul data-submenu-title="Account">

                                {{-- <li><a href="{{ route('dash_payments_history') }}"><i class="icon-material-outline-settings"></i> Payments History</a></li> --}}

                                <li><a href="{{ route('dash_my_accounts') }}"><i class="icon-feather-user"></i> My Account</a></li>

                                <li><a href="{{ route('switch_to_pro') }}"><i class="icon-material-outline-assignment"></i> Switch To Pro</a></li>
                                {{-- <li><a href="{{ route('dash_my_settings') }}"><i class="icon-material-outline-settings"></i> Settings</a></li> --}}

                                {{-- <li><a href="{{ route('dash_my_referals') }}"><i class="icon-material-outline-settings"></i> My Referals</a></li> --}}

                                <li><a href="{{ route('logout') }}"><i class="icon-material-outline-power-settings-new"></i> Logout</a></li>

                            </ul>

                            

                        </div>

                    </div>

                    {{-- <!-- customers  Navigation / End --> --}}

                   

                    @elseif ( Auth::user()->user_role == 'pro')



                    {{-- <!-- Pros  Navigation --> --}}

                    <div class="dashboard-nav">

                            <div class="dashboard-nav-inner">

        

                                <ul data-submenu-title="Main">

                                    <li class="active"><a href="{{ route('dashboard') }}"><i class="icon-material-outline-dashboard"></i> Dashboard</a></li>

                                    <li><a class="v_checker" href="{{ route('pro_requests') }}"><i class="icon-material-outline-business-center"></i> Requests</a></li>

                                </ul>





                                <ul data-submenu-title="Organize">

                                        <li><a class="v_checker" href="{{ route('pro_bids') }}"><i class="icon-material-outline-question-answer"></i> Bids</a></li>

                                        <li><a class="v_checker" href="{{ route('pro_services') }}"><i class="icon-feather-inbox"></i> Services</a></li>    

                                    <li><a href="#"><i class="icon-material-outline-settings"></i> Settings</a>

                                        <ul>

                                                {{-- <li><a class="v_checker" href="{{ route('dash_saved_pros') }}">Quotes</a></li> --}}

                                                {{-- <li><a class="v_checker" href="{{ route('dash_saved_pros') }}">Notifications</a></li> --}}

                                                <li><a class="v_checker" href="#">Reviews</a></li>

                                                {{-- <li><a class="v_checker" href="{{ route('dash_notifications') }}">Working Hours</a></li> --}}

                                                {{-- <li><a class="v_checker" href="{{ route('dash_payments_history') }}">Earnings</a></li>

                                                <li><a class="v_checker" href="{{ route('dash_my_accounts') }}">Payments History</a></li>        --}}

                                                <br>     

                                        </ul>	

                                    </li>

                                </ul>

        

                                <ul data-submenu-title="Account">

                                    <li><a href="{{ route('dash_my_accounts') }}"><i class="icon-feather-user"></i> My Account</a></li>
                                    <li><a href="{{ route('switch_to_cus') }}"><i class="icon-material-outline-assignment"></i> Switch To Customer</a></li>

                                    <li><a class="v_checker" href="{{ route('dash_my_profile') }}"><i class="icon-feather-users"></i> My Public Profile</a></li>

                                    <li><a href="{{ route('logout') }}"><i class="icon-material-outline-power-settings-new"></i> Logout</a></li>

                                </ul>

                                

                            </div>

                        </div>

                        {{-- <!-- Pros  Navigation / End --> --}}

                    @endif

                </div>

            </div>

        </div>

        <!-- Dashboard Sidebar / End -->