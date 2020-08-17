@extends('layouts.mvp_dash')

@section('page-css')
    <style>
    .freelancer-overview .freelancer-avatar img {
    width: 48px;
    border-radius: 50%;
    cursor: pointer;
    height: 48px;
}

.status_icon:after {
    position: relative;
    content: "";
    height: 12px;
    width: 12px;
    background-color: silver;
    display: block;
    border: 2px solid #fff;
    box-shadow: 0 2px 3px rgba(0, 0, 0, .3);
    border-radius: 50%;
}
.freelancer-detail-item {
    margin-top: 10px;
}
.status_icon:after {
    background-color: #38b653;
}
ul.dashboard-box-list>li {
    padding: 4px 10px;
}
.dashboard-box .freelancer-overview {
    padding: 6px 0;
}
span.msg_date {
    color: green;
    background-color: aliceblue;
    padding: 2px;
    margin-top: 13px;
    border-radius: 5px;
}
    </style>
@endsection

@section('content')
 
@if ( Auth::user()->user_role == 'pro')

                
                <!-- Dashboard Headline -->
                <div class="dashboard-headline">
                    <h3> My Bids</h3>
    
                    <!-- Breadcrumbs -->
                    <nav id="breadcrumbs" class="dark">
                        <ul>
                            <li><a href="{{route('home')}}">Home</a></li>
                            <li><a href="{{route('dashboard')}}">Dashboard</a></li>
                            <li>Bids</li>
                        </ul>
                    </nav>
                </div>
        
                <!-- Row -->
                <div class="row justify-content-center">
    
                    @if ($bids->isEmpty())
                    <!-- Dashboard Box -->
                    <div class="col-xl-12">

                        <!-- no projects -->
<div class="no_projects">
         <img src="images/icons/no_project_icon.svg" alt="">
     <h3>No bids have been sent</h3>
 </div> 

<div class="col-xl-12">
<div class="section-headline centered margin-top-0 margin-bottom-45">
<h3>Explore Ubuy</h3>
</div>
</div>


</div>
@endif

   <!-- Dashboard Box -->
   <div class="col-xl-12">
    <div class="dashboard-box margin-top-0">

        <!-- Headline -->
        <div class="headline">
            <h3><i class="icon-material-outline-gavel"></i> Bids List</h3>
        </div>

        <div class="content">
            <ul class="dashboard-box-list">
                    @forelse ($bids as $bid)
                <li>
                    <!-- Job Listing -->
                    <div class="job-listing width-adjustment">

                        <!-- Job Listing Details -->
                        <div class="job-listing-details">

                            <!-- Details -->
                            <div class="job-listing-description">
                                <h3 class="job-listing-title"><a href="{{route('project_chat',base64_encode($bid->id*786))}}">{{$bid->sub_category_name}}</a></h3>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Task Details -->
                    <ul class="dashboard-task-info">
                        <li><strong>₦{{$bid->bid_amount}}</strong></li>
                        <li><strong>{{$bid->bid_message}}</strong><span>Opeining message</span></li>
                    </ul>

                   
                </li>
                @empty
            
                @endforelse
            </ul>
        </div>
    </div>
</div>


         
      
                </div>

@endif
			

    @section('page-js')   
    @endsection

@endsection