@extends('layouts.mvp_dash')

@section('page-css')
    <style>
    
    .status-icon.status-message, .status-message:after {
    background-color: #e53935;
}
.pros_holder {
    text-align: center;
}
.job-listing-details {
    text-align: center;
}
.grid-layout .job-listing-details {
    flex-grow: 1;
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    padding: 30px;
    padding-right: 40px;
    margin-bottom: 8px;
}
.grid-layout .job-listing {
    display: flex;
    flex-direction: column;
    justify-content: center;
    margin: 0 30px 30px 0;
    width: calc(85% * (1/2) - 30px);
    flex-direction: column;
}
.job_subtext{
    margin: 24px 0px;
}
    </style>
@endsection

@section('content')
 
@if ( Auth::user()->user_role == 'customer')

                
                <!-- Dashboard Headline -->
                <div class="dashboard-headline">
                    <h3> Projects</h3>
    
                    <!-- Breadcrumbs -->
                    <nav id="breadcrumbs" class="dark">
                        <ul>
                            <li><a href="{{route('home')}}">Home</a></li>
                            <li><a href="{{route('dashboard')}}">Dashboard</a></li>
                            <li>Projects</li>
                        </ul>
                    </nav>
                </div>
        
                <!-- Row -->
                <div class="row">
    
                    <!-- Dashboard Box -->
                    <div class="col-xl-12">

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
<div class="row">
    
@forelse ($projects as $item)
            @if($item->status < 2)
                    <div class="col-xl-6">
                            
                            <div class="listings-container margin-top-35">
                                    <div href="single-job-page.html" class="job-listing">
                    
                                        <!-- Job Listing Details -->
                                        <div class="job-listing-details">
                                           
                                            <!-- Details -->
                                            <div class="job-listing-description">
                                                <h4 class="job-listing-company">{{$item->sub_category_name}} </h4>
                                                <h3 class="job-listing-title">{{ $item->created_at->format('F d,Y') }}</h3>
                                                {{-- @php
                                                     $project_base = base64_encode($item->id*786);
                                                @endphp --}}
                                                @if ($item->status == 3)
                                                <div class="task-tags">
                                                        <span>Project Complete</span>
                                                    </div>
                                                @elseif($item->status == 4)
                                                <div class="task-tags">
                                                        <span>Project On Hold</span>
                                                    </div>
                                                    
                                                @endif
                                                    <a href="{{route ('project_update', base64_encode($item->id*786)) }}">Update</a>
                                            </div>
                                        </div>
                    
                                        <!-- Job Listing Footer -->
                                        <div class="job-listing-footer">
                                                <div class="pros_holder">
                                                    @forelse ($bidders as $bid)
                                                    @if ($item->id == $bid->project_id)                                                              
                                                      <div class="user-avatar status-message"><a href="{{route ('pro_p_profile', base64_encode($bid->pro_id*786)) }}"><img src="{{ url('/'). env('PROFILE_IMAGES_PATH') .$bid->profile_photo }}" alt=""></a></div>
                                                    @endif
                                                    @empty
                                                        
                                                    @endforelse
                                                      <br>
                                                      <br>
                                                        <a href="{{route ('project_bids', base64_encode($item->id*786)) }}" class="button ripple-effect"><i class="icon-material-outline-supervisor-account"></i>
                                                             View Quotes</a> 
                                                       <h4 > 
                                                                {{$item->sub_category_name}} are ready to complete your project.
                                                                </h4>
                                                        {{-- <a href="#" > Request Again</a>  --}}
                                                            </div> 
                                            <ul>
                                            </ul>
                                        </div>
                                    </div>	
                                </div>
                              
                    
                    </div>
                      
                    @endif
                    @empty
                        
                    @endforelse

@forelse ($projects as $item)
            @if($item->status >= 2)
                    <div class="col-xl-6">
                            
                            <div class="listings-container margin-top-35">
                                    <div href="single-job-page.html" class="job-listing">
                    
                                        <!-- Job Listing Details -->
                                        <div class="job-listing-details">
                                           
                                            <!-- Details -->
                                            <div class="job-listing-description">
                                                <h4 class="job-listing-company">{{$item->sub_category_name}} </h4>
                                                <h3 class="job-listing-title">{{ $item->created_at->format('F d,Y') }}</h3>
                                                {{-- @php
                                                     $project_base = base64_encode($item->id*786);
                                                @endphp --}}
                                                @if ($item->status == 2)
                                                <div class="task-tags">
                                                        <span>Pro Accepted</span>
                                                    </div>
                                                @elseif($item->status == 3)
                                                <div class="task-tags">
                                                        <span>Project Completed</span>
                                                    </div>
                                                @elseif($item->status == 4)
                                                <div class="task-tags">
                                                        <span>Project On Hold</span>
                                                    </div>
                                                    
                                                @endif
                                                    <a href="{{route ('project_update', base64_encode($item->id*786)) }}">Update</a>
                                            </div>
                                        </div>
                    
                                        <!-- Job Listing Footer -->
                                        <div class="job-listing-footer">
                                                <div class="pros_holder">
                                                        <a href="{{route ('project_bids', base64_encode($item->id*786)) }}" class="button ripple-effect"><i class="icon-material-outline-supervisor-account"></i>
                                                             View Quotes</a> 
                                                            </div> 
                                            <ul>
                                            </ul>
                                        </div>
                                    </div>	
                                </div>
                              
                    
                    </div>
                      
                    @endif
                    @empty
                        
                    @endforelse
                </div>

                    </div>
    
                </div>
                <!-- Row / End -->
    
    
    
@elseif ( Auth::user()->user_role == 'pros')

@endif
			

    @section('page-js')   
    @endsection

@endsection