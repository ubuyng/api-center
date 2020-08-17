@extends('layouts.mvp_dash')


@section('content')
 
@if ( Auth::user()->user_role == 'customer')

    <!-- Dashboard Headline -->
    <div class="dashboard-headline">
            <h3>Hello, {{ Auth::user()->first_name }}!</h3>
            <span>We are glad to see you again!</span>
    </div>

    <!-- Content -->
    <div class="row">

            <!-- Item -->
            <div class="col-lg-3 col-md-6">
                <div class="dashboard-stat color-1">
                    <div class="dashboard-stat-content"><h4>{{$projectCount}}</h4> <span>My Projects</span></div>
                    <div class="dashboard-stat-icon"><i class="icon-feather-briefcase"></i></div>
                </div>
            </div>

            <!-- Item -->
            <div class="col-lg-3 col-md-6">
                <div class="dashboard-stat color-2">
                    <div class="dashboard-stat-content"><h4>{{$completeCount}}</h4> <span>Completed Projects</span></div>
                    <div class="dashboard-stat-icon"><i class="icon-feather-archive"></i></div>
                </div>
            </div>

            
            <!-- Item -->
            <div class="col-lg-3 col-md-6">
                <div class="dashboard-stat color-3">
                    <div class="dashboard-stat-content"><h4>{{$favoriteCount}}</h4> <span> My Favorite Pros</span></div>
                    <div class="dashboard-stat-icon"><i class="icon-line-awesome-users"></i></div>
                </div>
            </div>

            <!-- Item -->
            <div class="col-lg-3 col-md-6">
                <div class="dashboard-stat color-4">
                    <div class="dashboard-stat-content"><h4>0</h4> <span>Referals</span></div>
                    <div class="dashboard-stat-icon"> <img src="mvp_ui/images/icons/naira_icon.svg" alt="" style="width: 75px;"> </div>
                </div>
            </div>
        </div>

        <!-- Row -->
        <div class="row">

            <!-- Dashboard Box -->
            <div class="col-xl-12">
                    <div class="dashboard-box margin-top-0">

                        <!-- Headline -->
                        <div class="headline">
                            <h3><i class="icon-material-outline-business-center"></i> My Projects</h3>
                        </div>

                        <div class="content">

    <!-- no projects -->
    @if ($projects->isEmpty())
        
                    <div class="no_projects">
                                <img src="images/icons/no_project_icon.svg" alt="">
                            <h3>You don't have any active project</h3>
                        </div> 

    <div class="col-xl-12">
                <div class="section-headline centered margin-top-0 margin-bottom-45">
                    <h3>Explore Ubuy</h3>
                </div>
            </div>

            <div class="row explore_dash">
                @forelse ($home_cat as $cat)
                <div class="col-xl-3 col-md-6">
                        <a href="{{ route('category', $cat->slug) }}" class="photo-box small" data-background-image="{{ url('/'). env('BACKEND_IMAGES_PATH') .$cat->image }}">
                                <div class="photo-box-content">
                                    <h3>{{$cat->name}}</h3>
                                    <span>600</span>
                                </div>
                            </a>
                    </div>
                @empty
                    
                @endforelse
            
            
            </div>
            @endif

    <!-- no projects ends here -->
    <ul class="dashboard-box-list">
                            @forelse ($projects as $item)
                                <li>
                                    <div class="job-listing">

                                        <div class="job-listing-details">
                                            <div class="job-listing-description">
                                                <h3 class="job-listing-title"><a href="{{route ('project_bids', base64_encode($item->id*786)) }}">{{$item->sub_category_name}}</a> <span class="dashboard-status-button green">Pending Approval</span></h3>
                                                <div class="job-listing-footer">
                                                    <ul>
                                                        <li> See the list of {{$item->sub_category_name}} Pros that match your project details</li>
                                                        <li><i class="icon-material-outline-date-range"></i> {{ $item->created_at->format('F d,Y') }}</li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="buttons-to-right always-visible">
                                    <a href="{{route ('project_bids', base64_encode($item->id*786)) }}" class="button ripple-effect"><i class="icon-material-outline-supervisor-account"></i> Manage Bids</a> 
                                    </div>
                                </li>
                                @empty
                            </ul>
                                
                            @endforelse
                        </div>
                    </div>
                </div>
        </div>
        <!-- Row / End -->
@elseif ( Auth::user()->user_role == 'pro')
 <!-- Dashboard Headline -->

     <!-- Dashboard Headline -->
     <div class="dashboard-headline">
         <div class="col-xl-10">

                @if ($verify_status == false)
             <div class="notification error notverify_notice">
                 <p>
                 @if (Auth::user()->admin_message == true)

                     {{Auth::user()->admin_message}}
                 @else
                     Please complete Ubuy verification process to unlock full access to our services
                @endif
                     <button id="verify_account" class="button dark"><i class="icon-feather-unlock"></i> Verify</button>
                    </p>
                </div>
                @elseif ($verify_status == 1)
             <div class="notification warning notverify_notice">
                 <p>
                    <Strong>Hello, {{Auth::user()->first_name}}</Strong> Please wait for your account to be approved  
                </p>
                </div>
                @endif
        </div>
    </div>


    <div class="row">

        <!-- Item -->
        <div class="col-lg-3 col-md-6">
            <div class="dashboard-stat color-1">
                <div class="dashboard-stat-content"><h4>{{$jobsCount}}</h4> <span>My Jobs</span></div>
                <div class="dashboard-stat-icon"><i class="icon-feather-briefcase"></i></div>
            </div>
        </div>

        <!-- Item -->
        <div class="col-lg-3 col-md-6">
            <div class="dashboard-stat color-2">
                <div class="dashboard-stat-content"><h4>{{$bidsCount}}</h4> <span>Leads</span></div>
                <div class="dashboard-stat-icon"><i class="icon-feather-users"></i></div>
            </div>
        </div>

        
        <!-- Item -->
        <div class="col-lg-3 col-md-6">
            <div class="dashboard-stat color-3">
                <div class="dashboard-stat-content"><h4>{{$servicesCount}}</h4> <span> Services</span></div>
                <div class="dashboard-stat-icon"><i class="icon-feather-inbox"></i></div>
            </div>
        </div>

        <!-- Item -->
        <div class="col-lg-3 col-md-6">
            <div class="dashboard-stat color-4">
                <div class="dashboard-stat-content"><h4>0</h4> <span>Reviews</span></div>
                <div class="dashboard-stat-icon"> <i class="icon-line-awesome-star"></i> </div>
            </div>
        </div>
    </div>
<!-- Row -->
<div class="row">

        <!-- Dashboard Box -->
        <div class="col-xl-12">
            <div class="dashboard-box">
                <div class="headline">
                    <h3><i class="icon-material-outline-business-center"></i> Recent Tasks Requests base on your services</h3>
                    {{-- <button class="mark-as-read ripple-effect-dark" data-tippy-placement="left" title="Mark all as read">
                            <i class="icon-feather-check-square"></i>
                    </button> --}}
                </div>
                <div class="content">
                
                    {{-- <div class="notify-box ">
                        <div class="switch-container">
                            <label class="switch"><input type="checkbox"><span class="switch-button"></span><span class="switch-text">Turn on email alerts</span></label>
                        </div>

                        <div class="sort-by">
                            <span>Sort by:</span>
                            <select class="selectpicker hide-tick">
                                <option>Relevance</option>
                                <option>Newest</option>
                                <option>Oldest</option>
                            </select>
                        </div>
                    </div> --}}
			
                    @if ($pro_services->isEmpty())
			<!-- Tasks Container -->
			<div class="tasks-list-container compact-list margin-top-35">              
                <div class="no_projects">
                    <img src="/mvp_ui/images/icons/pro_services.svg" alt="">
                    <h3>List your services and start getting thousands of offers</h3>
                    <a href="{{route('add_services')}}" class="button dark">Add services</a>
                </div> 

			</div>
                @else
                <div class="tasks-list-container compact-list margin-top-35">

                @forelse ($available_request as $proj)
                    
              @if ($proj->user_id == Auth::User()->id)
                  
            
				<!-- Task -->
				<a href="{{route('project_chat',base64_encode($proj->id*786))}}" class="task-listing v_checker">

					<!-- Job Listing Details -->
					<div class="task-listing-details">

						<!-- Details -->
						<div class="task-listing-description">
							<h3 class="task-listing-title">{{$proj->sub_category_name}}</h3>
							<ul class="task-icons">
								<li><i class="icon-material-outline-location-on"></i> {{$proj->city}}</li>
							</ul>
							<p class="task-listing-text">{{$proj->project_message}}</p>
							<div class="task-tags">
                            <span><i class="icon-material-outline-location-city"></i>  State: {{$proj->state}}</span>
                            <span>Date: {{date('d-m-Y', strtotime($proj->created_at)) }}</span>
                            <span>Time: {{date('H-i', strtotime($proj->created_at)) }}</span>
							</div>
						</div>

					</div>

					<div class="task-listing-bid">
						<div class="task-listing-bid-inner">
							<div class="task-offers">
								{{-- <strong>Naira 75</strong>
								<span>Fixed Price</span> --}}
							</div>
							<span class="button dark button-sliding-icon ripple-effect">View Details <i class="icon-material-outline-arrow-right-alt"></i></span>
						</div>
					</div>
                </a>
                @endif
                @empty
                <div class="tasks-list-container compact-list margin-top-35">              
                    <div class="no_projects">
                        <img src="/mvp_ui/images/icons/pro_services.svg" alt="">
                        <h3>List your services and start getting thousands of offers</h3>
                        <a href="{{route('add_services')}}" class="button dark">Add services</a>
                    </div> 
    
                </div>
                @endforelse

			</div>
            <!-- Tasks Container / End -->
            <a href="#" class=" v_checker button full-width dark">View More</a>
                @endif
               

                </div>
            </div>
        </div>

      

    </div>
    <!-- Row / End -->


@endif
			

    @section('page-js')  
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
    @endsection

@endsection