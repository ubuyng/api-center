@extends('layouts.mvp_dash')

@section('page-css')
    <style>
   </style>
@endsection

@section('content')
 
@if ( Auth::user()->user_role == 'customer')

                
                <!-- Dashboard Headline -->
                <div class="dashboard-headline">
                    <h3> My Faborite Pros</h3>
    
                    <!-- Breadcrumbs -->
                    <nav id="breadcrumbs" class="dark">
                        <ul>
                            <li><a href="{{route('home')}}">Home</a></li>
                            <li><a href="{{route('dashboard')}}">Dashboard</a></li>
                            <li>Saved Pros</li>
                        </ul>
                    </nav>
                </div>
        
                <!-- Row -->
                <div class="row justify-content-center">

                	<!-- Dashboard Box -->
				<div class="col-xl-12">
                        <div class="dashboard-box">
    
                            <!-- Headline -->
                            <div class="headline">
                                <h3><i class="icon-material-outline-face"></i> Bookmarked Pros</h3>
                            </div>
    
                            <div class="content">
                                <ul class="dashboard-box-list">
                                   @forelse ($pros as $item)
                                       
                                   <li>
                                       <!-- Overview -->
                                       <div class="freelancer-overview">
                                           <div class="freelancer-overview-inner">
   
                                               <!-- Avatar -->
                                               <div class="freelancer-avatar">
                                                   <a href="{{route ('pro_p_profile', base64_encode($item->id*786)) }}"><img src="{{ url('/'). env('PROFILE_IMAGES_PATH') .$item->profile_photo }}" alt=""></a>
                                               </div>
   
                                               <!-- Name -->
                                               <div class="freelancer-name">
                                                   <h4><a href="{{route ('pro_p_profile', base64_encode($item->id*786)) }}">{{$item->business_name}} </a></h4>
                                                   @forelse ($services as $service)
                                                   <span>{{$service->service_name}}
                                                           @if ($loop->last)
                       
                                                           @else
                                                           +
                                                           @endif

                                                   </span>
                                                  
							@empty
								
							@endforelse
                                               </div>
                                           </div>
                                       </div>
                                   </li>
                                   @empty
                                        <!-- Dashboard Box -->
                                            <!-- no projects -->
                                        <div class="no_projects">
                                                <img src="/mvp_ui/images/icons/no_project_icon.svg" alt="">
                                            <h3>You don't have any save pros</h3>
                                        </div> 
                                   @endforelse
                                  
                                </ul>
                            </div>
                        </div>
                    </div>
    
                </div>

@elseif ( Auth::user()->user_role == 'pros')

@endif
			

    @section('page-js')   
    @endsection

@endsection